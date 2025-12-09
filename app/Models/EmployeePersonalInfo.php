<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePersonalInfo extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'employee_id', 'first_name', 'last_name', 'email', 'phone', 'photo',
        'date_of_birth', 'gender', 'marital_status', 'nationality',
        'address', 'city', 'state', 'zip_code'
    ];

    protected $appends = ['photo_url'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) \Illuminate\Support\Str::uuid());
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->photo)) {
            return asset('storage/' . $this->photo);
        }

        return asset('default.svg');
    }
}