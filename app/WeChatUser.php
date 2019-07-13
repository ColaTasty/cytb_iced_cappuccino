<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 */


namespace App;


use Illuminate\Database\Eloquent\Model;

class WeChatUser extends Model
{
//    public $attributes;
    public $timestamps = false;
    protected $table = "WeChatUser";
    protected $primaryKey = "id";
}
