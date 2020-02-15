<?php

namespace clk528\NyuReport\Controller;

use clk528\NyuReport\Models\Ill;
use clk528\NyuReport\Services\QuestionnaireEmailService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class IllController extends Controller
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

    public function rangeTime($startTime, $endTime)
    {
        $date = date("Ymd", time());
        //开始时间
        $start = strtotime($date . $startTime);
        $end = strtotime($date . $endTime);
        //当前时间
        $now = time();
        if ($now >= $start && $now <= $end) {
            return true;
        } else {
            return false;
        }
    }

    function index()
    {
        $result = [
            'submit' => false,
        ];
//        if ($this->rangeTime('00:00', '15:00')) {
//            $result['submit'] = false;
//        }

        return responseSucceed($result);
    }

    function store(Request $request)
    {
        $result = $this->getRangeIll($request);

        $data = $request->all();

        if ($data['ill'] == '2') {
            $data['cough'] = null;
        }

        if ($data['ill'] == '3') {
            $data['fever'] = null;
        }

        if ($data['ill'] == '4' || $data['ill'] == '5') {
            $data['fever'] = null;
            $data['cough'] = null;
        }

        if (empty($result)) {
            $netId = $request->user()->netId;
            if (!empty($netId)) {
                $this->questionnaireEmailService->setEmailIsRead($netId . '@nyu.edu');
            }
            $data['netId'] = $netId;
            return responseSucceed(Ill::query()->create($data));
        }

        return responseSucceed($result->fill($data)->save());
    }

    function getIll(Request $request)
    {
//        $mail = $request->user()->netId . '@nyu.edu';
//        return responseSucceed([
//            'isFill' => $this->questionnaireEmailService->isReadByEmail($mail)
//        ]);
        return responseSucceed($this->getRangeIll($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    private function getRangeIll(Request $request)
    {
        $hour = (int)date('His');//150001 --> 15:00:01
        $query = Ill::query()
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
