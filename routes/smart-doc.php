<?php

Route::name('doc.')->group(function () {
    Route::get('/controller', 'RouteController@controllers')->name('route.controllers');
    Route::get('actions', 'RouteController@actionsByController')->name('route.actions');
    Route::get('/', 'RouteController@filter')->name('route.filter');
    Route::get('/file/{file}', 'RouteController@file')->name('route.file');
    Route::get('/view', 'RouteController@view')->name('route.view');
    Route::get('/markdown', 'RouteController@markdown')->name('route.markdown');
    Route::get('resources', 'RouteController@resources')->name('route.resources');

    Route::get('schema/actions', 'SchemaController@actions')->name('route.schema.actions');
});
