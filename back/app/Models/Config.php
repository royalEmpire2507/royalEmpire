<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Config extends Model
{
    protected $collection = 'wolkvox_operation';
    protected $fillable = ['sharedOperations'];
}
