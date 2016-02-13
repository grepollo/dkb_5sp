<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthCustomSession extends Model {

    protected $table = 'oauth_custom_sessions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'person_id', 'role', 'username'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

}
