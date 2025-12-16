<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectMember extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'project_members';

    protected $fillable = ['project_id', 'employee_id', 'rating', 'feedback', 'rated_at'];

    protected $casts = [
        'rated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function project() { return $this->belongsTo(Project::class); }
    public function employee() { return $this->belongsTo(Employee::class); }
}