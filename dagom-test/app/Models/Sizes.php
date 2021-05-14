<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sizes extends Model
{
    use HasFactory;
    protected $fillable = [
        'size',
        'unit_measure',
        'avail_unit_measure'
    ];


    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
