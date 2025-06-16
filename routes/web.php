<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ShiftController as AdminShiftController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Главная страница
Route::get('/', function () {
    return view('welcome');
});

// Единая страница входа (доступна всегда)
Route::get('/unified-login', function () {
    // Если пользователь уже авторизован, перенаправляем его
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::guard('staff')->check()) {
        return redirect()->route('staff.dashboard');
    }
    return view('auth.unified-login');
})->name('unified.login');

// Переадресация старых маршрутов на единую страницу
Route::get('/staff/login', function () {
    return redirect()->route('unified.login');
})->name('staff.login');

// Самостоятельная регистрация сотрудников
Route::get('/staff/join', [StaffController::class, 'showSelfRegistrationForm'])->name('staff.join');
Route::post('/staff/join', [StaffController::class, 'selfRegister'])->name('staff.self-register');

// Staff routes
Route::get('/staff/register/{token}', [StaffController::class, 'showRegistrationForm'])->name('staff.register');
Route::post('/staff/register/{token}', [StaffController::class, 'register'])->name('staff.register.post');
Route::get('/staff/register/success', [StaffController::class, 'registrationSuccess'])->name('staff.register.success');
Route::get('/staff/register/expired', [StaffController::class, 'registrationExpired'])->name('staff.register.expired');

// Staff login/logout - используем другие маршруты для сотрудников
Route::post('/staff/login', [StaffController::class, 'login'])->name('staff.login.post');
Route::post('/staff/logout', [StaffController::class, 'logout'])->name('staff.logout');

// Staff dashboard
Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard')->middleware('auth:staff');

// Staff squads - только для вожатых
Route::middleware(['auth:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/squads', [StaffController::class, 'squads'])->name('squads.index');
    Route::get('/squads/{squad}', [StaffController::class, 'squadShow'])->name('squads.show');
});

// Маршруты для альтернативных названий
Route::get('registration/expired', [StaffController::class, 'registrationExpired'])
->name('registration.expired');

// Маршруты для администратора (требуют аутентификацию)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Главная страница админки
    Route::get('/', [AdminController::class, 'dashboard'])
         ->name('dashboard');
    
    // Управление сотрудниками
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [AdminController::class, 'staffIndex'])->name('index');
        Route::get('create', [AdminController::class, 'staffCreate'])->name('create');
        Route::post('/', [AdminController::class, 'staffStore'])->name('store');
        Route::get('{staff}', [AdminController::class, 'staffShow'])->name('show');
        Route::get('{staff}/edit', [AdminController::class, 'staffEdit'])->name('edit');
        Route::put('{staff}', [AdminController::class, 'staffUpdate'])->name('update');
        Route::delete('{staff}', [AdminController::class, 'staffDestroy'])->name('destroy');
        Route::post('{staff}/regenerate-link', [AdminController::class, 'regenerateRegistrationLink'])
             ->name('regenerate-link');
    });
    
    // Управление сменами
    Route::prefix('shifts')->name('shifts.')->group(function () {
        Route::get('/', [AdminShiftController::class, 'index'])->name('index');
        Route::get('create', [AdminShiftController::class, 'create'])->name('create');
        Route::post('/', [AdminShiftController::class, 'store'])->name('store');
        Route::get('{shift}', [AdminShiftController::class, 'show'])->name('show');
        Route::get('{shift}/edit', [AdminShiftController::class, 'edit'])->name('edit');
        Route::put('{shift}', [AdminShiftController::class, 'update'])->name('update');
        Route::delete('{shift}', [AdminShiftController::class, 'destroy'])->name('destroy');
        
        // Управление отрядами в смене
        Route::prefix('{shift}/squads')->name('squads.')->group(function () {
            Route::get('/', [AdminController::class, 'squadsIndex'])->name('index');
            Route::get('create', [AdminController::class, 'squadCreate'])->name('create');
            Route::post('/', [AdminController::class, 'squadStore'])->name('store');
            Route::post('bulk-create', [AdminController::class, 'squadBulkCreate'])->name('bulk-create');
            Route::get('{squad}', [AdminController::class, 'squadShow'])->name('show');
            Route::get('{squad}/edit', [AdminController::class, 'squadEdit'])->name('edit');
            Route::put('{squad}', [AdminController::class, 'squadUpdate'])->name('update');
            Route::delete('{squad}', [AdminController::class, 'squadDestroy'])->name('destroy');
            
            // Управление детьми в отряде
            Route::post('{squad}/add-child', [AdminController::class, 'squadAddChild'])->name('add-child');
            Route::delete('{squad}/remove-child/{child}', [AdminController::class, 'squadRemoveChild'])->name('remove-child');
            
            // Управление досрочным выездом детей из отряда
            Route::get('{squad}/child/{child}/early-departure', [AdminController::class, 'childEarlyDepartureForm'])->name('child-early-departure');
            Route::post('{squad}/child/{child}/early-departure', [AdminController::class, 'childMarkEarlyDeparture'])->name('child-mark-early-departure');
            Route::delete('{squad}/child/{child}/early-departure', [AdminController::class, 'childCancelEarlyDeparture'])->name('child-cancel-early-departure');
        });
    });
    
    // Управление детьми
    Route::prefix('children')->name('children.')->group(function () {
        Route::get('/', [AdminController::class, 'childrenIndex'])->name('index');
        Route::get('create', [AdminController::class, 'childCreate'])->name('create');
        Route::post('/', [AdminController::class, 'childStore'])->name('store');
        Route::post('import-csv', [AdminController::class, 'childrenImportCsv'])->name('import-csv');
        Route::get('{child}', [AdminController::class, 'childShow'])->name('show');
        Route::get('{child}/edit', [AdminController::class, 'childEdit'])->name('edit');
        Route::put('{child}', [AdminController::class, 'childUpdate'])->name('update');
        Route::delete('{child}', [AdminController::class, 'childDestroy'])->name('destroy');
    });
});

// Breeze маршруты для профиля (только для аутентифицированных пользователей)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
