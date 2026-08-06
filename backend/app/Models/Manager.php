<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    public $timestamps = true;
    protected $table = "manager";
    protected $primaryKey = 'id';
    protected $fillable = ["id","userName", "pwd"];

    public function getManager($userName,$pwd)
    {
        $manager = self::where("userName", $userName)->where("pwd",$pwd)->first();
        return $manager;
    }
}
