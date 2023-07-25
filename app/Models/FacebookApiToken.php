<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookApiToken extends Model
{
    public $table = 'facebook_api_tokens';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'user_page_id',
        'user_page_name',
        'access_token',
        'is_active'
    ];

}
