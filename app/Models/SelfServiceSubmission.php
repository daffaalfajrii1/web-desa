<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SelfServiceSubmission extends Model
{
    protected $fillable = [
        'self_service_id',
        'registration_number',
        'applicant_name',
        'applicant_nik',
        'applicant_phone',
        'applicant_email',
        'applicant_address',
        'form_data',
        'attachments',
        'status',
        'admin_note',
        'result_type',
        'result_title',
        'result_note',
        'result_file',
        'submitted_at',
        'processed_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'form_data' => 'array',
            'attachments' => 'array',
            'submitted_at' => 'datetime',
            'processed_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SelfServiceSubmission $submission) {
            if (empty($submission->submitted_at)) {
                $submission->submitted_at = now();
            }

            if (empty($submission->registration_number)) {
                $service = $submission->service ?: SelfService::find($submission->self_service_id);
                $submission->registration_number = static::generateRegistrationNumber($service);
            }
        });
    }

    public function service()
    {
        return $this->belongsTo(SelfService::class, 'self_service_id');
    }

    public static function generateRegistrationNumber(?SelfService $service = null): string
    {
        $serviceCode = $service?->service_code ?: 'LAY';
        $servicePart = Str::of($serviceCode)->replaceMatches('/[^A-Za-z0-9]/', '')->upper()->toString();
        $prefix = 'REG-' . now()->format('Ymd') . '-' . $servicePart . '-';

        $latestCode = static::where('registration_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('registration_number');

        $nextNumber = 1;

        if ($latestCode) {
            $latestNumber = (int) substr($latestCode, strrpos($latestCode, '-') + 1);
            $nextNumber = $latestNumber + 1;
        }

        do {
            $code = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (static::where('registration_number', $code)->exists());

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

    public static function resultTypes(): array
    {
        return [
            'surat' => 'Surat / Dokumen Siap',
            'pemberitahuan' => 'Pemberitahuan Datang ke Kantor Desa',
            'ditolak' => 'Permohonan Ditolak',
            'lainnya' => 'Lainnya',
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

    public function getResultTypeLabelAttribute(): string
    {
        return $this->result_type
            ? (static::resultTypes()[$this->result_type] ?? ucfirst((string) $this->result_type))
            : '-';
    }

    public function getDisplayApplicantNameAttribute(): string
    {
        return $this->applicant_name
            ?: data_get($this->form_data, 'nama_pemohon')
            ?: data_get($this->form_data, 'nama')
            ?: '-';
    }

    public function getDisplayApplicantContactAttribute(): string
    {
        return $this->applicant_phone
            ?: data_get($this->form_data, 'no_hp')
            ?: data_get($this->form_data, 'telepon')
            ?: data_get($this->form_data, 'nomor_hp')
            ?: '-';
    }
}
