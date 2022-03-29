<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@welcome')
    ->name('welcome');

Route::any('feedback', 'FeedbackController@storeFeedback')
    ->name('feedback');

Route::get('documents/download/{document}', 'DocumentDownloadController@download')
    ->name('documents.download');


/**
 * ------------------------------------------------------------------------
 *  Organization (public)
 * ------------------------------------------------------------------------
 */ 
Route::resource('/organizations', 'OrganizationController')
->only(['index', 'show']);

Route::group(['prefix' => 'organizations/{organization}'], function() {
    Route::resource ('/series', 'SeriesController')
        ->only(['show'])
        ->names(['show' => 'series.show']);

    Route::resource('/rodeos', 'RodeoController')
        ->only(['show'])
        ->names(['show' => 'rodeos.show']);

    Route::get('/competitions/{competition}/results', 'ResultsController@show')
        ->name('results.show');
});




/**
 * ------------------------------------------------------------------------
 *  Auth
 * ------------------------------------------------------------------------
 */ 
Auth::routes();


/**
 * ------------------------------------------------------------------------
 *  Home
 * ------------------------------------------------------------------------
 */ 

Route::get('/home', 'HomeController@index')
->name('home');


/**
 * ------------------------------------------------------------------------
 *  User account settings   (i.e. update name, email, password, etc.)  
 * ------------------------------------------------------------------------
 */ 
Route::get('account', 'UserAccountController@index')
->name('account.index');

Route::patch('account/update/info', 'UserAccountController@updateInfo')
->name('account.update.info');

Route::patch('account/update/email', 'UserAccountController@updateEmail')
->name('account.update.email');

Route::patch('account/update/password', 'UserAccountController@updatePassword')
->name('account.update.password');


/**
 * ------------------------------------------------------------------------
 *  Toolbox home 
 * ------------------------------------------------------------------------
 */ 
Route::get('/toolbox/{organization}', 'HomeController@toolbox')
->name('toolbox');


/**
 * ------------------------------------------------------------------------
 *  Producer home - (Level 2 / Level 3)  
 * ------------------------------------------------------------------------
 */ 
Route::get('producer/organizations/{organization}/home', 'ProducerHomeController@index')
->name('producer.home');


/**
 * ------------------------------------------------------------------------
 *  Level 1 - Admin
 * ------------------------------------------------------------------------
 */  
