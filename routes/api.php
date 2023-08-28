<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/breeds', function () {
    $result = [];
    getAllBreeds('https://catfact.ninja/breeds', $result);
    return $result;

});

function getAllBreeds(string $url, array &$result): void
{
    $response = Http::get($url);

    if ($response['next_page_url'] == null) {
        return;
    }
    $data = $response->json()['data'];
    foreach ($data as $value) {
        if (!isset($result[$value["country"]])) {
            $result[$value["country"]] = [];
        }
        array_push($result[$value["country"]], $value["breed"]);
    }

    getAllBreeds($response['next_page_url'], $result);
}