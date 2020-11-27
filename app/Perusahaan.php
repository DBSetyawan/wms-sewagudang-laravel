<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = "tbl_perusahaan_customer";
    protected $primaryKey = "id_perusahaan";
    public $timestamps = false;
}
