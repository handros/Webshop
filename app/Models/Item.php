<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'made_in',
    ];

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function labels() {
        return $this->belongsToMany(Label::class)->withTimestamps();
    }

    public function auction() {
        return $this->hasOne(Auction::class);
    }
}
