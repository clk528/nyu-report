<?php


namespace clk528\NyuReport\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireEmail extends Model
{
    protected $table = 'questionnaire_emails';

    public $timestamps = false;

    protected $fillable = [
        'email',
        'filled'
    ];
}
