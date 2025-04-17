<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


Route::get('/', function () {
    return redirect('/dashboard');
});


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


// Route::get('storage', function () {

//     //dd(  base_path()  );

//     $target = base_path('storage/app/public');
//     $shortcut = '/home/customer/www/doc.phoenixdev.mx/public_html/storage';

//     if (!file_exists($target)) {
//         return "❌ El directorio de destino no existe: $target";
//     }

//     // Verifica si ya existe y si es un symlink válido
//     if (is_link($shortcut)) {
//         return '⚠️ El symlink ya existe.';
//     }

//     // Si existe pero NO es symlink (es carpeta u otra cosa)
//     if (file_exists($shortcut)) {
//         return '🚫 Ya existe algo en la ruta pero no es un symlink.';
//     }

//     // Crear symlink
//     symlink($target, $shortcut);
//     return '✅ Symlink creado exitosamente.';
// });


