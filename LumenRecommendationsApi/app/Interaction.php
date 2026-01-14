<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_id',
        'interaction_type',
        'session_id',
    ];
}
