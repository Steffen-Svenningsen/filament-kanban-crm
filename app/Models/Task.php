<?php

namespace App\Models;

use App\TaskStatus;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'position',
        'user_id'
    ];

    protected $casts = [
        'status' => TaskStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
