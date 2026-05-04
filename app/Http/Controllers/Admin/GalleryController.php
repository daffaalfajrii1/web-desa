<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $mediaType = $request->get('media_type');
        $status = $request->get('status');

        $stats = [
            'total' => Gallery::count(),
            'photo' => Gallery::where('media_type', Gallery::TYPE_PHOTO)->count(),
            'video' => Gallery::where('media_type', Gallery::TYPE_VIDEO)->count(),
            'published' => Gallery::where('status', Gallery::STATUS_PUBLISHED)->count(),
        ];

        $items = Gallery::with('author')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('location', 'like', '%' . $search . '%')
                        ->orWhere('youtube_url', 'like', '%' . $search . '%');
                });
            })
            ->when(in_array($mediaType, [Gallery::TYPE_PHOTO, Gallery::TYPE_VIDEO], true), function ($query) use ($mediaType) {
                $query->where('media_type', $mediaType);
            })
            ->when(in_array($status, [Gallery::STATUS_DRAFT, Gallery::STATUS_PUBLISHED], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.galeri.index', compact('items', 'stats'));
    }

    public function create()
    {
        return view('admin.galeri.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['created_by'] = auth()->id();
        $data['published_at'] = $data['status'] === Gallery::STATUS_PUBLISHED ? now() : null;

        if ($data['media_type'] === Gallery::TYPE_PHOTO) {
            $images = $request->file('images', []);

            if ($request->hasFile('image')) {
                $images[] = $request->file('image');
            }

            $images = array_filter(is_array($images) ? $images : [$images]);

            if (count($images) === 0) {
                throw ValidationException::withMessages([
                    'images' => 'Minimal satu foto wajib diunggah untuk galeri foto.',
                ]);
            }

            $storedPaths = [];

            DB::beginTransaction();

            try {
                foreach ($images as $index => $image) {
                    $payload = $this->payloadFromData($data);
                    $payload['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title']);
                    $payload['sort_order'] = (int) $data['sort_order'] + $index;
                    $payload['image_path'] = $image->store('galleries/photos', 'public');
                    $payload['youtube_url'] = null;
                    $payload['youtube_id'] = null;
                    $storedPaths[] = $payload['image_path'];

                    Gallery::create($payload);
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();

                foreach ($storedPaths as $path) {
                    $this->deletePhoto($path);
                }

                throw $e;
            }

            return redirect()->route('admin.galeri.index')
                ->with('success', count($images) . ' foto galeri berhasil ditambahkan.');
        }

        if ($data['media_type'] === Gallery::TYPE_VIDEO) {
            $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title']);
            $data['youtube_id'] = $this->extractYoutubeId($data['youtube_url'] ?? null);

            if (! $data['youtube_id']) {
                throw ValidationException::withMessages([
                    'youtube_url' => 'Masukkan link YouTube yang valid.',
                ]);
            }

            $data['image_path'] = null;
        }

        Gallery::create($this->payloadFromData($data));

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function show(Gallery $gallery)
    {
        $gallery->load('author');

        return view('admin.galeri.show', [
            'item' => $gallery,
        ]);
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galeri.edit', [
            'item' => $gallery,
        ]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $this->validatedData($request, $gallery);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title'], $gallery->id);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['created_by'] = $gallery->created_by ?: auth()->id();
        $data['published_at'] = $data['status'] === Gallery::STATUS_PUBLISHED
            ? ($gallery->published_at ?: now())
            : null;

        if ($data['media_type'] === Gallery::TYPE_PHOTO) {
            $newImages = $this->uploadedPhotos($request, ['new_images', 'images']);
            $deleteCurrentPhoto = $request->boolean('delete_current_photo') && $gallery->is_photo;
            $storedPaths = [];
            $oldPhotoPath = $gallery->image_path;

            if (! $deleteCurrentPhoto && ! $gallery->image_path && count($newImages) === 0) {
                throw ValidationException::withMessages([
                    'new_images' => 'Minimal satu foto wajib ditambahkan untuk galeri foto.',
                ]);
            }

            $data['youtube_url'] = null;
            $data['youtube_id'] = null;

            DB::beginTransaction();

            try {
                if ($deleteCurrentPhoto) {
                    $gallery->delete();
                } else {
                    $payload = $this->payloadFromData($data);

                    if (! $gallery->image_path && count($newImages) > 0) {
                        $firstImage = array_shift($newImages);
                        $payload['image_path'] = $firstImage->store('galleries/photos', 'public');
                        $storedPaths[] = $payload['image_path'];
                    }

                    $gallery->update($payload);
                }

                foreach ($newImages as $index => $image) {
                    $payload = $this->payloadFromData($data);
                    $payload['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title']);
                    $payload['sort_order'] = (int) $data['sort_order'] + ($deleteCurrentPhoto ? $index : $index + 1);
                    $payload['image_path'] = $image->store('galleries/photos', 'public');
                    $payload['youtube_url'] = null;
                    $payload['youtube_id'] = null;
                    $storedPaths[] = $payload['image_path'];

                    Gallery::create($payload);
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();

                foreach ($storedPaths as $path) {
                    $this->deletePhoto($path);
                }

                throw $e;
            }

            if ($deleteCurrentPhoto) {
                $this->deletePhoto($oldPhotoPath);

                return redirect()->route('admin.galeri.index')
                    ->with(
                        'success',
                        count($newImages) > 0
                            ? 'Foto saat ini dihapus dan ' . count($newImages) . ' foto baru berhasil ditambahkan.'
                            : 'Foto galeri berhasil dihapus.'
                    );
            }

            return redirect()->route('admin.galeri.index')
                ->with(
                    'success',
                    count($newImages) > 0
                        ? 'Galeri berhasil diperbarui dan ' . count($newImages) . ' foto baru ditambahkan.'
                        : 'Galeri berhasil diperbarui.'
                );
        }

        if ($data['media_type'] === Gallery::TYPE_VIDEO) {
            $data['youtube_id'] = $this->extractYoutubeId($data['youtube_url'] ?? null);

            if (! $data['youtube_id']) {
                throw ValidationException::withMessages([
                    'youtube_url' => 'Masukkan link YouTube yang valid.',
                ]);
            }

            $this->deletePhoto($gallery->image_path);
            $data['image_path'] = null;
        }

        $gallery->update($this->payloadFromData($data));

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        $this->deletePhoto($gallery->image_path);
        $gallery->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Galeri berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Gallery $gallery = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'media_type' => 'required|in:photo,video',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'new_images' => 'nullable|array',
            'new_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'delete_current_photo' => 'nullable|boolean',
            'youtube_url' => 'nullable|required_if:media_type,video|url|max:500',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'taken_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:draft,published',
        ]);
    }

    private function payloadFromData(array $data): array
    {
        unset($data['image'], $data['images'], $data['new_images'], $data['delete_current_photo']);

        return $data;
    }

    private function uploadedPhotos(Request $request, array $keys): array
    {
        $photos = [];

        foreach ($keys as $key) {
            if (! $request->hasFile($key)) {
                continue;
            }

            $files = $request->file($key, []);
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $file) {
                if ($file) {
                    $photos[] = $file;
                }
            }
        }

        return $photos;
    }

    private function makeUniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while (
            Gallery::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function extractYoutubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $parts = parse_url(trim($url));

        if (! $parts || empty($parts['host'])) {
            return null;
        }

        $host = preg_replace('/^www\./', '', strtolower($parts['host']));
        $path = trim($parts['path'] ?? '', '/');
        $query = [];
        parse_str($parts['query'] ?? '', $query);
        $isYoutubeHost = $host === 'youtube.com'
            || str_ends_with($host, '.youtube.com')
            || $host === 'youtube-nocookie.com'
            || str_ends_with($host, '.youtube-nocookie.com');

        $id = null;

        if ($host === 'youtu.be') {
            $id = explode('/', $path)[0] ?? null;
        }

        if ($isYoutubeHost) {
            if (($query['v'] ?? null) !== null) {
                $id = $query['v'];
            } elseif (Str::startsWith($path, ['embed/', 'shorts/', 'live/'])) {
                $segments = explode('/', $path);
                $id = $segments[1] ?? null;
            }
        }

        return $id && preg_match('/^[A-Za-z0-9_-]{11}$/', $id) ? $id : null;
    }

    private function deletePhoto(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
