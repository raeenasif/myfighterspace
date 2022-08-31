<?php

use App\Domains\Auth\Http\Controllers\Backend\TrainersController;

use App\Domains\Auth\Models\User;

use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.auth'.
Route::group([
    'prefix' => 'trainers',
    'as' => 'trainers.',
    'middleware' => config('boilerplate.access.middleware.confirm'),
], function () {

    //Type
    Route::group([

        'middleware' => 'role:' . config('boilerplate.access.role.admin'),
    ], function () {
        Route::get('/', [TrainersController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('trainers'), route('admin.trainers.index'));
            });

        Route::get('create', [TrainersController::class, 'create'])
            ->name('create')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.trainers.index')
                    ->push(__('Create Trainers'), route('admin.trainers.create'));
            });

        Route::post('/', [TrainersController::class, 'store'])->name('store');

        Route::group(['prefix' => '{user}'], function () {
            Route::get('edit', [TrainersController::class, 'edit'])
                ->name('edit')
                ->breadcrumbs(function (Trail $trail, User $user) {
                    $trail->parent('admin.trainers.show', $user)
                        ->push(__('Edit'), route('admin.trainers.edit', $user));
                });

            Route::patch('/', [TrainersController::class, 'update'])->name('update');
            Route::delete('/', [TrainersController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => '{user}'], function () {
            Route::get('/', [TrainersController::class, 'show'])
                ->name('show')
                ->middleware('permission:admin.access.user.list')
                ->breadcrumbs(function (Trail $trail, User $user) {
                    $trail->parent('admin.trainers.index')
                        ->push(route('admin.trainers.show', $user));
                });

            Route::patch('mark/{status}', [DeactivatedUserController::class, 'update'])
                ->name('mark')
                ->where(['status' => '[0,1]'])
                ->middleware('permission:admin.access.user.deactivate|admin.access.user.reactivate');

            Route::post('clear-session', [UserSessionController::class, 'update'])
                ->name('clear-session')
                ->middleware('permission:admin.access.user.clear-session');

            Route::get('password/change', [UserPasswordController::class, 'edit'])
                ->name('change-password')
                ->middleware('permission:admin.access.user.change-password')
                ->breadcrumbs(function (Trail $trail, User $user) {
                    $trail->parent('admin.auth.user.show', $user)
                        ->push(__('Change Password'), route('admin.auth.user.change-password', $user));
                });

            Route::patch('password/change', [UserPasswordController::class, 'update'])
                ->name('change-password.update')
                ->middleware('permission:admin.access.user.change-password');
        });
    });
});
