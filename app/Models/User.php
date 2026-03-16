<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'ustadz_id',
        'santri_id',
    ];

    /**
     * Relasi ke Ustadz
     */
    public function ustadz(): BelongsTo
    {
        return $this->belongsTo(Ustadz::class);
    }

    /**
     * Relasi ke Santi
     */
    public function Santi(): BelongsTo
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    /**
     * Cek apakah user adalah ustadz dan dapatkan data ustadznya
     */
    public function getUstadzData(): ?Ustadz
    {
        if ($this->hasRole('ustadz') && $this->ustadz_id) {
            return $this->ustadz;
        }
        return null;
    }

    /**
     * Cek apakah user adalah santo dan dapatkan data santrinya
     */
    public function getSantriData(): ?Santri
    {
        if ($this->hasRole('santri') && $this->santri_id) {
            return $this->Santi;
        }
        return null;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
