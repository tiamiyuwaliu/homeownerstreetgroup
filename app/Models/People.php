<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;

    protected $table = "people";

    protected $fillable = [
        'title',
        'first_name',
        'initial',
        'last_name'
    ];
}
