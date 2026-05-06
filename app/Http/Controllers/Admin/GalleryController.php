<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
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
                    $q->where('title', 'like', '%'.$search.'%')
                        ->orWhere('slug', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('location', 'like', '%'.$search.'%')
                        ->orWhere('youtube_url', 'like', '%'.$search.'%');
                });
            })
            ->when(in_array($mediaType, [Gallery::TYPE_PHOTO, Gallery::TYPE_VIDEO], true), function ($query) use ($mediaType) {
                $query->where('media_type', $mediaType);
            })
            ->when(in_array($status, [Gallery::STATUS_DRAFT, Gallery::STATUS_PUBLISHED], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
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
        $data['created_by'] = auth()->id();
        $data['published_at'] = $data['status'] === Gallery::STATUS_PUBLISHED ? now() : null;

        if ($data['media_type'] === Gallery::TYPE_PHOTO) {
            $images = $this->collectPhotoUploads($request, false);
            $images = array_values(array_filter($images));

            if (count($images) === 0) {
                throw ValidationException::withMessages([
                    'images' => 'Minimal satu foto wajib diunggah untuk album foto.',
                ]);
            }

            $paths = [];
            foreach ($images as $image) {
                $paths[] = $image->store('galleries/photos', 'public');
            }

            $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title']);
            $data['youtube_url'] = null;
            $data['youtube_id'] = null;
            $data['photo_paths'] = $paths;
            $data['image_path'] = $paths[0];

            Gallery::create($this->payloadFromData($data));

            return redirect()->route('admin.galeri.index')
                ->with('success', 'Album galeri berhasil dibuat dengan '.count($paths).' foto.');
        }

        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title']);
        $data['youtube_id'] = $this->extractYoutubeId($data['youtube_url'] ?? null);

        if (! $data['youtube_id']) {
            throw ValidationException::withMessages([
                'youtube_url' => 'Masukkan link YouTube yang valid.',
            ]);
        }

        $data['image_path'] = null;
        $data['photo_paths'] = null;

        Gallery::create($this->payloadFromData($data));

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Galeri video berhasil ditambahkan.');
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
        $data['created_by'] = $gallery->created_by ?: auth()->id();
        $data['published_at'] = $data['status'] === Gallery::STATUS_PUBLISHED
            ? ($gallery->published_at ?: now())
            : null;

        if ($data['media_type'] === Gallery::TYPE_VIDEO) {
            if ($gallery->is_photo) {
                foreach ($gallery->photoPathsList() as $path) {
                    $this->deletePhoto($path);
                }
            }

            $data['youtube_id'] = $this->extractYoutubeId($data['youtube_url'] ?? null);

            if (! $data['youtube_id']) {
                throw ValidationException::withMessages([
                    'youtube_url' => 'Masukkan link YouTube yang valid.',
                ]);
            }

            $data['image_path'] = null;
            $data['photo_paths'] = null;

            $gallery->update($this->payloadFromData($data));

            return redirect()->route('admin.galeri.index')
                ->with('success', 'Galeri berhasil diperbarui sebagai video.');
        }

        $newImages = $this->collectPhotoUploads($request, true);
        $newImages = array_values(array_filter($newImages));

        $removeRequested = $request->input('remove_photos', []);
        $removeRequested = is_array($removeRequested) ? $removeRequested : [];
        $allowedRemove = array_values(array_intersect($removeRequested, $gallery->photoPathsList()));

        foreach ($allowedRemove as $path) {
            $this->deletePhoto($path);
        }

        $keptPaths = array_values(array_diff($gallery->photoPathsList(), $allowedRemove));

        $newPaths = [];
        foreach ($newImages as $image) {
            $newPaths[] = $image->store('galleries/photos', 'public');
        }

        $allPaths = array_merge($keptPaths, $newPaths);

        if (count($allPaths) === 0) {
            throw ValidationException::withMessages([
                'new_images' => 'Album foto harus memiliki minimal satu gambar. Unggah foto baru atau batalkan penghapusan.',
            ]);
        }

        $data['youtube_url'] = null;
        $data['youtube_id'] = null;
        $data['photo_paths'] = $allPaths;
        $data['image_path'] = $allPaths[0];

        $gallery->update($this->payloadFromData($data));

        $added = count($newPaths);

        return redirect()->route('admin.galeri.index')
            ->with(
                'success',
                $added > 0
                    ? 'Album diperbarui; '.$added.' foto baru ditambahkan.'
                    : 'Album foto berhasil diperbarui.'
            );
    }

    public function destroy(Gallery $gallery)
    {
        foreach ($gallery->photoPathsList() as $path) {
            $this->deletePhoto($path);
        }
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
            'remove_photos' => 'nullable|array',
            'remove_photos.*' => 'nullable|string|max:500',
            'youtube_url' => 'nullable|required_if:media_type,video|url|max:500',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'taken_at' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:draft,published',
        ]);
    }

    private function payloadFromData(array $data): array
    {
        unset(
            $data['image'],
            $data['images'],
            $data['new_images'],
            $data['remove_photos'],
        );

        return $data;
    }

    /**
     * Mengumpulkan semua unggahan gambar; beberapa browser gagal kirim beberapa file kalau nama input hanya bergantung ke DataTransfer pada input tersembunyi.
     *
     * @return list<UploadedFile>
     */
    private function collectPhotoUploads(Request $request, bool $forEdit): array
    {
        $keys = $forEdit ? ['new_images', 'images', 'image'] : ['images', 'image'];
        $fromKeys = $this->uploadedPhotos($request, $keys);
        $fromAll = [];
        foreach (Arr::flatten($request->allFiles()) as $uploaded) {
            if ($uploaded instanceof UploadedFile && $uploaded->isValid()) {
                $fromAll[] = $uploaded;
            }
        }

        $merged = array_merge($fromKeys, $fromAll);
        $seen = [];
        $out = [];
        foreach ($merged as $file) {
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                continue;
            }
            $key = $file->getClientOriginalName()."\0".$file->getSize();
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $out[] = $file;
        }

        return $out;
    }

    /**
     * @return list<UploadedFile>
     */
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
                if ($file instanceof UploadedFile && $file->isValid()) {
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
            $slug = $base.'-'.$counter;
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
