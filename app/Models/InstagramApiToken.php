<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramApiToken extends Model
{
    public $table = 'instagram_api_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'instagram_business_account',
        'facebook_page_id',
        'access_token'
    ];
}
