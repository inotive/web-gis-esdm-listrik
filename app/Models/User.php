<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\RegVillage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'status',
        'image',
        'jabatan',
        'phone',
        'address',
        'village_id',
        'identity_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getRoleSelectAttribute()
    {
        return $this->roles->first();
    }

    public function permohonan(): BelongsToMany
    {
        return $this->belongsToMany(Permohonan::class, 'permohonan_users', 'user_id', 'permohonan_id');
    }

    public function permohonanUsers(): HasMany
    {
        return $this->hasMany(PermohonanUser::class, 'user_id');
    }

    public function permohonanDocuments(): HasMany
    {
        return $this->hasMany(PermohonanUserDocument::class, 'user_id');
    }

    public function village()
    {
        return $this->belongsTo(RegVillage::class, 'village_id');
    }
}
