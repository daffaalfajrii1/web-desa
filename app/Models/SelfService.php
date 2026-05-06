<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SelfService extends Model
{
    protected $fillable = [
        'service_code',
        'service_name',
        'slug',
        'description',
        'requirements',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SelfService $service) {
            if (empty($service->service_code)) {
                $service->service_code = static::generateCode();
            }
            if (trim((string) ($service->slug ?? '')) === '') {
                $base = Str::slug((string) $service->service_name) ?: 'layanan';
                $service->slug = static::ensureUniqueSlug($base, null);
            }
        });

        static::updating(function (SelfService $service) {
            if (trim((string) ($service->slug ?? '')) === '') {
                $base = Str::slug((string) $service->service_name) ?: 'layanan';
                $service->slug = static::ensureUniqueSlug($base, $service->id);
            }
        });
    }

    public static function ensureUniqueSlug(string $base, ?int $exceptId): string
    {
        $slug = $base;
        $i = 2;
        while (static::query()
            ->where('slug', $slug)
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public static function generateCode(): string
    {
        $prefix = 'LAY-';

        $latestCode = static::where('service_code', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('service_code');

        $nextNumber = 1;

        if ($latestCode) {
            $latestNumber = (int) substr($latestCode, strrpos($latestCode, '-') + 1);
            $nextNumber = $latestNumber + 1;
        }

        do {
            $code = $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (static::where('service_code', $code)->exists());

        return $code;
    }

    public function fields()
    {
        return $this->hasMany(SelfServiceField::class)->orderBy('sort_order')->orderBy('id');
    }

    public function submissions()
    {
        return $this->hasMany(SelfServiceSubmission::class);
    }
}
