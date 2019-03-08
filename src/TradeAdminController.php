<?php
namespace Selfreliance\TradeAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Trade\TradeManager;

class TradeAdminController extends Controller{

	public function index(TradeManager $trade){
		$calc_queue = $trade->calc_queue();
		$stat_consolidation = $trade->get_consolidation_full();
		$history_percents = $trade->history_percents();
		$avalible_percent = $trade->avalible_percent_now();
		return view('tradeadmin::index')->with(compact('calc_queue', 'stat_consolidation', 'history_percents', 'avalible_percent'));
	}

	public function store(Request $request, TradeManager $trade){
		$this->validate($request, [
			'avalible_at'         => 'required',
			'min_percent'        => ['required', function ($attribute, $value, $fail) {
				if ($value < 0.25) {
					$fail($attribute.' is invalid.');
				}
			}], 
			'max_percent'        => ['required', function ($attribute, $value, $fail) use ($request) {
				if($value < $request->input('min_percent')){
					$fail($attribute.' must be more than min_percent');
				}
			}]
	    ]);

		$trade->store_percent([
			'trading_id'  => 1,
			'min_percent' => $request->input('min_percent'),
			'max_percent' => $request->input('max_percent'),
			'avalible_at' => $request->input('avalible_at'),
		]);

		flash()->success('Процент успешно добавлен');
		return redirect()->back();
	}

	public function queue(Request $request){
		$this->validate($request, [
			'QUEUE_CNT_APP_ONE'		=> 'required|numeric|min:1|max:40',
			'QUEUE_SECONDS_DELAY'   => 'required|numeric|min:3',
		]);
		$path = $this->envPath();
        file_put_contents($path, str_replace(
            'QUEUE_CNT_APP_ONE='.env('QUEUE_CNT_APP_ONE'),
            'QUEUE_CNT_APP_ONE='.$request->input('QUEUE_CNT_APP_ONE'), file_get_contents($path)
        ));

        file_put_contents($path, str_replace(
            'QUEUE_SECONDS_DELAY='.env('QUEUE_SECONDS_DELAY'),
            'QUEUE_SECONDS_DELAY='.$request->input('QUEUE_SECONDS_DELAY'), file_get_contents($path)
        ));
        flash()->success('Новые условия сохранены');
        return redirect()->back();
	}



    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath()
    {
        if (method_exists(app(), 'environmentFilePath')) {
            return app()->environmentFilePath();
        }
        return app()->basePath('.env');
    }

}