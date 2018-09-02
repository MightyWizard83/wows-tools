<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['realm','account_id'];
    
    public function scopeByRealm($query, $realm)
    {
        return $query->where('realm', $realm);
    }
    
    public function scopeByAccountId($query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }
}
