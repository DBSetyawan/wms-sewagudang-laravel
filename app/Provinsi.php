<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = "tbl_provinsi";
    protected $primaryKey = "id_provinsi";
    public $timestamps = false;
}