Route::group([
    'namespace' => 'L1',    
    'prefix' => 'admin',
], function () {

    // ------------------------
    //  Admin home (dashboard)
    // ------------------------
    Route::get('/', 'HomeController@index')
    ->name('admin.home');

    // --------------------------
    //  Organizations
    // --------------------------
    Route::resource('organizations', 'OrganizationController')
    ->names([
        'index'   => 'admin.organizations.index',
        'create'  => 'admin.organizations.create',
        'store'   => 'admin.organizations.store',
        'show'    => 'admin.organizations.show',
        'edit'    => 'admin.organizations.edit',
        'update'  => 'admin.organizations.update',    
        'destroy' => 'admin.organizations.destroy',    
    ]);  

    // --------------------------
    //  Users
    // --------------------------
    Route::resource('users', 'UserController')
    ->names([
        'index'   => 'admin.users.index',
        'create'  => 'admin.users.create',
        'store'   => 'admin.users.store',
        'show'    => 'admin.users.show',
        'edit'    => 'admin.users.edit',
        'update'  => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]); 

    // --------------------------
    //  Operate as another user
    // --------------------------
    Route::get('/operate/as/user/{id}', 'UserOperatorController@start')
    ->name('admin.user.operator.start');

    Route::get('/end/operate/as/user', 'UserOperatorController@end')
    ->name('admin.user.operator.end');

    // --------------------------
    //  User levels
    // --------------------------
    Route::resource('organization.level.user', 'OrganizationUserLevelController')
    ->only([
        'index', 
        'store', 
        'destroy'
    ])
    ->names([
        'index'   => 'admin.organization.user.level.index',
        'store'   => 'admin.organization.user.level.store',
        'destroy' => 'admin.organization.user.level.destroy',  
    ]);  

    // ------------------------
    //  Contestants
    // ------------------------
    Route::resource('contestants', 'ContestantController')
    ->only([
        'index', 
        'show', 
    ])
    ->names([
        'index'   => 'admin.contestants.index',
        'show'   => 'admin.contestants.show',
    ]);

    // ------------------------
    //  Assign contestants
    // ------------------------
    Route::post('contestants/{contestant}/unassign/user/{user}', 'ContestantAssignmentController@unassign')
    ->name('admin.contestants.unassign.user');

    Route::post('contestants/{contestant}/assign/users', 'ContestantAssignmentController@assign')
    ->name('admin.contestants.assign.users');

    
    // --------------------------
    //  Draw
    // --------------------------
    Route::get('draw', 'DrawController@index')
    ->name('admin.draw.index');

    Route::get('draw/rodeo/{rodeo}/clear', 'DrawController@clear')
    ->name('admin.draw.clear');

    // --------------------------
    //  Tasks
    // --------------------------
    Route::group([], function() {

        Route::get('/tasks', 'AdminTaskController@index')
        ->name('admin.task.index');

        Route::get('/tasks/open', 'AdminTaskController@indexOpen')
        ->name('admin.task.index.open');

        Route::get('/tasks/closed', 'AdminTaskController@indexClosed')
        ->name('admin.task.index.closed');
       
        Route::get('/task/create', 'AdminTaskController@create')
        ->name('admin.task.create');

        Route::post('/task/create', 'AdminTaskController@store')
        ->name('admin.task.store');

        Route::get('/task/{id}/show', 'AdminTaskController@show')
        ->name('admin.task.show');
        
        Route::get('/task/{task}/edit', 'AdminTaskController@edit')
        ->name('admin.task.edit');

        Route::post('/task/{task}/edit', 'AdminTaskController@update')
        ->name('admin.task.update');

        Route::get('/task/delete/{id}', 'AdminTaskController@destroy')
        ->name('admin.task.delete');

        Route::get('/task/{id}/close', 'AdminTaskController@closeTask')
        ->name('admin.task.close');

        Route::get('/task/{id}/open', 'AdminTaskController@openTask')
        ->name('admin.task.open');

        Route::post('/task/comment/store', 'AdminTaskCommentController@store')
        ->name('admin.task.comment.store');

    });

    // --------------------------
    // Task settings
    // -------------------------
    Route::group([], function() {

        Route::get('/tasks/settings', 'AdminTaskSettingsController@index')
        ->name('admin.task.settings.index');

        // types
        Route::post('/tasks/settings/type/store', 'AdminTaskSettingsController@storeType')
        ->name('admin.task.type.store');

        Route::get('/tasks/settings/type/delete/{id}', 'AdminTaskSettingsController@deleteType')
        ->name('admin.task.type.delete');

        // priorities
        Route::post('/tasks/settings/priority/store', 'AdminTaskSettingsController@storePriority')
        ->name('admin.task.priority.store');

        Route::get('/tasks/settings/priority/delete/{id}', 'AdminTaskSettingsController@deletePriority')
        ->name('admin.task.priority.delete');

        // statuses
        Route::post('/tasks/settings/status/store', 'AdminTaskSettingsController@storeStatus')
        ->name('admin.task.status.store');

        Route::get('/tasks/settings/status/delete/{id}', 'AdminTaskSettingsController@deleteStatus')
        ->name('admin.task.status.delete');
    });

    /**
     * Rodeo entries
     */
    Route::get('/import/rodeo/entries/', 'ImportEntriesController@home')->name('L1.import.home');
    Route::get('/import/rodeo/{rodeo}/entries', 'ImportEntriesController@import')->name('L1.import.import');
    Route::post('/import/rodeo/{rodeo}/entries/process', 'ImportEntriesController@process')->name('L1.import.process');
    Route::get('/import/rodeo/{rodeo}/results', 'ImportEntriesController@results')->name('L1.import.results');

    // IMPORT
    Route::get('/import-new/rodeos', 'ImportController@selectRodeo')
    ->name('L1.import-new');
});


