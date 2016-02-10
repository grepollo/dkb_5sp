<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model {

    protected $table = 'oauth_access_tokens';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'session_id', 'expire_time'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

}
