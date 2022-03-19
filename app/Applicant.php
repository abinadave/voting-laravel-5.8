<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $table = "applicants";
    protected $primaryKey = "id";
    
    protected $fillable = ['name','email','phone','message','job_id'];
}
