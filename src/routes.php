<?php

Route::group(['prefix' => config('adminamazing.path').'/tradeadmin', 'middleware' => ['web','CheckAccess']], function() {
	Route::get('/', 'Selfreliance\TradeAdmin\TradeAdminController@index')->name('AdminTradeAdmin');
	Route::post('/store', 'Selfreliance\TradeAdmin\TradeAdminController@store')->name('AdminTradeAdminStore');
	Route::post('/queue', 'Selfreliance\TradeAdmin\TradeAdminController@queue')->name('AdminTradeAdminQueue');
});
