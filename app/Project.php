<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = "tbl_project";
    protected $primaryKey = "id_project";
    public $timestamps = false;
}
