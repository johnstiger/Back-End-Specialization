<?php

namespace App\Services\Status;

use Illuminate\Database\Eloquent\Model;

class OrderStatus  extends Model
{
    const CONFIRMED = 'CONFIRMED';
    const PENDING = 'PENDING';
    const CANCEL = 'CANCEL';
    const DECLINED = 'DECLINED';
}


?>
