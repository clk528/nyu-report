<?php

namespace clk528\NyuReport\Command;

use clk528\NyuReport\Jobs\SendQuestionnaireNoticeMail;
use clk528\NyuReport\Services\QuestionnaireEmailService;
use Illuminate\Console\Command;

class ResetQuestionnaireEmailIsReadCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'questionnaire-notice:reset-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重置问卷邮件';

    protected $questionnaireEmailService;

    /**
     * ResetQuestionnaireEmailIsReadCommand constructor.
     * @param QuestionnaireEmailService $questionnaireEmailService
     */
    public function __construct(QuestionnaireEmailService $questionnaireEmailService)
    {
        $this->questionnaireEmailService = $questionnaireEmailService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->questionnaireEmailService->resetEmailToUnRead();
//        reset完成后发送邮件给我
        dispatch((new SendQuestionnaireNoticeMail('841506740@qq.com'))->onQueue('default'));
        //
    }
}
