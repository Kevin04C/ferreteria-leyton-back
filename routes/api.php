<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DNIController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// usuarios
Route::get('/users', [UserController::class, 'getUsers']);
Route::post('/users', [UserController::class, 'createUser']);
Route::get('/users/total', [UserController::class, 'getTotalUser']);
Route::get('/users/{id}', [UserController::class, 'getUser']);
Route::put('/users/{id}', [UserController::class, 'updateUser']);
Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
Route::put('/users/{id}/password', [UserController::class, 'updatePassword']);

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get("/auth/renew", [AuthController::class, 'renewToken']);

// productos
Route::get("/productos", [ProductoController::class, 'obtenerProductosPaginados']);
Route::get('/producto/all-active', [ProductoController::class, 'getAllProductActive']);
Route::get('/producto/total', [ProductoController::class, 'getTotalProducts']);
Route::get('/producto/{id}', [ProductoController::class, 'obtenerProductoPorId']);
Route::post('/producto', [ProductoController::class, 'crearProducto']);
Route::post('/producto/{id}', [ProductoController::class, 'actualizarProducto']);
Route::delete('/producto/{id}', [ProductoController::class, 'eliminarProducto']);


//proveedores
Route::get("/proveedores", [ProveedorController::class, 'obtenerProveedores']);
Route::get("/proveedores/all", [ProveedorController::class, 'getAllSuppliers']);
Route::post("/proveedores", [ProveedorController::class, 'crearProveedor']);
Route::put("/proveedores/{id}", [ProveedorController::class, 'actualizarProveedor']);
Route::delete("/proveedores/{id}", [ProveedorController::class, 'eliminarProveedor']);

// categorias 
Route::get("/categorias", [CategoriaController::class, 'obtenerCategorias']);
Route::get("/categorias/all", [CategoriaController::class, 'getAllActiveCategories']);
Route::get("/categorias/{id}", [CategoriaController::class, 'obtenerCategoria']);
Route::post("/categorias", [CategoriaController::class, 'crearCategoria']);
Route::put("/categorias/{id}", [CategoriaController::class, 'actualizarCategoria']);
Route::delete("/categorias/{id}", [CategoriaController::class, 'eliminarCategoria']);
// roles
Route::get("/roles", [RolesController::class, 'getRoles']);

Route::get('/dni/search', [DNIController::class, 'searchUser']);

//sales
Route::post('/sales', [SaleController::class, 'newSale']);
Route::get('/sales', [SaleController::class, 'getSales']);
Route::get('/sales/total', [SaleController::class, 'getTotalSales']);

//reports 
Route::get('/reports/customer-purchases', [ReportsController::class, 'getCustomerPurchases']);


//cart
Route::post('/cart', [ShoppingCartController::class, 'addProductToCart']);
Route::get('/cart', [ShoppingCartController::class, 'getUserCart']);
Route::delete('/cart/{id}', [ShoppingCartController::class, 'deleteProductToCart']);

//payment
Route::get('/payment/check', [PaymentController::class, 'checkPayment']);
Route::post('/payment', [PaymentController::class, 'createPayment']);

