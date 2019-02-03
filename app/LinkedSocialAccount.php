<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkedSocialAccount extends Model
{
    protected $table = 'linked_social_accounts';
    protected $fillable = ['provider_name', 'provider_id', 'user_id'];
 
    public function user() {
        return $this->belongsTo('App\User');
    }
}
