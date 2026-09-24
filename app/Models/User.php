<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'status_akun'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'status_akun' => 'string',
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',             
        'email',
        'password',
        'nomor_identitas',  
        'kategori',         
        'no_telepon',       
        'fakultas',         
        'program_studi',   
        'role',             
        'status_akun',      
    ];
}
    // Helper untuk cek verified
    public function isVerified(): bool
    {
        return $this->status_akun === 'verified';
    }

    // Helper untuk role
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
