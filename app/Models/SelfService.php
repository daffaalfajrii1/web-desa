<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfService extends Model
{
    protected $fillable = [
        'service_code',
        'service_name',
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
        });
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
