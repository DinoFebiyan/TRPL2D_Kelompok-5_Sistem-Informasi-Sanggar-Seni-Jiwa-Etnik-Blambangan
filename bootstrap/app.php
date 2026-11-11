<?php

use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\SuperAdminAuth;
use Illuminate\Database\QueryException; // PERBAIKAN (TC-BRT-008): dipakai handler error database
use Illuminate\Database\SQLiteDatabaseDoesNotExistException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        // Daftarkan alias middleware SuperAdminAuth
        $middleware->alias([
            'superadmin.auth' => SuperAdminAuth::class,
            'admin.auth' => AdminAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 404 → tampilkan popup halaman tidak ditemukan
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            // Deteksi apakah request berasal dari area superadmin
            if ($request->is('superadmin/*') || $request->is('superadmin')) {
                return response()->view('error.path', [], 404);
            }

            // Untuk semua halaman lain (termasuk umum)
            return response()->view('error.path', [], 404);
        });

        // 500 / ValidationException dari area superadmin → popup server error
        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->is('superadmin/*') || $request->is('superadmin')) {
                // Kembalikan ke halaman sebelumnya dengan error validasi
                // (popup server ditampilkan via session flash di blade)
                return back()->withErrors($e->errors())->withInput()->with('validation_error', true);
            }
        });

        // PERBAIKAN (TC-BRT-008): sebelumnya error database tidak ditangani dan menampilkan halaman 500 mentah.
        // Sekarang tampil pesan ramah (view error.database, status 503) tanpa stack trace.
        // Exception tetap dicatat ke log oleh Laravel.
        $exceptions->render(function (\Throwable $e, $request) {
            // Request JSON (mis. endpoint AI) tetap ditangani handler bawaan
            if ($request->expectsJson()) {
                return null;
            }

            // Telusuri exception berantai (mis. ViewException) untuk mencari penyebab database
            for ($t = $e; $t !== null; $t = $t->getPrevious()) {
                if ($t instanceof QueryException
                    || $t instanceof \PDOException
                    || $t instanceof SQLiteDatabaseDoesNotExistException) {
                    return response()->view('error.database', [], 503);
                }
            }

            return null;
        });

        // $exceptions->render(function (\Exception $e, $request) {
        //     if ($request->is('superadmin/*') || $request->is('superadmin')) {
        //         return response()->view('error.server', ['message' => $e->getMessage()], 500);
        //     }
        // });
    })->create();