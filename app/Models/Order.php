<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'orderer_id',
        'description',
        'ready'
    ];

    public function orderer() {
        return $this->belongsTo(User::class, 'orderer_id');
    }

    public function labels() {
        return $this->belongsToMany(Label::class)->withTimestamps();
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function images() {
        return $this->hasMany(Image::class);
    }
}
