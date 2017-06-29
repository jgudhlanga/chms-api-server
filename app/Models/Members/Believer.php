<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class Believer extends Model
{
    //
    protected $fillable = [
        'member_id','createdBy','receivedJesusDate','receivedJesusArea',
        'receivedJesusConveyor','waterBaptismDate','waterBaptismArea',
        'waterBaptismConveyor','HolySpiritBaptismDate','HolySpiritBaptismArea',
        'HolySpiritBaptismConveyor','description'
    ];
    
    public function member(){
        return $this->belongsTo('App\Models\Members\Member');
    }
}
