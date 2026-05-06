<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\PpidRequest;
use App\Models\SelfService;
use App\Models\SelfServiceField;
use App\Models\SelfServiceSubmission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoServiceInteractionSeeder extends Seeder
{
    public function run(): void
    {
        $services = $this->seedServices();
        $this->seedSubmissions($services);
        $this->seedComplaints();
        $this->seedPpidRequests();
    }

    private function seedServices(): array
    {
        if (! Schema::hasTable('self_services')) {
            return [];
        }

        $definitions = [
            ['code' => 'LAY-001', 'name' => 'Surat Keterangan Usaha', 'slug' => 'surat-keterangan-usaha'],
            ['code' => 'LAY-002', 'name' => 'Surat Keterangan Domisili', 'slug' => 'surat-keterangan-domisili'],
            ['code' => 'LAY-003', 'name' => 'Surat Pengantar KK/KTP', 'slug' => 'surat-pengantar-kk-ktp'],
            ['code' => 'LAY-004', 'name' => 'Surat Keterangan Tidak Mampu', 'slug' => 'surat-keterangan-tidak-mampu'],
        ];

        $services = [];
        foreach ($definitions as $i => $def) {
            $payload = [
                'service_name' => $def['name'],
                'description' => 'Layanan administrasi desa untuk kebutuhan dokumen warga.',
                'requirements' => 'Fotokopi KTP, KK, dan dokumen pendukung lainnya.',
                'is_active' => true,
                'sort_order' => $i + 1,
            ];
            if (Schema::hasColumn('self_services', 'slug')) {
                $payload['slug'] = $def['slug'];
            }

            $service = SelfService::query()->updateOrCreate(
                ['service_code' => $def['code']],
                $payload
            );
            $services[] = $service;
            $this->seedServiceFields($service);
        }

        return $services;
    }

    private function seedServiceFields(SelfService $service): void
    {
        if (! Schema::hasTable('self_service_fields')) {
            return;
        }

        $fields = [
            ['field_name' => 'nama_pemohon', 'field_label' => 'Nama Pemohon', 'field_type' => 'text', 'is_required' => true, 'sort_order' => 1],
            ['field_name' => 'nik', 'field_label' => 'NIK', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 2],
            ['field_name' => 'no_hp', 'field_label' => 'No. HP / WhatsApp', 'field_type' => 'text', 'is_required' => true, 'sort_order' => 3],
            ['field_name' => 'alamat', 'field_label' => 'Alamat', 'field_type' => 'textarea', 'is_required' => true, 'sort_order' => 4],
            ['field_name' => 'lampiran_ktp', 'field_label' => 'Lampiran KTP', 'field_type' => 'file', 'is_required' => true, 'sort_order' => 5],
        ];

        foreach ($fields as $field) {
            SelfServiceField::query()->updateOrCreate(
                ['self_service_id' => $service->id, 'field_name' => $field['field_name']],
                array_merge($field, [
                    'placeholder' => null,
                    'help_text' => 'Isi sesuai data yang valid.',
                    'options' => null,
                ])
            );
        }
    }

    private function seedSubmissions(array $services): void
    {
        if (! Schema::hasTable('self_service_submissions')) {
            return;
        }

        foreach ($services as $i => $service) {
            if (! $service instanceof SelfService) {
                continue;
            }

            for ($j = 1; $j <= 3; $j++) {
                $date = Carbon::now()->subDays(($i * 3) + $j);
                $name = 'Warga Demo '.$service->id.$j;

                SelfServiceSubmission::query()->updateOrCreate(
                    ['registration_number' => 'REG-'.now()->format('Ymd').'-'.$service->service_code.'-'.str_pad((string) ($i * 3 + $j), 4, '0', STR_PAD_LEFT)],
                    [
                        'self_service_id' => $service->id,
                        'applicant_name' => $name,
                        'applicant_nik' => '17020'.str_pad((string) ($i * 3 + $j), 10, '0', STR_PAD_LEFT),
                        'applicant_phone' => '0812736600'.str_pad((string) ($i * 3 + $j), 2, '0', STR_PAD_LEFT),
                        'applicant_email' => Str::slug($name, '.').'@mail.test',
                        'applicant_address' => 'Dusun '.(($j % 4) + 1).' Desa Tanjung Beringin',
                        'form_data' => ['nama_pemohon' => $name, 'nik' => '17020'.str_pad((string) ($i * 3 + $j), 10, '0', STR_PAD_LEFT)],
                        'attachments' => ['lampiran_ktp' => 'demo/uploads/ktp-'.$service->id.'-'.$j.'.pdf'],
                        'status' => $j === 1 ? 'masuk' : ($j === 2 ? 'diproses' : 'selesai'),
                        'admin_note' => $j === 3 ? 'Dokumen sudah diverifikasi dan siap diambil.' : null,
                        'result_type' => $j === 3 ? 'surat' : null,
                        'result_title' => $j === 3 ? 'Dokumen '.$service->service_name : null,
                        'result_note' => $j === 3 ? 'Silakan unduh dokumen hasil layanan.' : null,
                        'result_file' => $j === 3 ? 'demo/results/result-'.$service->id.'-'.$j.'.pdf' : null,
                        'submitted_at' => $date,
                        'processed_at' => $j >= 2 ? $date->copy()->addDay() : null,
                        'completed_at' => $j === 3 ? $date->copy()->addDays(2) : null,
                    ]
                );
            }
        }
    }

    private function seedComplaints(): void
    {
        if (! Schema::hasTable('complaints')) {
            return;
        }

        $subjects = [
            'Lampu jalan lingkungan padam',
            'Drainase tersumbat saat hujan',
            'Perlu perbaikan jalan gang',
            'Pelayanan administrasi terlambat',
            'Permintaan penertiban sampah',
        ];

        foreach ($subjects as $i => $subject) {
            $submitted = Carbon::now()->subDays(10 - $i);
            Complaint::query()->updateOrCreate(
                ['complaint_code' => 'ADU-'.now()->format('Y').'-'.str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT)],
                [
                    'name' => 'Pelapor '.($i + 1),
                    'nik' => '17030'.str_pad((string) ($i + 1), 10, '0', STR_PAD_LEFT),
                    'phone' => '0812737700'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
                    'email' => 'pelapor'.($i + 1).'@mail.test',
                    'address' => 'Dusun '.(($i % 4) + 1).' Desa Tanjung Beringin',
                    'subject' => $subject,
                    'complaint_text' => 'Warga melaporkan isu ini agar mendapat tindak lanjut dari pemerintah desa.',
                    'attachments' => ['demo/complaints/aduan-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'.jpg'],
                    'status' => $i < 2 ? 'masuk' : ($i < 4 ? 'diproses' : 'selesai'),
                    'admin_note' => $i >= 2 ? 'Sudah ditindaklanjuti oleh tim terkait.' : null,
                    'submitted_at' => $submitted,
                    'resolved_at' => $i === 4 ? $submitted->copy()->addDays(2) : null,
                ]
            );
        }
    }

    private function seedPpidRequests(): void
    {
        if (! Schema::hasTable('ppid_requests')) {
            return;
        }

        for ($i = 1; $i <= 4; $i++) {
            PpidRequest::query()->updateOrCreate(
                ['email' => 'pemohon'.$i.'@mail.test', 'request_content' => 'Permohonan dokumen publik nomor '.$i],
                [
                    'name' => 'Pemohon Informasi '.$i,
                    'institution' => $i % 2 === 0 ? 'Lembaga Sosial Desa' : null,
                    'phone' => '0812738800'.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                    'status' => $i < 3 ? 'processed' : 'new',
                    'admin_note' => $i < 3 ? 'Permohonan diproses sesuai prosedur PPID.' : null,
                    'responded_at' => $i < 3 ? Carbon::now()->subDays(3 - $i) : null,
                    'handled_by' => null,
                ]
            );
        }
    }
}
