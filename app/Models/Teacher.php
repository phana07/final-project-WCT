<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email', 'specialization'];

    /**
     * Get the courses taught by the Teacher.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
