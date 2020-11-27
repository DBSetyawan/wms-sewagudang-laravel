<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locator extends Model
{
    protected $table = "tbl_locator";
    protected $primaryKey = "id_locator";
    public $timestamps = false;
}
