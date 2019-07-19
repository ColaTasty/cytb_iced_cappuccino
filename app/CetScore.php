<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-07-19 12:46
 */


namespace App;


use Illuminate\Database\Eloquent\Model;

class CetScore extends Model
{
    protected $table = "CetScore";
    protected $dateFormat = "Y-m-d H:i:s";
    protected $fillable = ["zkz","name","school","read","write","listen","total","open_id"];

    public function UpdateScore($records)
    {
        $res = self::updateOrCreate(
            ["zkz" => $records["zkz"]],
            $records
        );

        return $res;
    }
}
