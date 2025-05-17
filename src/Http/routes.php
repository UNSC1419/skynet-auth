<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => '/skynetauth',
    'namespace' => 'Seat\UNSC1419\SkynetAuth\Http\Controllers'
], function () {
    Route::get('/sso')
        ->name('skynetauth::sso')
        ->uses('SkynetAuthController@getsso');
});

Route::group([
    'middleware' => ['api'],
    'prefix' => '/skynetauth/api',
    'namespace' => 'Seat\UNSC1419\SkynetAuth\Http\Controllers'
], function () {
    Route::post('/esi/update/usercharacters')
        ->uses('SkynetEsiUpdateController@usercharacters')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});