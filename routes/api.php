<?php

use App\Http\Controllers\Api\BookableController;
use App\Models\Bookable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('bookables',function (Request $request){
//     return Bookable::all();
// });

// Route::get('bookables/{id}', function(Request $request, $id){

//     return Bookable::findOrFail($id);
// });
Route::group(["namespace" => "App\Http\Controllers\Api"], function () {

    // Route::get('bookables', 'BookableController@index');
    // Route::get('bookables/{id}', 'BookableController@show');

    Route::apiResource('bookables', 'BookableController')->only(['index', 'show']);
    Route::get('bookables/{bookable}/availability', 'BookableAvailabilityController')
        ->name('bookable.availability.show');

    Route::get('/bookables/{bookable}/reviews', 'BookableReviewController')->name('bookable.reviews.show');

    Route::get('/booking-by-review/{reviewKey}', 'BookingByReviewController')->name('booking.by-review.show');

    Route::apiResource('reviews', 'ReviewController')->only(['show','store']);
});
