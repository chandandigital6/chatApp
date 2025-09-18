<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
{
     protected $fillable = ['message_id','path','mime','size','original_name','width','height','duration'];
    public function message(){ return $this->belongsTo(Message::class); }
}
