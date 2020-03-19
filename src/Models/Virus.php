<?php


namespace clk528\NyuReport\Models;


use Illuminate\Database\Eloquent\Model;

class Virus extends Model
{
    protected $table = 'virus';

    protected $fillable = [
        'netId',

        'coronavirus',

        'touch_coronavirus',

        'go_hubei',

        'location',

        'country',

        'back_school'
    ];
}
