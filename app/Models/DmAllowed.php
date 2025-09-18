<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmAllowed extends Model
{
   
  protected $fillable = ['user_id','peer_id','granted_by'];
}
