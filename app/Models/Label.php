<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
    ];

    public function items() {
        return $this->belongsToMany(Item::class)->withTimestamps();
    }

    public function orders() {
        return $this->belongsToMany(Order::class)->withTimestamps();
    }
}
