<?php

use Illuminate\Support\Facades\Route;

Route::get('/{resource}/mention-items/{field}', 'App\Http\Controllers\MentionItemsController@index');