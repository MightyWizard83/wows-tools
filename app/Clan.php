<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    
    protected $fillable = ['id', 'wg_created_at', 'members_count', 'name', 'tag'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clans';
    
    public function scopeById($query, $id)
    {
        return $query->where('id', $id);
    }
    
}
