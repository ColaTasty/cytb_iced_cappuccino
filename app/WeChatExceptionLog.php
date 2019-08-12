<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use test\Mockery\ArgumentObjectTypeHint;

class WeChatExceptionLog extends Model
{
    protected $table = "WeChatExceptionLog";
    protected $dateFormat = "Y-m-d H:i:s";
    protected $primaryKey = "id";
    protected $fillable = ["subject","content","count"];

    public function Log($subject,$content){
        $res = new $this;
        $res->subject = $subject;
        $res->content = $content;
        $res = self::firstOrNew(
            ["subject"=>$subject,"content"=>$content],
            ["count"=>0]
        );
        $res->count = $res->count+1;
        if ($res->save()){
            return $res;
        }else{
            return null;
        }
    }
}
