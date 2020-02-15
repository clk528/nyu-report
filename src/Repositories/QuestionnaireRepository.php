<?php

namespace clk528\NyuReport\Repositories;

use clk528\NyuReport\Models\Questionnaire;

class QuestionnaireRepository extends Repository
{
    protected function model(): string
    {
        return Questionnaire::class;
    }
}