/**
 * ------------------------------------------------------------------------
 *  Level 2 
 * ------------------------------------------------------------------------
 */  
Route::group([
    'namespace' => 'L2',
    'prefix' => 'producer'
], function() {

    // ------------------------
    //  Organization 
    // ------------------------
    Route::resource('organizations', 'OrganizationController')
    ->only([ 'show', 'edit', 'update' ])
    ->names([
        'show' => 'L2.organizations.show',
        'edit' => 'L2.organizations.edit',
        'update' => 'L2.organizations.update'
    ]);   


    // ------------------------
    //  Organization users
    // ------------------------
    // Route::get('organizations/{organization}/users/home', 'UserController@home')
    // ->name('L2.users.home');


    // ------------------------
    //  Documents 
    // ------------------------
    Route::resource('organizations.documents', 'DocumentController')
    ->except(['show'])
    ->names([
        'index'   => 'L2.documents.index',
        'create'  => 'L2.documents.create',
        'store'   => 'L2.documents.store',
        'edit'    => 'L2.documents.edit',
        'update'  => 'L2.documents.update',
        'destroy' => 'L2.documents.destroy',
    ]);


    // ------------------------
    //  Events 
    // ------------------------
    Route::resource('organizations.events', 'EventController')
    ->names([
        'index'   => 'L2.events.index',
        'create'  => 'L2.events.create',
        'store'   => 'L2.events.store',
        'show'    => 'L2.events.show',
        'edit'    => 'L2.events.edit',
        'update'  => 'L2.events.update',
        'destroy' => 'L2.events.destroy',
    ]);    


    // ------------------------
    //  Groups 
    // ------------------------ 
    Route::resource('organizations.groups', 'GroupController')
    ->names([
        'index'   => 'L2.groups.index',
        'create'  => 'L2.groups.create',
        'store'   => 'L2.groups.store',
        'show'    => 'L2.groups.show',
        'edit'    => 'L2.groups.edit',
        'update'  => 'L2.groups.update',
        'destroy' => 'L2.groups.destroy',
    ]);  


    // ------------------------
    //  Build series
    // ------------------------
    Route::group([ 'prefix' => 'organization/{organization}/build-series' ], function () {

        Route::get('home', 'BuildSeriesController@home')
        ->name('L2.build.series.home');

        Route::resource('series', 'BuildSeriesController')
        ->names([
            'index'   => 'L2.build.series.index',
            'create'  => 'L2.build.series.create',
            'store'   => 'L2.build.series.store',
            'show'    => 'L2.build.series.show',
            'edit'    => 'L2.build.series.edit',
            'update'  => 'L2.build.series.update',
            'destroy' => 'L2.build.series.destroy'
        ]);

        Route::group([ 'prefix' => 'series/{series}/documents' ], function () {
            
            Route::get('add', 'BuildSeriesDocumentController@add')
            ->name('L2.build.series.documents.add');

            Route::post('attach', 'BuildSeriesDocumentController@attach')
            ->name('L2.build.series.documents.attach');

            Route::post('remove/{document}', 'BuildSeriesDocumentController@remove')
            ->name('L2.build.series.documents.remove');

        });

        Route::resource('series.rodeos', 'BuildSeriesRodeoController')
        ->except([ 'index' ])
        ->names([
            'create'  => 'L2.build.series.rodeos.create',
            'store'   => 'L2.build.series.rodeos.store',
            'show'    => 'L2.build.series.rodeos.show',
            'edit'    => 'L2.build.series.rodeos.edit',
            'update'  => 'L2.build.series.rodeos.update',
            'destroy' => 'L2.build.series.rodeos.destroy'
        ]);

        Route::get('series/{series}/rodeos/{rodeo}/order', 'BuildSeriesRodeoController@order')
        ->name('L2.build.series.rodeos.order');

        Route::post('series/{series}/rodeos/{rodeo}/order', 'BuildSeriesRodeoController@saveOrder')
        ->name('L2.build.series.rodeos.order.save');


        Route::group([ 'prefix' => 'office/fee' ], function () {
           
            Route::get('series/{series}/rodeos/{rodeo}', 'BuildSeriesRodeoOfficeFeeController@edit')
            ->name('L2.build.series.rodeo.office.fee.edit');
        
            Route::put('series/{series}/rodeos/{rodeo}', 'BuildSeriesRodeoOfficeFeeController@update')
            ->name('L2.build.series.rodeo.office.fee.update');

        });

        Route::resource('rodeos.events.groups.competitions', 'BuildSeriesCompetitionController')
        ->only([ 'create', 'store' ])
        ->names([
            'create'  => 'L2.build.series.competitions.create',
            'store'   => 'L2.build.series.competitions.store',
        ]);

        Route::resource('competitions', 'BuildSeriesCompetitionController')
        ->only([ 'edit', 'update', 'destroy' ])
        ->names([
            'edit'    => 'L2.build.series.competitions.edit',
            'update'  => 'L2.build.series.competitions.update',
            'destroy' => 'L2.build.series.competitions.destroy',
        ]);        

        Route::post('rodeos/{rodeo}/copy/events', 'BuildSeriesCompetitionController@copyEvents')
        ->name('L2.build.series.competitions.copy');
    });


    // ------------------------
    //  Contestants
    // ------------------------
    Route::resource('organizations.contestants', 'ContestantController')
    ->names([
        'index'   => 'L2.contestants.index',
        'create'  => 'L2.contestants.create',
        'store'   => 'L2.contestants.store',
        'show'    => 'L2.contestants.show',
        'edit'    => 'L2.contestants.edit',
        'update'  => 'L2.contestants.update',
        'destroy' => 'L2.contestants.destroy',
    ]);


    // ------------------------
    //  Membership
    // ------------------------
    Route::group([ 'prefix' => 'organization/{organization}/membership' ], function () { 

        Route::get('home', 'MembershipController@home')
        ->name('L2.membership.home');

        Route::resource('series.memberships', 'MembershipController')
        ->names([
            'index'   => 'L2.memberships.index',
            'create'  => 'L2.memberships.create',
            'store'   => 'L2.memberships.store',
            'show'    => 'L2.memberships.show',
            'edit'    => 'L2.memberships.edit',
            'update'  => 'L2.memberships.update',
            'destroy' => 'L2.memberships.destroy',
        ]);

    });


    // ------------------------
    //  Rodeo registration
    // ------------------------    
    Route::group([ 'prefix' => 'organizations/{organization}/registration/' ], function () {

        Route::get('rodeos', 'RegistrationController@rodeoIndex')
        ->name('L2.registration.rodeos.index');

        Route::get('rodeos/{rodeo}/contestants', 'RegistrationController@contestantIndex')
        ->name('L2.registration.contestants.index');

        Route::get('rodeos/{rodeo}/contestants/{contestant}', 'RegistrationController@show')
        ->name('L2.registration.show');

        Route::post('rodeos/{rodeo}/contestants/{contestant}', 'RegistrationController@save')
        ->name('L2.registration.save');

        Route::delete('rodeos/{rodeo}/contestants/{contestant}/delete', 'RegistrationController@destroy')
        ->name('L2.registration.destroy');

        // ---------------------------
        //  registration entries
        // ---------------------------
        Route::group([ 'prefix' => '/' ], function () {

            Route::resource('rodeos.contestants.entries', 'RegistrationEntryController')
            ->only(['index'])
            ->names([
                'index'   => 'L2.registration.entries.index',
            ]);

            Route::resource('rodeos.contestants.competitions.entries', 'RegistrationEntryController')
            ->only(['create', 'store'])
            ->names([
                'create'  => 'L2.registration.entries.create',
                'store'   => 'L2.registration.entries.store',
            ]);

            Route::resource('entries', 'RegistrationEntryController')
            ->only(['edit', 'update', 'destroy'])
            ->names([
                'edit'    => 'L2.registration.entries.edit',
                'update'  => 'L2.registration.entries.update',
                'destroy' => 'L2.registration.entries.destroy',
            ]);

        });

        // ---------------------------
        //  check in notes
        // ---------------------------        
        Route::group([ 'prefix' => '/notes' ], function () {

            Route::get('rodeos/{rodeo}/contestants/{contestant}/edit', 'RegistrationCheckInNotesController@edit')
            ->name('L2.registration.checkin.notes.edit');

            Route::put('rodeos/{rodeo}/contestants/{contestant}/update', 'RegistrationCheckInNotesController@update')
            ->name('L2.registration.checkin.notes.update');

        });

    });


    // ------------------------
    //  Entries
    // ------------------------  
    Route::group([ 'prefix' => 'organization/{organization}' ], function () { 

        Route::get('rodeos/entries', 'EntryController@home')
        ->name('L2.entries.home');

        Route::get('rodeos/{rodeo}/entries', 'EntryController@rodeo')
        ->name('L2.entries.rodeo');

        Route::resource('competitions.entries', 'EntryController')
        ->only([ 'index', 'create', 'store' ])
        ->names([
            'index'   => 'L2.entries.index',
            'create'  => 'L2.entries.create',
            'store'   => 'L2.entries.store',
        ]);

        Route::resource('entries', 'EntryController')
        ->only([ 'show', 'edit', 'update', 'destroy' ])
        ->names([
            'show'    => 'L2.entries.show',
            
            'edit'    => 'L2.entries.edit',
            'update'  => 'L2.entries.update',
            'destroy' => 'L2.entries.destroy',
        ]);
    });

    // ------------------------
    //  Team roping entries
    // ------------------------  
    Route::group([ 'prefix' => 'organization/{organization}' ], function () { 

        Route::resource('competitions.team-entries', 'TeamRopingEntryController')
        ->only([ 'index', 'create', 'store' ])
        ->names([
            'index'   => 'L2.team.entries.index',
            'create'  => 'L2.team.entries.create',
            'store'   => 'L2.team.entries.store',
        ]);

        Route::resource('team-roping/entries', 'TeamRopingEntryController')
        ->only([ 
            //'show', 'edit', 'update', 
            'destroy' 
        ])
        ->names([
            // 'show'    => 'L2.team.entries.show',
            // 'edit'    => 'L2.team.entries.edit',
            // 'update'  => 'L2.team.entries.update',
            'destroy' => 'L2.team.entries.destroy',
        ]);

    });


    // ------------------------
    //  Reports
    // ------------------------    
    Route::group([ 'prefix' => 'organizations/{organization}/reports/' ], function () {

        Route::get('/', 'ReportsController@home')
        ->name('L2.reports.home');

        Route::get('emails', 'ReportsController@emails')
        ->name('L2.reports.emails');

        Route::get('/{series}/rodeos', 'ReportsController@selectRodeo')
        ->name('L2.reports.rodeos');

        Route::group(['prefix' => 'rodeos/{rodeo}/'], function() {

            Route::get('/entries/days', 'ReportsController@selectEntriesDay')
            ->name('L2.reports.entries.days');

            Route::get('/entries', 'ReportsController@entries')
            ->name('L2.reports.entries');

            Route::get('/draw/days', 'ReportsController@selectDrawDay')
            ->name('L2.reports.draw.days');

            Route::get('/draw/days/{i}', 'ReportsController@draw')
            ->name('L2.reports.draw');

            Route::get('/judge/days/{i}', 'ReportsController@judge')
            ->name('L2.reports.judge');

            Route::get('/judge/days', 'ReportsController@selectJudgeDay')
            ->name('L2.reports.judge.days');
        });
    });

    // ------------------------
    //  Draw
    // ------------------------   
    Route::get('/organizations/{organization}/rodeos/{rodeo}/draw/home', 'DrawController@home')
    ->name('L2.draw.home');

    Route::get('/organizations/{organization}/rodeos/{rodeo}/draw/create', 'DrawController@create')
    ->name('L2.draw.create');

});


