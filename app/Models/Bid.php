<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'auction_id',
        'amount'
    ];

    public function auction() {
        return $this->belongsTo(Auction::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getCssClass(int $highestBid): string {
        if ($this->user_id === Auth::id() && $this->amount == $highestBid) {
            return 'table-success';
        } elseif ($this->user_id === Auth::id()) {
            return 'table-warning';
        } else {
            return 'table-danger';
        }
    }
}
