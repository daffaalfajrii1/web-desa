<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->json('attachments')->nullable()->after('complaint_text');
        });

        DB::table('complaints')->orderBy('id')->lazyById(100)->each(function ($row) {
            $legacy = $row->attachment ?? null;
            $payload = $legacy ? json_encode([$legacy]) : null;
            DB::table('complaints')->where('id', $row->id)->update(['attachments' => $payload]);
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('attachment')->nullable()->after('complaint_text');
        });

        DB::table('complaints')->orderBy('id')->lazyById(100)->each(function ($row) {
            $first = null;
            if ($row->attachments !== null && $row->attachments !== '') {
                $decoded = json_decode((string) $row->attachments, true);
                $first = is_array($decoded) && $decoded !== [] ? reset($decoded) : null;
            }
            DB::table('complaints')->where('id', $row->id)->update(['attachment' => $first]);
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('attachments');
        });
    }
};
