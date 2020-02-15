<?php

namespace clk528\NyuReport\Controller;

use clk528\NyuReport\Models\Virus;
use clk528\NyuReport\Services\QuestionnaireEmailService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


class VirusController extends Controller
{

    private $questionnaireEmailService;

    /**
     * IllController constructor.
     * @param $questionnaireEmailService
     */
    public function __construct(QuestionnaireEmailService $questionnaireEmailService)
    {
        $this->questionnaireEmailService = $questionnaireEmailService;
    }


    function store(Request $request)
    {
        $result = $this->getRangeVirus($request);

        $data = $request->all();

        if (empty($result)) {
            $netId = $request->user()->netId;
            if (!empty($netId)) {
//                $this->questionnaireEmailService->setEmailIsRead($netId . '@nyu.edu');
            }

            $data['netId'] = $netId;

            return responseSucceed(Virus::query()->create($data));
        }

        return responseSucceed($result->fill($data)->save());
    }

    function getVirus(Request $request)
    {
        return responseSucceed($this->getRangeVirus($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null|Virus
     */
    private function getRangeVirus(Request $request)
    {
        $hour = (int)date('His');//150001 --> 15:00:01
        $query = Virus::query()
            ->where('netId', $request->user()->netId);
        if ($hour >= 150001) { //如果超过15点00分01秒，查今天15点到明天15点区间
            $result = $query->where('created_at', '>=', date('Y-m-d 15:00:00'))
                ->where('created_at', '<=', date('Y-m-d 15:00:00', strtotime('+1 day')))
                ->first();
        } else {//如果没有超过15点00分01秒，查做天15点到今天15点区间
            $result = $query->where('created_at', '>=', date('Y-m-d 15:00:00', strtotime('-1 day')))
                ->where('created_at', '<=', date('Y-m-d 15:00:00'))
                ->first();
        }

        return $result;
    }
}
