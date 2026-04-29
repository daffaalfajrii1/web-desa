<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAssistanceRecipient extends Model
{
    protected $fillable = [
    'social_assistance_program_id',
    'hamlet_id',
    'name',
    'nik',
    'kk_number',
    'address',
    'amount',
    'benefit_type',
    'item_description',
    'unit',
    'quantity',
    'phone',
    'verification_status',
    'distribution_status',
    'distributed_at',
    'receiver_name',
    'notes',
];

    protected function casts(): array
{
    return [
        'distributed_at' => 'date',
        'amount' => 'decimal:2',
        'quantity' => 'decimal:2',
    ];
}

    public function program()
    {
        return $this->belongsTo(SocialAssistanceProgram::class, 'social_assistance_program_id');
    }

    public function hamlet()
    {
        return $this->belongsTo(Hamlet::class);
    }
}