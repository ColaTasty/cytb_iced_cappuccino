<?php


namespace App\CustomClasses\Abstracts;


use Illuminate\Database\Eloquent\Model;

class ModelAbstract extends Model
{
    protected $timestamps = false;
    protected $dateFormat = "Y-m-d H:i:s";
}
