<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public const TYPE_PHOTO = 'photo';

    public const TYPE_VIDEO = 'video';

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'title',
        'slug',
        'media_type',
        'image_path',
        'photo_paths',
        'youtube_url',
        'youtube_id',
        'description',
        'location',
        'taken_at',
        'is_featured',
        'status',
        'views',
        'published_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'photo_paths' => 'array',
            'taken_at' => 'date',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'views' => 'integer',
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** @return list<string> Stored paths relative to public disk */
    public function photoPathsList(): array
    {
        if ($this->media_type !== self::TYPE_PHOTO) {
            return [];
        }

        $list = $this->photo_paths;
        if (is_array($list) && $list !== []) {
            $clean = [];
            foreach ($list as $p) {
                if (is_string($p) && $p !== '') {
                    $clean[] = $p;
                }
            }

            return array_values(array_unique($clean));
        }

        if (is_string($this->image_path) && $this->image_path !== '') {
            return [$this->image_path];
        }

        return [];
    }

    /** @return list<string> Full URLs for Blade/JS */
    public function photoUrls(): array
    {
        return array_map(fn ($p) => asset('storage/'.$p), $this->photoPathsList());
    }

    public function photoCount(): int
    {
        return count($this->photoPathsList());
    }

    public function getIsPhotoAttribute(): bool
    {
        return $this->media_type === self::TYPE_PHOTO;
    }

    public function getIsVideoAttribute(): bool
    {
        return $this->media_type === self::TYPE_VIDEO;
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (! $this->youtube_id) {
            return null;
        }

        return 'https://www.youtube.com/embed/'.$this->youtube_id;
    }

    public function getYoutubeThumbnailUrlAttribute(): ?string
    {
        if (! $this->youtube_id) {
            return null;
        }

        return 'https://img.youtube.com/vi/'.$this->youtube_id.'/hqdefault.jpg';
    }

    public function getMediaUrlAttribute(): ?string
    {
        if ($this->is_photo) {
            $paths = $this->photoPathsList();
            $first = $paths[0] ?? null;

            return $first ? asset('storage/'.$first) : null;
        }

        return $this->youtube_thumbnail_url;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === self::STATUS_PUBLISHED ? 'Published' : 'Draft';
    }
}
