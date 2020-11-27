<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = "tbl_kabupaten";
    protected $primaryKey = "id_kabupaten";
    public $timestamps = false;
}
