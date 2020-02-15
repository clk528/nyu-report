<?php


namespace clk528\NyuReport\Models;


use Illuminate\Database\Eloquent\Model;

class Ill extends Model
{
    protected $fillable = [
        'netId',
        'gowuhang',
        'touch',
        'ill',
        'fever',
        'cough'
    ];
}
