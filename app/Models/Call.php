<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Laravel\Prompts\select;

class Call extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function users()
    {
      return "teste users";
    }

    public function tecs(){
        return '';
        // return $this->belongsToMany(User::class, 'users','tec_1');
    }

    public function tec($tec)
    {
        return User::find($tec)->name;
    }
}
