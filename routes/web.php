<?php

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

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/property_url', 'PropertyUrlController@index')->name('property_url.index');
Route::get('/property_getdata', 'PropertyUrlController@getData')->name('property_url.index.getData');
Route::post('/property_url', 'PropertyUrlController@store');
Route::post('/property_url/updatestatus', 'PropertyUrlController@updatePropertyUrlStatus')->name('property_url.update_status');
Route::post('/property_url/updateguest', 'PropertyUrlController@updatePropertyUrlNumGuest')->name('property_url.updateguest');
Route::post('/property_url/updatestaylength', 'PropertyUrlController@updatePropertyUrlStayLength')->name('property_url.updatestaylength');
Route::post('/property_url/deleteProperty', 'PropertyUrlController@deletePropertyUrlDetails')->name('property_url.deleteProperty');
Route::get('/property_url/property_url_by_city', 'PropertyUrlByCity@index')->name('property_url_by_city.index');
Route::get('/property/search_url_City', 'PropertyUrlByCity@getPropertyUrlsByCity')->name('property_url_by_city.search_url_City');
Route::post('/property/insertPropertyUrls', 'PropertyUrlByCity@savePropertyUrlDetails')->name('PropertyUrlByCity.insertPropertyUrls');

Route::get('/hotel_master', 'HotelMasterController@index')->name('hotel_master.index');
Route::get('/hotel_master_getdata', 'HotelMasterController@getData')->name('hotel_master.getData');

Route::get('/hotel_prices', 'HotelPricesController@index')->name('hotel_prices.index');
Route::get('/hotel_prices_getdata', 'HotelPricesController@getData')->name('hotel_prices.index.getData');
Route::post('/get_hotel_equipment', 'HotelPricesController@getHotelEquipment')->name('getHotelEquipment');
Route::post('/get_room_equipment', 'HotelPricesController@getRoomEquipment')->name('getRoomEquipment');

Route::get('/price_chart', 'ChartPricesController@index')->name('chart_prices.index');
Route::post('/hotel_prices/chart_prices_getChartData', 'ChartPricesController@getChartData')->name('chart_prices.getChartData');

Route::get('/room_details', 'RoomDetailsController@index')->name('room_details.index');
Route::get('/room_details_getdata', 'RoomDetailsController@getData')->name('room_details.index.getData');

Route::get('/rooms_availability', 'RoomsAvailabilityController@index')->name('rooms_availability.index');
Route::get('/rooms_availability_getdata', 'RoomsAvailabilityController@getData')->name('rooms_availability.index.getData');


Route::resource('users', 'UserController');
Route::get('/getdata', 'UserController@getData')->name('users.index.getdata');

Route::get('/config', 'HomeController@config')->name('config');
Route::post('/config', 'HomeController@configUpdate')->name('config_update');

Route::get('/get_filter_list', 'HomeController@getFilterList')->name('get_filter_list');
Route::get('/restart_parser', 'HomeController@restartParser')->name('restart_parser');
Route::get('/stop_parser', 'HomeController@stopParser')->name('stop_parser');

Route::get('/export_csv', 'HomeController@exportCSV')->name('export_csv');

Route::get('/url_stats', 'UrlStatController@index')->name('url_stats.index');
Route::get('/url_stats_getdata', 'UrlStatController@getData')->name('url_stats.index.getData');

Route::get('/logs', 'LogsController@index')->name('logs.index');
Route::get('/logs_getdata', 'LogsController@getData')->name('logs.index.getData');