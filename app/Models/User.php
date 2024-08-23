<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class User extends Authenticatable implements FilamentUser, HasName, HasAvatar
{
    use HasFactory, Notifiable, LogsActivity;

    protected $guarded = ['id'];

    // use UserActivityTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'status',
        'occupation',
        'admin',
        'avatar_url',
        'password',
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

    public function address()
    {
        return $this->hasMany(Address::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function printers()
    {
        return $this->hasMany(Printer::class);
    }

    public function computers()
    {
        return $this->hasMany(Computer::class);
    }

    public function serviceorders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
        return true;
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function getIdPanel(): string
    {
        return $this->id;
    }

    public function calls()
    {
        return $this->belongsToMany(Call::class, 'user_call')->withTimestamps();
    }

    public function orders()
    {
        return $this->belongsToMany(ServiceOrder::class, 'user_os')->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'id', 'admin', 'email', 'occupation', 'admin', 'avatar_url']);
    }


    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url("$this->avatar_url") : null;
    }

}
