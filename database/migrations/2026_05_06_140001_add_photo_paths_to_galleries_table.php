<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->json('photo_paths')->nullable()->after('image_path');
        });

        foreach (DB::table('galleries')->where('media_type', 'photo')->whereNotNull('image_path')->cursor() as $row) {
            $path = trim((string) ($row->image_path ?? ''));
            if ($path === '') {
                continue;
            }
            DB::table('galleries')->where('id', $row->id)->update([
                'photo_paths' => json_encode([$path]),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('photo_paths');
        });
    }
};
