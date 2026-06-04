<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Daftarkan alias middleware SuperAdminAuth
        $middleware->alias([
            'superadmin.auth' => \App\Http\Middleware\SuperAdminAuth::class,
            'admin.auth'      => \App\Http\Middleware\AdminAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 404 → tampilkan popup halaman tidak ditemukan
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            // Deteksi apakah request berasal dari area superadmin
            if ($request->is('superadmin/*') || $request->is('superadmin')) {
                return response()->view('error.path', [], 404);
            }
            // Untuk semua halaman lain (termasuk umum)
            return response()->view('error.path', [], 404);
        });

        // 500 / ValidationException dari area superadmin → popup server error
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->is('superadmin/*') || $request->is('superadmin')) {
                // Kembalikan ke halaman sebelumnya dengan error validasi
                // (popup server ditampilkan via session flash di blade)
                return back()->withErrors($e->errors())->withInput()->with('validation_error', true);
            }
        });

        $exceptions->render(function (\Exception $e, $request) {
            if ($request->is('superadmin/*') || $request->is('superadmin')) {
                return response()->view('error.server', ['message' => $e->getMessage()], 500);
            }
        });
    })->create();
