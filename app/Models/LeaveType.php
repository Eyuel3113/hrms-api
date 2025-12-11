<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LeaveType extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'default_days',
        'requires_approval',
        'is_paid',
        'is_active',
    ];

    protected $casts = [
        'requires_approval' => 'boolean',
        'is_paid'            => 'boolean',
        'is_active'          => 'boolean',
        'default_days'       => 'integer',
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

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}