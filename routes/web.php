<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/test-db', function () {
    try {
        $db = DB::connection('mongodb')->getMongoDB();
        $collections = $db->listCollections();

        return response()->json([
            'status' => 'OK',
            'message' => 'MongoDB Connected Successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => $e->getMessage()
        ], 500);
    }
});
