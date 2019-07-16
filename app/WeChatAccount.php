<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-12 23:00
 */


namespace App;


use Illuminate\Database\Eloquent\Model;

class WeChatAccount extends Model
{
    public $timestamps = false;
    protected $table = "WeChatAccount";
    protected $primaryKey = "accountId";
}
