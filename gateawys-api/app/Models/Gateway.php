<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;

    public static $rules = [
        'serial_number' => 'required|unique:gateways',
        'ipv4_address' => 'required|unique:gateways',
    ];
}
