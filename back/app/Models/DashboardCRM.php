<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class DashboardCRM extends Model
{
    protected $collection = 'wolkvox_dashboard';
}
