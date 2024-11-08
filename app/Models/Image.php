<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path'];
    
    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }
}


