<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name','price','link','image','store'];

    public function getFormattedPriceAttribute()
    {
        $raw = (string)($this->price ?? '');
        // keep digits, dots, commas
        $num = preg_replace('/[^0-9.,]/', '', $raw) ?: '0';
        return 'MVR ' . $num;
    }
}
