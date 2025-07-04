<?php
namespace App\Routes;

use App\Controllers\Modules\Proxy\ProxyController;
use App\Controllers\AuthController;
use App\Controllers\BlogController;
use App\Controllers\ProductController;
use App\Controllers\TestController;
use App\Controllers\VideoController;
use App\Controllers\WalletController;
use App\Controllers\WooController;
use App\Middlewares\ApiKeyMiddleware;
use App\Services\WalletService;
use Kernel\Facades\Route;

class RouteServiceProvider {
    public function boot() {
        Route::get('up', [AuthController::class, 'checkAuth'])->make();
    }
}
