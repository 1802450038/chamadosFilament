<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

use function Laravel\Prompts\select;

class Call extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    use LogsActivity;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function tecs()
    {
      return $this->belongsToMany(User::class, 'user_call')->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['user_id', 'status', 'issue', 'location_id']);
    }

}
