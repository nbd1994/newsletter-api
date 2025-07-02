<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    protected $fillable = ['title', 'body', 'created_by'];

    // You can add any additional methods or relationships here if needed
}
