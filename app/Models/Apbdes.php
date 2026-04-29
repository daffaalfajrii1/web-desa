<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apbdes extends Model
{
    protected $table = 'apbdes';

    protected $fillable = [
        'year',
        'pendapatan',
        'belanja',
        'pembiayaan_penerimaan',
        'pembiayaan_pengeluaran',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'pendapatan' => 'decimal:2',
            'belanja' => 'decimal:2',
            'pembiayaan_penerimaan' => 'decimal:2',
            'pembiayaan_pengeluaran' => 'decimal:2',
        ];
    }

    public function getSurplusDefisitAttribute(): float
    {
        return (float) $this->pendapatan - (float) $this->belanja;
    }

    public function getPembiayaanNettoAttribute(): float
    {
        return (float) $this->pembiayaan_penerimaan - (float) $this->pembiayaan_pengeluaran;
    }

    public function getSilpaAttribute(): float
    {
        return $this->surplus_defisit + $this->pembiayaan_netto;
    }
}