<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdmSummary extends Model
{
    protected $fillable = [
        'year',
        'iks_score',
        'ike_score',
        'ikl_score',
        'idm_score',
        'idm_status',
        'target_status',
        'minimal_target_score',
        'additional_score_needed',
        'description',
        'is_active',
    ];

    protected $casts = [
        'iks_score' => 'decimal:4',
        'ike_score' => 'decimal:4',
        'ikl_score' => 'decimal:4',
        'idm_score' => 'decimal:4',
        'minimal_target_score' => 'decimal:4',
        'additional_score_needed' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function indicators()
    {
        return $this->hasMany(IdmIndicator::class)->orderBy('category')->orderBy('indicator_no');
    }

    public static function calculateIdmScore($iks, $ike, $ikl): float
    {
        return round(((float)$iks + (float)$ike + (float)$ikl) / 3, 4);
    }

    public static function resolveStatus($score): string
    {
        $score = (float)$score;

        if ($score <= 0.4907) {
            return 'Sangat Tertinggal';
        } elseif ($score <= 0.5989) {
            return 'Tertinggal';
        } elseif ($score <= 0.7071) {
            return 'Berkembang';
        } elseif ($score <= 0.8155) {
            return 'Maju';
        }

        return 'Mandiri';
    }

    public static function resolveTargetStatus($score): string
    {
        $score = (float)$score;

        if ($score <= 0.4907) {
            return 'Tertinggal';
        } elseif ($score <= 0.5989) {
            return 'Berkembang';
        } elseif ($score <= 0.7071) {
            return 'Maju';
        } elseif ($score <= 0.8155) {
            return 'Mandiri';
        }

        return 'Mandiri';
    }

    public static function resolveMinimalTargetScore($score): float
    {
        $score = (float)$score;

        if ($score <= 0.4907) {
            return 0.4908;
        } elseif ($score <= 0.5989) {
            return 0.5990;
        } elseif ($score <= 0.7071) {
            return 0.7072;
        } elseif ($score <= 0.8155) {
            return 0.8156;
        }

        return (float)$score;
    }

    public static function resolveAdditionalNeeded($score): float
    {
        $score = (float)$score;
        $minimal = self::resolveMinimalTargetScore($score);

        if ($score > 0.8155) {
            return 0;
        }

        return round(max($minimal - $score, 0), 4);
    }
}