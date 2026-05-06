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
        if (! Schema::hasTable('employee_positions')) {
            Schema::create('employee_positions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('position_type')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('employees') && ! Schema::hasColumn('employees', 'employee_position_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->foreignId('employee_position_id')
                    ->nullable()
                    ->constrained('employee_positions')
                    ->nullOnDelete();
            });
        }

        $this->backfillExistingPositions();
    }

    public function down(): void
    {
        if (Schema::hasTable('employees') && Schema::hasColumn('employees', 'employee_position_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropConstrainedForeignId('employee_position_id');
            });
        }

        Schema::dropIfExists('employee_positions');
    }

    private function backfillExistingPositions(): void
    {
        if (
            ! Schema::hasTable('employees')
            || ! Schema::hasTable('employee_positions')
            || ! Schema::hasColumn('employees', 'position')
            || ! Schema::hasColumn('employees', 'employee_position_id')
        ) {
            return;
        }

        $employees = DB::table('employees')
            ->whereNull('employee_position_id')
            ->whereNotNull('position')
            ->where('position', '!=', '')
            ->orderBy('id')
            ->get(['id', 'position', 'position_type']);

        foreach ($employees as $employee) {
            $positionId = $this->positionIdFor((string) $employee->position, $employee->position_type);

            DB::table('employees')
                ->where('id', $employee->id)
                ->update(['employee_position_id' => $positionId]);
        }
    }

    private function positionIdFor(string $name, ?string $positionType): int
    {
        $existing = DB::table('employee_positions')->where('name', $name)->first();

        if ($existing) {
            return (int) $existing->id;
        }

        $now = now();
        $id = DB::table('employee_positions')->insertGetId([
            'name' => $name,
            'slug' => $this->uniqueSlug($name),
            'position_type' => $positionType,
            'sort_order' => (int) DB::table('employee_positions')->max('sort_order') + 1,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return (int) $id;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while (DB::table('employee_positions')->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
};
