<?php

Route::get('/', 'Frontend\HCWelcomeController@index')->middleware('web');
