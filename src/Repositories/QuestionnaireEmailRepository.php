<?php


namespace clk528\NyuReport\Repositories;

use clk528\NyuReport\Models\QuestionnaireEmail;

class QuestionnaireEmailRepository extends Repository
{
    protected function model(): string
    {
        return QuestionnaireEmail::class;
    }
}
