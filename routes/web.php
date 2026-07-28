<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\MedicineController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('portal-hub');
Route::get('/login', function () {
    return redirect()->route('portal-hub');
})->name('login');

Route::middleware('guest')->group(function () {
    //Super Admin Login

    Route::get('/super-admin/login', function () {
        return view('super-admin.login');
    });
    Route::post('super-admin/login',[AuthController::class,'adminLogin']);

    //doctor login
    Route::get('doctor/login',function(){
        return view('doctor.login');
    })->name('doctor.login');
    Route::post('doctor/login',[AuthController::class,'doctorLogin']);
    //doctor register
    Route::post('doctor/register',[AuthController::class,'doctorRegister'])->name('doctor.register');

    //patient login
    Route::get('patient/login',function(){
        return view('patient.login');
    })->name('patient.login');
    Route::post('patient/login',[AuthController::class,'patientLogin']);

    //patient register
    Route::post('patient/register',[AuthController::class,'patientRegister'])->name('patient.register');
    });
    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');
Route::match(['get','post'],'logout',[AuthController::class,'logout'])->name('logout')->middleware('auth');

//super admin
Route::middleware(['auth', 'role:super-admin'])->prefix('super-admin')->name('super-admin.')->group(function(){
    Route::get('/dashboard',[SuperAdminController::class,'dashboard'])->name('dashboard');
    Route::get('/users',[SuperAdminController::class,'users'])->name('users');
    Route::get('/doctors',[SuperAdminController::class,'doctors'])->name('doctors');
    Route::post('/doctors/store',[SuperAdminController::class,'storeDoctor'])->name('doctors.store');
    Route::post('/doctors/{doctor}/update',[SuperAdminController::class,'updateDoctor'])->name('doctors.update');
    Route::delete('/doctors/{doctor}',[SuperAdminController::class,'deleteDoctor'])->name('doctors.destroy');
    Route::get('/patients',[SuperAdminController::class,'patients'])->name('patients');
    Route::post('/patients/store',[SuperAdminController::class,'storePatient'])->name('patients.store');
    Route::get('/patients/{patient}',[SuperAdminController::class,'deletePatient'])->name('patients.destroy');
    Route::get('/appointments',[SuperAdminController::class,'appointments'])->name('appointments');
    Route::put('/appointments/{appointment}',[SuperAdminController::class,'updateAppointment'])->name('appointments.update');
    Route::delete('/appointments/{appointment}',[SuperAdminController::class,'destroyAppointment'])->name('appointments.destroy');
    Route::get('/billing',[SuperAdminController::class,'billing'])->name('billing');
    Route::post('/billing/store',[SuperAdminController::class,'storeInvoice'])->name('billing.store');
    Route::post('/billing/{invoice}/status',[SuperAdminController::class,'updateInvoiceStatus'])->name('billing.status');
    Route::get('/billing/{invoice}',[SuperAdminController::class,'deleteInvoice'])->name('billing.destroy');
    Route::get('/laboratory',[SuperAdminController::class,'laboratory'])->name('laboratory');
    Route::post('/laboratory',[SuperAdminController::class,'storeLabReport'])->name('laboratory.store');
    Route::put('/laboratory/{labReport}',[SuperAdminController::class,'updateLabReport'])->name('laboratory.update');
    Route::delete('/laboratory/{labReport}',[SuperAdminController::class, 'destroyLabReport'])->name('laboratory.destroy');
    Route::get('/reports',[SuperAdminController::class,'reports'])->name('reports');
    Route::get('/reports/export/pdf', [SuperAdminController::class, 'exportPDF'])->name('reports.export.pdf');
    Route::get('/reports/export/excel', [SuperAdminController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/settings',[SuperAdminController::class,'settings'])->name('settings');
    Route::post('/settings',[SuperAdminController::class,'updateSettings'])->name('settings.update');
    Route::post('/settings/smtp',[SuperAdminController::class,'updateSMTP'])->name('settings.smtp');
    Route::post('/settings/security',[SuperAdminController::class,'updateSecurity'])->name('settings.security');
    Route::post('/settings/backup',[SuperAdminController::class,'generateBackup'])->name('settings.backup');
    Route::get('/backup/download/{id}',[SuperAdminController::class,'downloadBackup'])->name('settings.backup.download');
    Route::post('/backup/restore/{id}',[SuperAdminController::class,'restoreBackup'])->name('settings.backup.restore');
    Route::resource('medicines', MedicineController::class);
    });

//doctor
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function(){
    Route::get('/dashboard',[DoctorController::class,'dashboard'])->name('dashboard');
    Route::get('/appointments',[DoctorController::class,'appointments'])->name('appointments');
    Route::patch('/appointments/{appointment}/cancel',[DoctorController::class,'cancelAppointments'])->name('appointments.cancel');
    Route::post('/consultation/{appointment}',[DoctorController::class,'storeConsultation'])->name('consultation.store');
    Route::get('/patients',[DoctorController::class,'patients'])->name('patients');
    Route::get('/settings',[DoctorController::class,'settings'])->name('settings');
    Route::post('/settings/profile',[DoctorController::class,'updateProfile'])->name('settings.profile');
    Route::post('/settings/shifts',[DoctorController::class,'updateShifts'])->name('settings.shifts');
    Route::post('/settings/security',[DoctorController::class,'updateSecurity'])->name('settings.security');
    // Route::post('/consultation/store',[DoctorController::class,'storeConsultation'])->name('consultation.store');
});

//patient
// Route::middleware(['auth', 'role:patient'])->prefix('patient')->group(function(){
//     Route::get('/dashboard', fn () => view('patient.dashboard'))->name('patient.dashboard');
// });
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function(){
    Route::get('/dashboard',[PatientController::class,'dashboard'])->name('dashboard');
    Route::get('/prescription/{prescription}/print',[PatientController::class, 'printPrescription'])->name('prescription.print');
    Route::get('/appointments',[PatientController::class,'appointments'])->name('appointments');
    Route::post('appointments/book',[PatientController::class,'bookAppointments'])->name('appointments.book');

    Route::patch('/appointments/{appointment}/cancel',
            [PatientController::class, 'cancelAppointment'])
            ->name('appointments.cancel');
            
    Route::get('/billing',[PatientController::class,'billing'])->name('billing');
    Route::get('/records',[PatientController::class,'records'])->name('records');
    Route::get('/settings',[PatientController::class,'settings'])->name('settings');
    Route::post('/settings/profile',[PatientController::class,'updateProfile'])->name('settings.profile');
    Route::post('/settings/security',[PatientController::class,'updateSecurity'])->name('settings.security');
});

// Route::get('/preview-error/{type}', [SuperAdminController::class,'previewError'])
//     ->name('errors.preview');