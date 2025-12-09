<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Shift extends Model
{
    use HasFactory;

    // UUID — YOUR WAY
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

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
            if (! $model->id) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->is_active)) {
                $model->is_active = true;
            }
            if (empty($model->late_threshold_minutes)) {
                $model->late_threshold_minutes = 15;
            }
            if (empty($model->half_day_minutes)) {
                $model->half_day_minutes = 240;
            }
            if (empty($model->overtime_rate)) {
                $model->overtime_rate = 1.50;
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