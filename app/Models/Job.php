<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Job
 *
 * @property string $id
 * @property string $department_id
 * @property string|null $designation_id
 * @property string $title
 * @property string $description
 * @property int $vacancy
 * @property \Illuminate\Support\Carbon $deadline
 * @property string $status
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Department|null $department
 * @property-read \App\Models\Designation|null $designation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Candidate[] $candidates
 */
class Job extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'job_postings';

protected $fillable = [
    'title', 'department_id', 'designation_id', 'description',
    'vacancy', 'deadline', 'status', 'is_active',
    'min_salary', 'max_salary', 'salary_currency', 'salary_negotiable', 'show_salary'
];

protected $casts = [
    'deadline'          => 'date',
    'is_active'         => 'boolean',
    'salary_negotiable' => 'boolean',
    'show_salary'       => 'boolean',
    'min_salary'        => 'decimal:2',
    'max_salary'        => 'decimal:2',
];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function department() { return $this->belongsTo(Department::class); }
    public function designation() { return $this->belongsTo(Designation::class); }
    public function candidates() { return $this->hasMany(Candidate::class); }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'open');
    }
}