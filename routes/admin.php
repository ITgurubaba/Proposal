<?php

use App\Livewire\Admin\Services\ServiceManager;
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => 'admin',
    'as' => 'admin::'
], function () {

    Route::get('/', 'DashboardController@Index')->name('dashboard');
    Route::get('logout', 'DashboardController@Logout')->name('logout');
});


Route::group([
    'namespace' => 'App\Livewire\Admin',
    'prefix' => 'admin',
    'as' => 'admin::',
    'middleware' => ['AdminLivewireLayout'],
], function () {

    Route::group([
        'middleware' => ['AdminAuthRedirect'],
        'namespace' => 'Auth',
    ], function () {

        Route::get('login', 'LoginPage')->name('login');
    });


    Route::group(['middleware' => ['AdminAuthCheck']], function () {

        Route::group([
            'namespace' => 'Dashboard',
            'as' => 'dashboard.',
            'prefix' => 'dashboard'
        ], function () {

            Route::get('/', 'MainDashboardPage')->name('main');
        });


        // User Routes
        Route::group([
            'namespace' => 'Users',
            'as' => 'users.',
            'prefix' => 'users'
        ], function () {

            Route::get('website', 'UsersListPage')->name('website');
            Route::get('website/add', 'UsersAddEditPage')->name('website.add');
            Route::get('website/edit/{user_id}', 'UsersAddEditPage')->name('website.edit');

            Route::get('team', 'TeamListPage')->name('team');
            Route::get('team/add', 'TeamAddEditPage')->name('team.add');
            Route::get('team/edit/{code}', 'TeamAddEditPage')->name('team.edit');
        });

        Route::group([
            'namespace' => 'Settings',
            'as' => 'settings.',
            'prefix' => 'settings'
        ], function () {

            Route::get('website', 'WebsiteSettingPage')->name('website');
            Route::get('general', 'GeneralSettingPage')->name('general');
            Route::get('mail', 'MailSettingPage')->name('mail');
            Route::get('server-logs', 'ServerLogPage')->name('server-logs');
            Route::get('server-info', 'ServerInfoPage')->name('server-info');
        });

        Route::group([
            'namespace' => 'Settings',
        ], function () {

            Route::get('profile', 'AdminProfilePage')->name('profile');
        });

        Route::group([
            'namespace' => 'Contact',
            'prefix' => 'contact',
            'as' => 'contact:'
        ], function () {

            Route::get('list', 'ContactFormListPage')->name('list');
        });

      
      

        Route::group([
            'namespace' => 'Services',
            'prefix' => 'services',
            'as' => 'services:'
        ], function () {

            Route::get('/', 'ServiceList')->name('list');
            Route::get('/add', 'ServiceManager')->name('add');
            Route::get('/edit/{service_id}', 'ServiceManager')->name('edit');
        });

        Route::group([
            'namespace' => 'Proposals',
            'prefix' => 'proposals',
            'as' => 'proposals:'
        ], function () {

            Route::get('/', 'ProposalListPage')->name('list');
            Route::get('/create', 'ProposalCreatePage')->name('create');
            Route::get('/view/{proposal_id}', 'ProposalViewPage')->name('view');
        });




        
        // Grouping
        Route::group([
            'namespace' => 'Grouping',
            'prefix' => 'grouping',
            'as' => 'grouping:'
        ], function () {

            Route::get('currency', 'CurrencyListPage')->name('currency');
            Route::get('countries', 'CountryListPage')->name('countries');
        });



       
        Route::group([
            'prefix' => 'ecommerce',
            'as' => 'ecommerce.',
        ], function () {

            
            Route::group([
                'namespace' => 'Ecommerce',
            ], function () {

                Route::get('taxes', 'TaxListPage')->name('tax');
                Route::get('shipping', 'ShippingListPage')->name('shipping');
                Route::get('settings', 'SettingPage')->name('settings');

             
            });
        });

        

        Route::group([
            'prefix' => 'company',
            'as' => 'company.',
        ], function () {

            Route::group([
                'namespace' => 'Clients',
            ], function () {

                Route::get('clients', 'ClientListPage')->name('clients.list');
                Route::get('clients/add', 'ClientAddEditPage')->name('clients.add');
                Route::get('clients/edit/{client_id}', 'ClientAddEditPage')->name('clients.edit');

                Route::get('category', 'ClientCategoryPage')->name('category.list');
                Route::get('attributes', 'ClientTypePage')->name('attributes.list');
            });

            
        });
        

    });
});

Route::group(['prefix' => 'admin/file-manager', 'middleware' => ['AdminAuthCheck']], function () {
    Lfm::routes();
});

Route::group([
    'namespace' => 'App\Http\Controllers\Admin\Api',
    'prefix' => 'api/admin',
    'as' => 'admin::api.',
    'middleware' => 'UserAuthCheck'
], function () {

    Route::post('upload', 'FileUploadController@UploadFile')->name('upload');
    Route::post('revert', 'FileUploadController@RevertFile')->name('revert');
});
