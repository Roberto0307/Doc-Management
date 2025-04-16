<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


Route::get('/', function () {
    return redirect('/dashboard');
});

// Reset...
Route::get('clear', function () {

    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');

    return redirect('/#clear');
});


Route::get('/salir', function () {
    Auth::logout();

    // Invalidar la sesión y regenerar el token CSRF por seguridad
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/'); // o a donde quieras redirigir
})->name('salir');


// Route::get('/test-mail', function () {
//     Mail::raw('Este es un correo de prueba.', function ($message) {
//         $message->to('montesinos.quintana@gmail.com')
//                 ->subject('Correo de prueba');
//     });

//     return 'Correo enviado';
// });
