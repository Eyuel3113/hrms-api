<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Shift extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'late_threshold_minutes',
        'half_day_minutes',
        'overtime_rate',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time'   => 'string',
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
        'overtime_rate' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->is_active)) {
                $model->is_active = true;
            }
        });
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}