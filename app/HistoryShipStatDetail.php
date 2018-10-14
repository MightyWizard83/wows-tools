<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryShipStatDetail extends Model
{
    protected $fillable = ['account_id','ship_id','type','last_battle_time'];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'history_ship_stat_details';
    
    public function scopeByAccountId($query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }
    
    public function scopeByShipId($query, $shipId)
    {
        return $query->where('ship_id', $shipId);
    }
    
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
    
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', '=', $date);
    }
}
