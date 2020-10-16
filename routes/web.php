<?php
//auth routes for normal user
Auth::routes(['verify' => false]);

//admin auth routes
Route::prefix('web_admin')->namespace('Auth')->group(function () {
    Route::get('/login', 'AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/logout', 'AdminLoginController@logout')->name('admin.logout');
    Route::post('/login', 'AdminLoginController@login')->name('admin.login.submit');
    Route::post('/password/reset', 'AdminResetPasswordController@reset')->name('admin.password.update');
    Route::post('/password/forget/password', 'AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('/password/reset', 'AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');                                     
    Route::get('/password/reset/{token}', 'AdminResetPasswordController@showResetForm')->name('admin.password.reset');
});

//protected admin routes
Route::prefix('web_admin')->namespace('Administrator')->middleware(['auth:admin', 'is_active'])->group(function () {
    Route::get('/', 'AdminController@dashboard')->name('admin.home');

    Route::get('/users', 'UsersController@index')->name('admin.users');
    Route::get('/users/change_status/{id}', 'UsersController@change_status')->name('admin.users.change_status');
    
    //support tickets route
    Route::get('/support-tickets/{type?}', 'TicketController@index')->name('admin.tickets');
    Route::get('/support-tickets/view/{ticket_id}', 'TicketController@view')->name('admin.tickets.view');
    Route::post('/support-tickets/update-status', 'TicketController@updateStatus')->name('admin.tickets.update_status');
    Route::post('/support-tickets/send-message', 'TicketController@send_message')->name('admin.tickets.send_message');
    Route::get('/support-tickets/delete/{ticket_id}', 'TicketController@delete')->name('admin.tickets.delete');

    //notification routes
    Route::get('/notification', 'NotificaitonController@index')->name('admin.notifications');
    Route::get('/notification/mark_all_read', 'NotificaitonController@mark_all_read')->name('admin.notifications.all_read');
    Route::get('/notification/mark_as_read/{id}', 'NotificaitonController@mark_single_notification_read')->name('admin.notifications.mark_as_read');
    Route::get('/notification/delete_all', 'NotificaitonController@delete_notifications')->name('admin.notifications.delete_all');

    //Staffs routes
    Route::get('/staffs/', 'StaffController@index')->name('admin.staffs');
    Route::get('/staffs/add', 'StaffController@add')->name('admin.staffs.add');
    Route::get('/staffs/edit/{staff_id}', 'StaffController@edit')->name('admin.staffs.edit');
    Route::post('/staffs/save', 'StaffController@save')->name('admin.staffs.save');
    Route::post('/staffs/update_password', 'StaffController@update_password')->name('admin.staffs.update_password');
    Route::get('/staffs/update-status/{staff_id}', 'StaffController@updateStatus')->name('admin.staffs.update_status');
    Route::get('/staffs/delete/{staff_id}', 'StaffController@delete')->name('admin.staffs.delete');
    
    //profile pages
    Route::get('/update-profile', 'StaffController@update_profile')->name('admin.update_profile');
    Route::post('/save-profile', 'StaffController@save_profile')->name('admin.save_profile');
    Route::post('/change-password', 'StaffController@change_password')->name('admin.change_password');

    //Categoires routes
    Route::get('/categories/', 'CategoryController@index')->name('admin.categories');
    Route::get('/categories/edit/{id}', 'CategoryController@edit')->name('admin.categories.edit');
    Route::post('/categories/save', 'CategoryController@save')->name('admin.categories.save');
    Route::get('/categories/update-status/{id}', 'CategoryController@updateStatus')->name('admin.categories.update_status');
    Route::get('/categories/delete/{id}', 'CategoryController@delete')->name('admin.categories.delete');

    //Cities routes
    Route::get('/cities/', 'CityController@index')->name('admin.cities');
    Route::get('/cities/edit/{id}', 'CityController@edit')->name('admin.cities.edit');
    Route::post('/cities/save', 'CityController@save')->name('admin.cities.save');
    Route::get('/cities/delete/{id}', 'CityController@delete')->name('admin.cities.delete');

    //Providers routes
    Route::get('/providers/', 'ProviderController@index')->name('admin.providers');
    Route::get('/providers/add', 'ProviderController@add')->name('admin.providers.add');
    Route::get('/providers/edit/{provider_id}', 'ProviderController@edit')->name('admin.providers.edit');
    Route::post('/providers/save', 'ProviderController@save')->name('admin.providers.save');
    Route::post('/providers/update_password', 'ProviderController@update_password')->name('admin.providers.update_password');
    Route::get('/providers/update-status/{provider_id}', 'ProviderController@updateStatus')->name('admin.providers.update_status');
    Route::get('/providers/delete/{provider_id}', 'ProviderController@delete')->name('admin.providers.delete');


    Route::get('/tickets/view/{ticket_no}', 'ProviderController@delete')->name('admin.tickets.view');


    //Providers routes
    Route::get('/schedules/', 'ScheduleController@index')->name('admin.schedules');
    Route::get('/schedules/add', 'ScheduleController@add')->name('admin.schedules.add');
    Route::get('/schedules/edit/{schedule_id}', 'ScheduleController@edit')->name('admin.schedules.edit');
    Route::post('/schedules/save', 'ScheduleController@save')->name('admin.schedules.save');
    Route::post('/schedules/save-edit', 'ScheduleController@save_edit')->name('admin.schedules.save_edit');
    Route::get('/schedules/delete/{schedule_id}', 'ScheduleController@delete')->name('admin.schedules.delete');
});


//frontend routes without login
Route::namespace('Frontend')->group(function () {
    Route::get('/', 'HomeController@index')->name('front.home');
    // Route::get('/privacy-policy', 'HomeController@privacy_policy')->name('front.pages.privacy_policy');
    // Route::get('/terms-and-conditions', 'HomeController@terms_conditions')->name('front.pages.terms_conditions');
    // Route::get('/about', 'HomeController@about')->name('front.pages.about');
    // Route::get('/contact', 'HomeController@contact')->name('front.pages.contact');
    // Route::post('/contact-save', 'HomeController@contact_save')->name('front.pages.contact_save');
});

//protected frontend routes also required subscription to be active
Route::namespace('Frontend')->middleware(['auth:user', 'is_active'])->group(function () {

});