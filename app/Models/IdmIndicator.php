<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdmIndicator extends Model
{
    protected $fillable = [
        'idm_summary_id',
        'category',
        'indicator_no',
        'indicator_name',
        'score',
        'description',
        'activity',
        'value',
        'executor_central',
        'executor_province',
        'executor_regency',
        'executor_village',
        'executor_csr',
        'executor_other',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function summary()
    {
        return $this->belongsTo(IdmSummary::class, 'idm_summary_id');
    }
}