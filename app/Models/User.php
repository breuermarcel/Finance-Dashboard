<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'is_admin'
    ];

    protected $hidden = [
        'password',
        'is_admin'
    ];

    protected $casts = [
        'is_admin' => 'boolean'
    ];

    /**
     * @param array $casts
     */
    public function setCasts(array $casts): void
    {
        $this->casts = $casts;
    }

    /**
     * @return mixed
     */
    public function stockAnalysisPreferences(): mixed
    {
        return $this->hasMany(UserAnalysisSettings::class);
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return auth()->user()->is_admin;
    }
}
