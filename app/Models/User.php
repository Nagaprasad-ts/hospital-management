<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user can access Filament Panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['admin', 'doctor', 'patient']);
    }

    /**
     * Assign default role when a user is created.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            if (!$user->hasRole(['admin', 'doctor', 'patient'])) {
                $user->assignRole('patient'); // Default role
            }
        });
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

}
