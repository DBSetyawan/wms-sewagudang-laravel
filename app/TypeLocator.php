<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeLocator extends Model
{
    protected $table = "tbl_type_locator";
    protected $primaryKey = "id_type_locator";
    public $timestamps = false;
}
