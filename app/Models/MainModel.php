<?php

namespace App\Models;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;

class MainModel extends Model
{
    use DianujHashidsTrait;
    protected $guarded = [];
    protected $appends = ['hashid'];
}