/**
 * ------------------------------------------------------------------------
 *  Level 3   (data entry)
 * ------------------------------------------------------------------------
 */  
Route::group([
    'namespace' => 'L3',
    'prefix' => 'data-entry/organizations/{organization}'
], function() {

    // ---------------------------
    //  Check-in summary
    // ---------------------------
    Route::group([ 'prefix' => 'check-in/summary'], function() {

        Route::get('rodeos/{rodeo}/not/checked/in', 'NotCheckedInController@notCheckedIn')
        ->name('L3.check-in.summary.not.checked.in');

        Route::get('rodeos/{rodeo}/not/checked/in/groups/{group}', 'NotCheckedInController@notCheckedInGroup')
        ->name('L3.check-in.summary.not.checked.in.group');

    });

    // ---------------------------
    //  Check in
    // ---------------------------
    Route::group([ 'prefix' => 'check-in'], function() {

        Route::get('home', 'CheckInController@home')
        ->name('L3.check-in.home');

        Route::get('/rodeos/{rodeo}', 'CheckInController@rodeoSummary')
        ->name('L3.check-in.rodeo');

        Route::get('/rodeos/{rodeo}/checked/in', 'CheckInController@checkedIn')
        ->name('L3.check-in.checked.in');




 
       Route::get('/rodeos/{rodeo}/contestants', 'CheckInController@rodeoContestantList')
        ->name('L3.check-in.contestants');
        
        Route::any('/rodeos/{rodeo}/add/memberships', 'CheckInController@addMemberships')
        ->name('L3.check-in.add.memberships');

        Route::any('/rodeos/{rodeo}/summary', 'CheckInController@summary')
        ->name('L3.check-in.summary');

        Route::post('/rodeos/{rodeo}/contestants/check/in', 'CheckInController@process')
        ->name('L3.check-in.process');

        Route::delete('/entry/{entry}/undo', 'CheckInController@deleteCheckIn')
        ->name('L3.check-in.destroy');

    });

    // ---------------------------
    //  Check in payment
    // ---------------------------
    Route::group([ 'prefix' => 'check-in/payment'], function() {

        Route::post('/rodeos/{rodeo}', 'CheckInPaymentController@makePayment')
        ->name('L3.check-in.payment');




    });

    // ---------------------------
    //  Results  ("work events")
    // ---------------------------
    Route::group([ 'prefix' => 'work/events/'], function() {

        Route::get('home', 'ResultsController@home')
        ->name('L3.results.home');

        Route::get('rodeos/{rodeo}', 'ResultsController@index')
        ->name('L3.results.index');

        Route::get('rodeos/{rodeo}/competition/{competition}/show', 'ResultsController@show')
        ->name('L3.results.show');

        Route::get('rodeos/{rodeo}/competition/{competition}/edit', 'ResultsController@edit')
        ->name('L3.results.edit');

        Route::patch('rodeos/{rodeo}/competition/{competition}/update', 'ResultsController@update')
        ->name('L3.results.update');
    });    

});


