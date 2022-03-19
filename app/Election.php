<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = ['election_name','start_date','end_date','created_by','start_time','end_time'];
}
