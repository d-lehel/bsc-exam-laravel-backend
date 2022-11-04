<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','user_name','is_active','product_name','description','amount','expiration','pickup_adress','price','longitude','latitude','image_1'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
