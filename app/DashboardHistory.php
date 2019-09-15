<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DashboardHistory extends Model
{
    protected $fillable = ['title', 'info', 'color', 'profile_key'];
}
