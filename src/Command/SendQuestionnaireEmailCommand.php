<?php


namespace clk528\NyuReport\Command;

use clk528\NyuReport\Jobs\SendQuestionnaireNoticeMail;
use clk528\NyuReport\Jobs\SendQuestionnaireNoticeSpecifiedMail;
use clk528\NyuReport\Services\QuestionnaireEmailService;
use Illuminate\Console\Command;

class SendQuestionnaireEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'questionnaire-notice:email {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送问卷通知邮件';
    protected $questionnaireEmailService;

    /**
     * SendQuestionnaireEmailCommand constructor.
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
        $type = $this->argument('type');
        switch ($type) {
            case 'all':
//                $emails = $this->questionnaireEmailService->fetchAllEmail();
//                collect($emails)->chunk(50)->each(function ($d) {
//                    dispatch((new SendQuestionnaireNoticeMail($d->toArray()))->onQueue('default'));
//                });
                dispatch((new SendQuestionnaireNoticeMail([
                    'shanghai.staff@nyu.edu',
                    'shanghai.faculty@nyu.edu',
                    'shanghai.student@nyu.edu'
                ]))->onQueue('default'));
                //邮件发送完成后测试发送给我
                dispatch((new SendQuestionnaireNoticeMail('841506740@qq.com'))->onQueue('default'));
                break;
            case 'specified':
                $emails = $this->questionnaireEmailService->fetchUnReadEmail();
                collect($emails)->chunk(50)->each(function ($d) {
                    dispatch((new SendQuestionnaireNoticeSpecifiedMail($d->toArray()))->onQueue('default'));
                });
                //邮件发送完成后测试发送给我
                dispatch((new SendQuestionnaireNoticeMail('841506740@qq.com'))->onQueue('default'));
            default:
                break;
        }
    }
}
