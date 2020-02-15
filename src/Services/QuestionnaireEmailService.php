<?php


namespace clk528\NyuReport\Services;

use clk528\NyuReport\Repositories\QuestionnaireEmailRepository;

class QuestionnaireEmailService
{
    private $questionnaireEmailRepository;

    public function __construct(QuestionnaireEmailRepository $questionnaireEMailRepository)
    {
        $this->questionnaireEmailRepository = $questionnaireEMailRepository;
    }

    public function resetEmailToUnRead()
    {
        $this->questionnaireEmailRepository->getBuilder()
            ->update(['filled' => 0]);
        return true;
    }

    public function setEmailIsRead($email)
    {
        $this->questionnaireEmailRepository->getBuilder()
            ->where('email', '=', $email)
            ->update([
                'filled' => 1
            ]);
        return true;
    }

    public function fetchUnReadEmail()
    {
        $emails = [];
        $questionnaireEmails = $this->questionnaireEmailRepository->getBuilder()
            ->where('filled', '=', 0)
            ->get();
        if ($questionnaireEmails->isNotEmpty()) {
            $questionnaireEmails->each(function ($e) use (&$emails) {
                array_push($emails, $e->email);
            });
        }
        return $emails;
    }

    public function fetchAllEmail()
    {
        $emails = [];
        $questionnaireEmails = $this->questionnaireEmailRepository->getBuilder()
            ->get();
        if ($questionnaireEmails->isNotEmpty()) {
            $questionnaireEmails->each(function ($e) use (&$emails) {
                array_push($emails, $e->email);
            });
        }
        return $emails;
    }

    public function isReadByEmail($email)
    {
        return $this->questionnaireEmailRepository->getBuilder()
            ->where('email', $email)
            ->where('filled', 1)
            ->exists();
    }
}
