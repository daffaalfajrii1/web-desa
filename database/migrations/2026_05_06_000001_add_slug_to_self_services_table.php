<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('self_services', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('service_name');
        });

        $this->backfillSlugs();
    }

    public function down(): void
    {
        Schema::table('self_services', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }

    private function backfillSlugs(): void
    {
        $rows = DB::table('self_services')->select('id', 'service_name')->orderBy('id')->get();
        foreach ($rows as $row) {
            $base = Str::slug((string) $row->service_name) ?: 'layanan';
            $slug = $base;
            $n = 2;
            while (DB::table('self_services')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = $base.'-'.$n;
                $n++;
            }

            DB::table('self_services')->where('id', $row->id)->update(['slug' => $slug]);
        }
    }
};
