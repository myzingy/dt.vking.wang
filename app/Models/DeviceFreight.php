<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceFreight extends Model
{
    use SoftDeletes;

    protected $table = 'device_freight';
}
