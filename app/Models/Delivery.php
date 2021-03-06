<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_date',
        'delivery_recieve_date',
        'name_of_deliver_company',
    ];

    protected $cast = [
        'delivery_recieve_date' => 'h:m:i',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
