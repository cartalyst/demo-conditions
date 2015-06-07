<?php

Route::get('/', function()
{
	return View::make('home');
});

Route::get('regular', 'ConditionsController@regular');

Route::get('percentage', 'ConditionsController@percentage');

Route::get('inclusive', 'ConditionsController@inclusive');

Route::post('process', 'ConditionsController@process');
