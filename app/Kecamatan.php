<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = "tbl_kecamatan";
    protected $primaryKey = "id_kecamatan";
    public $timestamps = false;
}
