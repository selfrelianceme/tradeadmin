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

}