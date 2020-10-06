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

Route::get('/', 'HomeController@home');

Route::resource('animals.updates', 'UpdateController');
Route::resource('animals.documents', 'DocumentController');
Route::resource('guests.updates', 'UpdateController');
Route::resource('guests.histories', 'HistoryController');
Route::resource('shelters.updates', 'UpdateController');
Route::resource('shelters.histories', 'HistoryController');
Route::resource('owners.histories', 'HistoryController');

Route::get('animals/{id}/histories', 'HistoryController@indexanimals');

Route::get('updates/{type}', 'UpdateController@showall');
Route::get('animals/{id}/match', 'AnimalController@match');
Route::get('animals/{id}/unconnectowner', 'AnimalController@unconnectowner');
Route::get('animals/{id}/unconnectshelter', 'AnimalController@unconnectshelter');
Route::get('animals/{id}/unconnectguest', 'AnimalController@unconnectguest');
Route::get('animals/{id}/outofproject', 'AnimalController@outofproject');
Route::get('animals/{id}/matchowner/{owner_id}', 'AnimalController@matchowner');
Route::get('animals/{id}/matchshelter/{shelter_id}', 'AnimalController@matchshelter');
Route::get('animals/{id}/matchguest/{guest_id}', 'AnimalController@matchguest');
Route::get('animals/{id}/shelter', 'AnimalController@shelter');
Route::get('animals/{id}/owner', 'AnimalController@owner');
Route::get('owners/{id}/match', 'OwnerController@match');
Route::get('shelters/{id}/match', 'ShelterController@match');

Route::post('animals/{id}/outofprojectstore', 'AnimalController@outofprojectstore');

Route::resource('animals', 'AnimalController');
Route::resource('owners', 'OwnerController');
Route::resource('vets', 'VetController');
Route::resource('locations', 'LocationController');
Route::resource('shelters', 'ShelterController');
Route::resource('guests', 'GuestController');
Route::resource('tables', 'TableController');
Route::resource('tablegroups', 'TableGroupController');
