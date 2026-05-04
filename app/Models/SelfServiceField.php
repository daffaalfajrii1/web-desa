<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfServiceField extends Model
{
    protected $fillable = [
        'self_service_id',
        'field_name',
        'field_label',
        'field_type',
        'placeholder',
        'help_text',
        'is_required',
        'options',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'options' => 'array',
        ];
    }

    public function service()
    {
        return $this->belongsTo(SelfService::class, 'self_service_id');
    }

    public static function fieldTypes(): array
    {
        return [
            'text' => 'Text',
            'textarea' => 'Textarea',
            'number' => 'Number',
            'date' => 'Date',
            'select' => 'Select',
            'radio' => 'Radio',
            'checkbox' => 'Checkbox',
            'file' => 'File',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return static::fieldTypes()[$this->field_type] ?? ucfirst((string) $this->field_type);
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->field_type) {
            'text', 'textarea' => 'badge-primary',
            'number', 'date' => 'badge-info',
            'select', 'radio', 'checkbox' => 'badge-success',
            'file' => 'badge-warning',
            default => 'badge-secondary',
        };
    }
}
