<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryShipStat extends Model
{
    
    protected $fillable = ['account_id','ship_id','date'];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'history_ship_stats';
    
    public function scopeByAccountId($query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }
    
    public function scopeByShipId($query, $shipId)
    {
        return $query->where('ship_id', $shipId);
    }
    
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', '>=', $date);
    }
}
