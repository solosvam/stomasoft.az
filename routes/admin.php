<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\MainController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\StatisticsController;
use App\Http\Controllers\Backend\PermissionsController;
use App\Http\Controllers\Backend\AjaxController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\MessagesController;
use App\Http\Controllers\Backend\PatientController;
use App\Http\Controllers\Backend\CashierController;
use App\Http\Controllers\Backend\NotesController;
use App\Http\Controllers\Backend\ReservationController;
use App\Http\Controllers\Backend\CrmController;
use App\Http\Controllers\Backend\ServicesController;
use App\Http\Controllers\Backend\PartnersController;
use App\Http\Controllers\Backend\PrintController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\StockController;


Route::name('admin.')->group(function() {

    Route::middleware('guest')->group(function(){
        Route::get('/login',[AuthController::class,'loginpage'])->name('login');
        Route::post('/login',[AuthController::class,'login'])->name('loginpost');
    });

    Route::middleware(['auth','check.subscription'])->group(function() {
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/main', [MainController::class, 'index'])->name('main');
        Route::get('/settings', [MainController::class, 'settings'])->name('settings');
        Route::post('/settings_update', [MainController::class, 'settingsUpdate'])->name('settingsUpdate');

        Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
            Route::get('/','index')->name('index');
            Route::post('update','update')->name('update');
            Route::post('update/password','updatepassword')->name('updatepassword');
        });

        Route::controller(AjaxController::class)->prefix('ajax')->name('ajax.')->group(function() {
            Route::post('set-role-permission', 'setRolePermission')->name('set-role-permission');
            Route::post('search-customer','searchCustomer')->name('search-customer');
            Route::post('search-customer-reservation','searchCustomerForReservation')->name('search-customer-reservation');
        });

        Route::controller(UsersController::class)->middleware(['can:admin.list'])->group(function () {
            Route::get('list','index')->name('list');
            Route::get('edit/{id}','edit')->name('edit');
            Route::get('subscription/{id}','subscription')->name('subscription');
            Route::post('subscription/payment/{id}','subscriptionPayment')->name('subscriptionPayment');
            Route::post('add','create')->name('add');
            Route::post('update/{id}','update')->name('update');
        });

        Route::controller(RolesController::class)->prefix('role')->name('role.')->group(function () {
            Route::get('list','index')->name('list');
            Route::get('edit/{id}','edit')->name('edit');
            Route::get('permissions/{id}','permissions')->name('permissions');
            Route::post('add','create')->name('add');
            Route::post('update/{id}','update')->name('update');
        });

        Route::controller(PermissionsController::class)->prefix('permission')->name('permission.')->group(function () {
            Route::get('list','index')->name('list');
            Route::get('edit/{id}','edit')->name('edit');
            Route::post('add','add')->name('add');
            Route::post('update/{id}','update')->name('update');
        });

        Route::controller(ServicesController::class)->middleware(['can:services.list'])->prefix('services')->name('services.')->group(function () {
            Route::get('list','index')->name('list');
            Route::get('edit/{id}','edit')->name('edit');
            Route::post('add','add')->name('add');
            Route::post('update/{id}','update')->name('update');
        });

        Route::controller(StockController::class)->middleware(['can:stock.list'])->prefix('stock')->name('stock.')->group(function () {
            Route::get('list','index')->name('list');
            Route::get('edit/{id}','edit')->name('edit');
            Route::get('delete/{id}','delete')->name('delete');
            Route::post('add','add')->name('add');
            Route::post('update/{id}','update')->name('update');
            Route::post('update-qty','updateQty')->name('updateQty');
        });

        Route::controller(StatisticsController::class)->middleware(['can:statistics'])->prefix('statistics')->name('statistics.')->group(function () {
            Route::get('info','index')->name('info');
        });

        Route::controller(ReservationController::class)->middleware(['can:reservations.list'])->prefix('reservations')->name('reservations.')->group(function () {
            Route::get('list','index')->name('list');
            Route::post('updateStatus/{id}','updateStatus')->name('updateStatus');
            Route::get('edit/{id}','edit')->name('edit');
            Route::get('delete/{id}','delete')->name('delete');
            Route::post('add/{id}','add')->name('add');
            Route::post('update/{id}','update')->name('update');
            Route::post('create','create')->name('create');
            Route::post('hours','hours')->name('hours');
        });

        Route::controller(NotesController::class)->middleware(['can:notes.list'])->prefix('notes')->name('notes.')->group(function () {
            Route::get('list','index')->name('list');
            Route::get('edit/{id}','edit')->name('edit');
            Route::get('delete/{id}','delete')->name('delete');
            Route::post('add','add')->name('add');
            Route::post('update/{id}','update')->name('update');
        });

        Route::controller(PartnersController::class)->middleware(['can:partners.list'])->prefix('partners')->name('partners.')->group(function () {
            Route::get('list','index')->name('list');
            Route::get('edit/{id}','edit')->name('edit');
            Route::get('delete/{id}','delete')->name('delete');
            Route::get('balance/{id}','doctorBalance')->name('doctor.balance');
            Route::get('balance/{partner}/doctor/{doctor}/patient/{patient}','patientLedger')->name('doctor.patient');
            Route::post('balance/{partner}/doctor/{doctor}/patient/{patient}/pay','payPartnerPatient')->name('pay_patient');
            Route::post('add','add')->name('add');
            Route::post('update/{id}','update')->name('update');
            Route::post('purchase/{id}','purchase')->name('purchase');
            Route::get('purchase/delete/{id}','deletePurchase')->name('deletePurchase');
            Route::post('buyitem','buyitem')->name('buyitem');
        });

        Route::controller(PatientController::class)->middleware(['can:patient.list'])->prefix('patient')->name('patient.')->group(function () {
            Route::get('list','index')->name('list');
            Route::get('list-data','listData')->name('listData');
            Route::get('debtors','debtors')->name('debtors');
            Route::get('active-services','activeServices')->name('activeServices');
            Route::get('edit/{id}','edit')->name('edit');
            Route::post('add','create')->name('add');
            Route::post('update/{id}','update')->name('update');
            Route::get('{patient}/worked-teeth','workedTeeth')->name('workedTeeth');
            Route::post('files/upload/{id}','fileUpload')->name('files.upload');
            Route::post('files/delete/{id}','fileDelete')->name('files.delete');
        });

        Route::controller(MessagesController::class)->middleware(['can:messages'])->prefix('messages')->name('messages.')->group(function () {
            Route::get('list','index')->name('list');
            Route::get('view/{id}','view')->name('view');
        });

        Route::controller(CrmController::class)->middleware(['can:crm'])->prefix('crm')->name('crm.')->group(function () {
            Route::get('/','index')->name('index');
            Route::get('{id}','info')->name('info');
            Route::get('tooth-services/{id}','toothServices')->name('toothservices');
            Route::get('session/finish/{id}','finishSession')->name('finishSession');
            Route::post('addservice/{id}','addService')->name('addservice');
            Route::post('addprescription/{id}','addPrescription')->name('addprescription');
            Route::post('{id}/pay','pay')->name('pay');
            Route::get('session/{id}/edit', 'editSession')->name('session.edit');
            Route::get('session/{id}/delete', 'deleteSession')->name('session.delete');
            Route::post('session/{id}/update', 'updateSession')->name('session.update');
        });

        Route::controller(CashierController::class)->middleware(['can:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
            Route::get('/','index')->name('index');
            Route::post('expence','expence')->name('expence');
            Route::post('income','income')->name('income');
        });

        Route::controller(PrintController::class)->prefix('print')->name('print.')->group(function () {
            Route::get('service/{id}','printService')->name('service');
            Route::get('prescription/{id}','printPrescription')->name('prescription');
        });
    });
});