/**
 * ------------------------------------------------------------------------
 *  Level 4   (registered user)
 * ------------------------------------------------------------------------
 */  
Route::group([
    'namespace' => 'L4',
    'prefix' => 'organizations/{organization}'
], function() {

    // ---------------------------
    //  Contestants
    // ---------------------------
    Route::resource('contestants', 'ContestantController')
    ->except([
        'show'
    ])
    ->names([
        'index'   => 'L4.contestants.index',
        'create'  => 'L4.contestants.create',
        'store'   => 'L4.contestants.store',
        'show'    => 'L4.contestants.show',
        'edit'    => 'L4.contestants.edit',
        'update'  => 'L4.contestants.update',
        'destroy' => 'L4.contestants.destroy',
    ]);

    
    // ---------------------------
    //  Documents
    // ---------------------------
    Route::group([ 'prefix' => 'documents/' ], function () {
        // Route::get('/series', 'DocumentsController@series')
        // ->name('L4.documents.series');
    });  


    // ---------------------------
    //  Membership
    // ---------------------------
    Route::get('membership/home', 'MembershipController@home')
    ->name('L4.membership.home');

    Route::get('membership/series/{series}', 'MembershipController@details')
    ->name('L4.membership.details');

    Route::get('membership/series/{series}/contestant/{contestant}/registration', 'MembershipController@create')
    ->name('L4.membership.create');

    Route::post('membership/series/{series}/contestant/{contestant}/registration/store', 'MembershipController@store')
    ->name('L4.membership.store');

    Route::delete('membership/series/{series}/contestant/{contestant}/destroy', 'MembershipController@destroy')
    ->name('L4.membership.destroy');


    // ---------------------------
    //  Rodeo registration
    // ---------------------------
    Route::group([ 'prefix' => 'registration/' ], function () {

        Route::get('rodeos', 'RegistrationController@rodeoIndex')
        ->name('L4.registration.home');

        Route::get('rodeos/{rodeo}/contestants', 'RegistrationController@contestantIndex')
        ->name('L4.registration.contestants');

        Route::get('rodeos/{rodeo}/contestants/{contestant}/registration', 'RegistrationController@show')
        ->name('L4.registration.show');

        Route::post('rodeos/{rodeo}/contestants/{contestant}/registration/save', 'RegistrationController@save')
        ->name('L4.registration.save');

        Route::get('rodeos/{rodeo}/contestants/{contestant}/registration/confirmation', 'RegistrationController@confirmation')
        ->name('L4.registration.confirmation');

        Route::get('entered/rodeos', 'RegistrationController@entered')
        ->name('L4.registration.entered');

        // ---------------------------
        //  registration entries
        // ---------------------------
        Route::group([ 'prefix' => '/' ], function () {

            Route::resource('rodeos.contestants.entries', 'RegistrationEntryController')
            ->only(['index'])
            ->names([
                'index'   => 'L4.registration.entries.index',
            ]);

            Route::resource('rodeos.contestants.competitions.entries', 'RegistrationEntryController')
            ->only(['create', 'store'])
            ->names([
                'create'  => 'L4.registration.entries.create',
                'store'   => 'L4.registration.entries.store',
            ]);

            Route::resource('entries', 'RegistrationEntryController')
            ->only(['edit', 'update', 'destroy'])
            ->names([
                'edit'    => 'L4.registration.entries.edit',
                'update'  => 'L4.registration.entries.update',
                'destroy' => 'L4.registration.entries.destroy',
            ]);
            
            // Route::post('/addcard', function () {
            //     return view('L4.registration.add_card');
            // })->name('L4.registration.addcard');
            Route::post('rodeos/{rodeo}/contestants/{contestant}/payment', 'RegistrationEntryController@payment')
            ->name('L4.registration.entries.payment');

            // Route::post('/addcard', 'SquareController@addcard')->name('addcard');
        });

    });

    // ---------------------------
    //  Payments
    // ---------------------------
    // Route::group([ 'prefix' => 'payments' ], function () {

    //     Route::get('/', 'PaymentController@home')
    //     ->name('L4.payments.home');`

    // });

    // ---------------------------
    //  Results
    // ---------------------------
    Route::group([ 'prefix' => 'results' ], function () {

        Route::get('/', 'ResultsController@home')
        ->name('L4.results.home');

        Route::get('rodeos/{rodeo}', 'ResultsController@index')
        ->name('L4.results.index');

        Route::get('rodeos/{rodeo}/competitions/{competition}', 'ResultsController@show')
        ->name('L4.results.show');

    });

});
// ----------------add card------



/**
 * ------------------------------------------------------------------------
 *  For development only
 * ------------------------------------------------------------------------
 */  
if( 'local' == env('APP_ENV') )
{
    Route::get('dev', 'DeveloperController@dev');
    Route::get('dev/home', 'DeveloperController@devHome');
    Route::get('dev/producer/first', 'DeveloperController@devProducerFirst');
    Route::get('dev/super', 'DeveloperController@super');
    Route::get('dev/admin', 'DeveloperController@admin');
    Route::get('dev/user', 'DeveloperController@user');
}
/**
 * ------------------------------------------------------------------------
 */ 
// Route::get('addcard', function() {
//     return view('/L4/registration/add_card');
//  });

Route::post('/add-card', 'SquareController@addCard')->name('addCard');
Route::post('/add-payment-table', 'L4\PaymentController@addPaymentTable')->name('L4.addPaymentTable');


