<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'description',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function bids() {
        return $this->hasMany(Bid::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function isOpen() {
        return $this->opened && $this->deadline->endOfDay() >= now();
    }

    public function isBoughtBy($userId) {
        return Auth::check()
            && (!$this->opened || $this->deadline->endOfDay() < now())
            && $this->bids()->exists()
            && $this->bids()->where('amount', $this->getHighestBid())->first()->user_id === $userId;
    }

    public function isNoBidOver() {
        return (!$this->opened || $this->deadline->endOfDay() < now()) && !$this->bids()->exists();
    }

    public function getHighestBid() {
        return $this->bids()->max('amount') ?? $this->price;
    }

    public function getMinBid() {
        return $this->getHighestBid()+500;
    }
}
