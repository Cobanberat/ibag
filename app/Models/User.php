<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Rol sabitleri
    const ROLE_ADMIN = 'admin';
    const ROLE_TEAM_LEADER = 'ekip_yetkilisi';
    const ROLE_MEMBER = 'üye';

    // Rol seçenekleri
    const ROLE_OPTIONS = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_TEAM_LEADER => 'Ekip Yetkilisi',
        self::ROLE_MEMBER => 'Üye'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'status',
        'avatar_color',
        'last_login_at',
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
            'last_login_at' => 'datetime',
        ];
    }

    // Rol kontrol metodları
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isTeamLeader()
    {
        return $this->role === self::ROLE_TEAM_LEADER;
    }

    public function isMember()
    {
        return $this->role === self::ROLE_MEMBER;
    }

    // Yetki kontrol metodları
    public function canManageEquipment()
    {
        return $this->isAdmin() || $this->isTeamLeader();
    }

    public function canManageFaults()
    {
        return $this->isAdmin() || $this->isTeamLeader();
    }

    public function canManageUsers()
    {
        return $this->isAdmin();
    }

    public function canManageAssignments()
    {
        return $this->isAdmin() || $this->isTeamLeader() || $this->isMember();
    }

    public function canReportFaults()
    {
        return true; // Tüm roller arıza bildirebilir
    }

    // Rol etiketini döndür
    public function getRoleLabelAttribute()
    {
        return self::ROLE_OPTIONS[$this->role] ?? 'Bilinmiyor';
    }

    // Rol seçeneklerini döndür
    public static function getRoleOptions()
    {
        return self::ROLE_OPTIONS;
    }

    // Kullanıcının zimmet işlemleri
    public function assignments()
    {
        return $this->hasMany(\App\Models\Assignment::class);
    }
}
