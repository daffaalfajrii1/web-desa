<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'complaint_code',
        'name',
        'nik',
        'phone',
        'email',
        'address',
        'subject',
        'complaint_text',
        'attachment',
        'status',
        'admin_note',
        'submitted_at',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Complaint $complaint) {
            if (empty($complaint->complaint_code)) {
                $year = $complaint->submitted_at
                    ? $complaint->submitted_at->format('Y')
                    : now()->format('Y');

                $complaint->complaint_code = static::generateCode((int) $year);
            }

            if (empty($complaint->submitted_at)) {
                $complaint->submitted_at = now();
            }
        });
    }

    public static function generateCode(?int $year = null): string
    {
        $year = $year ?: (int) now()->format('Y');
        $prefix = 'ADU-' . $year . '-';

        $latestCode = static::where('complaint_code', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('complaint_code');

        $nextNumber = 1;

        if ($latestCode) {
            $latestNumber = (int) substr($latestCode, strrpos($latestCode, '-') + 1);
            $nextNumber = $latestNumber + 1;
        }

        do {
            $code = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (static::where('complaint_code', $code)->exists());

        return $code;
    }

    public static function statuses(): array
    {
        return [
            'masuk' => 'Masuk',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return static::statuses()[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'masuk' => 'badge-primary',
            'diproses' => 'badge-warning',
            'selesai' => 'badge-success',
            'ditolak' => 'badge-danger',
            default => 'badge-secondary',
        };
    }
}
