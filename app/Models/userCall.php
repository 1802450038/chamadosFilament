<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userCall extends Model
{

    protected $table = 'user_call';
    protected $guarded = ['id'];

    use HasFactory;
}
