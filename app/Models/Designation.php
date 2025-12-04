<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Designation extends Model
{
    use HasFactory;

    // UUID Settings — same as your Department
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'description',
        'status',
        'department_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate UUID — exactly like your Department
        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) Str::uuid();
            }

            // Default status = active if empty — your requested behavior
            if (empty($model->status)) {
                $model->status = 'active';
            }
        });

        // Optional: ensure status is active if empty on update
        static::updating(function ($model) {
            if (empty($model->status)) {
                $model->status = 'active';
            }
        });
    }

    // Scope for active designations (very useful in dropdowns)
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Relationship
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}