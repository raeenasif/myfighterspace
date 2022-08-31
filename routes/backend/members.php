<?php

use App\Domains\Auth\Http\Controllers\Backend\MembersController;

use App\Domains\Auth\Models\User;

use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.auth'.
Route::group([
    'prefix' => 'members',
    'as' => 'members.',
    'middleware' => config('boilerplate.access.middleware.confirm'),
], function () {

    //Type
    Route::group([

        'middleware' => 'role:' . config('boilerplate.access.role.admin'),
    ], function () {
        Route::get('/', [MembersController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Members'), route('admin.members.index'));
            });

        Route::get('deleted', [DeletedUserController::class, 'index'])
            ->name('deleted')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.members.index')
                    ->push(__('Deleted Users'), route('admin.members.deleted'));
            });

        Route::get('create', [MembersController::class, 'create'])
            ->name('create')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.members.index')
                    ->push(__('Create Members'), route('admin.members.create'));
            });

        Route::post('/', [MembersController::class, 'store'])->name('store');

        Route::group(['prefix' => '{user}'], function () {
            Route::get('edit', [MembersController::class, 'edit'])
                ->name('edit')
                ->breadcrumbs(function (Trail $trail, User $user) {
                    $trail->parent('admin.members.show', $user)
                        ->push(__('Edit'), route('admin.members.edit', $user));
                });

            Route::patch('/', [MembersController::class, 'update'])->name('update');
            Route::delete('/', [MembersController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => '{user}'], function () {
            Route::get('/', [MembersController::class, 'show'])
                ->name('show')
                ->middleware('permission:admin.access.user.list')
                ->breadcrumbs(function (Trail $trail, User $user) {
                    $trail->parent('admin.members.index')
                        ->push($user->id, route('admin.members.show', $user));
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

        // Route::get('create', [ScanController::class, 'create'])
        //     ->name('create')
        //     ->breadcrumbs(function (Trail $trail) {
        //         $trail->parent('admin.scan.index')
        //             ->push(__('Create Scan'), route('admin.scan.create'));
        //     });

        // Route::post('/', [ScanController::class, 'store'])->name('store');

        // Route::group(['prefix' => '{type}'], function () {
        //     Route::get('edit', [ScanController::class, 'edit'])
        //         ->name('edit')
        //         ->breadcrumbs(function (Trail $trail, Scan $type) {
        //             $trail->parent('admin.scan.index')
        //                 ->push(__('Edit'), route('admin.scan.edit', $type));
        //         });

        //     Route::patch('/', [ScanController::class, 'update'])->name('update');
        //     Route::delete('/', [ScanController::class, 'destroy'])->name('destroy');
        // });
    });
});
