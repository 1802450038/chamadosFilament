<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser , HasName
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
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

    public static function getId(){
        return User::get('id');
    }

    public function picture(){
        return "TESTE PICTURE";
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

    public function calls(){
        return $this->belongsToMany(Call::class, 'user_call')->withTimestamps();
    }

    public function orders(){
        return $this->belongsToMany(ServiceOrder::class, 'user_os')->withTimestamps();
    }
}
