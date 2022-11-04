<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['sender_id','recipient_id','product_id','subject','message','sender_name','is_accepted'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
