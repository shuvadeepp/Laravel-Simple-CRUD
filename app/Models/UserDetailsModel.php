<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserDetailsModel extends Model
{
    protected $table      = 'job_portal_db.t_user_details';
    protected $primaryKey = 'Id';

      // Define relationship with State model
    public function state() {
        return $this->belongsTo(State::class, 'intState', 'state_id');
    }
  
      // Define relationship with City model
    public function city() {
        return $this->belongsTo(City::class, 'intCity', 'city_id');
    }
}
