<?php


namespace clk528\NyuReport\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $table = 'questionnaires';

    protected $fillable = [
        'netId',
        'data'
    ];

    public function getDataAttribute()
    {
        return json_decode($this->attributes['data']);
    }

    public function setDataAttribute($val)
    {
        $this->attributes['data'] = explode(', ', json_encode($val));
    }
}
