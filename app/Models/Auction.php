<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'description',
        'deadline',
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
