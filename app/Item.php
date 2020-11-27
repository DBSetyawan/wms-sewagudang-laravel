<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = "tbl_item";
    protected $primaryKey = "id_item";
    public $timestamps = false;
}
