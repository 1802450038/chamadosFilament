<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userOs extends Model
{
    protected $table = 'user_os';
    protected $guarded = ['id'];

    use HasFactory;
}
