<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function database(): JsonResponse
    {
        try {
            $db = DB::connection('mongodb')->getMongoDB();
            $collections = $db->listCollections();

            $collectionNames = [];
            foreach ($collections as $c) {
                $collectionNames[] = $c->getName();
            }

            return response()->json([
                'status' => 'connected',
                'database' => $db->getDatabaseName(),
                'collections' => $collectionNames,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
