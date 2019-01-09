<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
      'name',
      'dob',
      'phone_number',
      'city',
      'faculty',
      'class',
    ];
}
