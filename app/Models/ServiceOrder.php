<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function computer(){
        return $this->belongsTo(Computer::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function tec($tec)
    {
        return User::find($tec)->name;
    }
}
