<?php

namespace clk528\NyuReport\Services;

use clk528\NyuReport\Repositories\QuestionnaireRepository;
use Illuminate\Http\Request;

class QuestionnaireService
{
    private $questionnaireRepository;

    /**
     * QuestionnaireService constructor.
     * @param $questionnaireRepository
     */
    public function __construct(QuestionnaireRepository $questionnaireRepository)
    {
        $this->questionnaireRepository = $questionnaireRepository;
    }

    public function addQuestionnaire(Request $request)
    {
        return $this->questionnaireRepository->store($request->all());
    }
}
