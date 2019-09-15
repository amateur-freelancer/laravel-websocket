<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Dashboard extends Model
{
    protected $fillable = ['code', 'title', 'info', 'color', 'sort_order', 'profile_key'];

    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            if (!$model->profile_key) {
                $model->profile_key = (string) Uuid::generate(4);
            }
        });
    }
}
