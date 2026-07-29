<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\HospitalSetting;
use App\Models\User;
use App\Models\Backup;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Str;
use App\Models\LabReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SuperAdminController extends Controller
{
    public function dashboard(): View  
    {
        return view('super-admin.dashboard');
    }
    public function users()
    {
        $users = User::latest()->get();
        return view('super-admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:doctor,patient,super-admin',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'status' => $data['status'],
        ]);

        if ($user->role == 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
            ]);
        }

        if ($user->role == 'patient') {
            Patient::create([
                'user_id' => $user->id,
            ]);
        }

        return redirect()->route('super-admin.users')
            ->with('success', 'User created successfully.');
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:doctor,patient,super-admin',
            'status' => 'required|in:active,inactive',
        ]);

        $oldRole = $user->role;

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->status = $data['status'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        // Role changed to Doctor
        if ($user->role == 'doctor') {
            Doctor::firstOrCreate([
                'user_id' => $user->id,
            ]);

            Patient::where('user_id', $user->id)->delete();
        }

        // Role changed to Patient
        if ($user->role == 'patient') {
            Patient::firstOrCreate([
                'user_id' => $user->id,
            ]);

            Doctor::where('user_id', $user->id)->delete();
        }

        return redirect()->route('super-admin.users')
            ->with('success', 'User updated successfully.');
    }
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role == 'super-admin') {
            return back()->with('error', 'Super Admin cannot be deleted.');
        }

        $user->delete();

        return redirect()->route('super-admin.users')
            ->with('success', 'User deleted successfully.');
    }

    public function doctors(Request $request) : View{
        $user = User::latest()->get();
        $query = Doctor::with('user');
        if($request->filled('status')){
            $query->where('status',$request->status);
        }
        if($request->filled('search')){
            $search=$request->status;
            $query->whereHas('user',function ($q) use ($search) {
            $q->where('name','like',"%{search}%")
            ->orWhere('email','like',"%{search}%");
        })->orWhere('department','like',"%{$search}%")
        ->orWhere('license_id','like',"%{search}%");
        }
        $doctors=$query->orderBy('created_at','desc')->get();
        return view('super-admin.doctors',compact('user','doctors'));
    }
    public function storeDoctor(Request $request){
        $data=$request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:6',
            'department'=>'required|string|max:255',
            'license_id'=>'required|string|max:255|unique:doctors,license_id',
            'bio'=>'nullable|string',
            'profile_photo'=>'nullable|image',
            'consultation_fee'=>'nullable|numeric|min:0'
        ]);
        $photoPath = null;
        if($request->hasFile('profile_photo')){
            $file=$request->file('profile_photo');
            $filename=time().'-'.$file->getClientOriginalExtension();
            $file->move(public_path('doctors/profile'),$filename);
            $photoPath=$filename;
        }
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'role'=>'doctor',
            'status'=>'active'
        ]);
        Doctor::create([
            'user_id'=>$user->id,
            'department'=>$data['department'],
            'license_id'=>$data['license_id'],
            'bio'=>$data['bio'] ?? null,
            'profile_photo'=>$photoPath,
            'consultation_fee'=>$data['consultation_fee'] ?? null,
        ]);
        return redirect()->route('super-admin.doctors')->with('success','Doctor added successfully!');
    }
    public function updateDoctor(Request $request ,Doctor $doctor){
       $data=$request->validate([
          'name'=>'required|string|max:255',
          'email'=>'required|email|unique:users,email,'.$doctor->user_id,
          'password'=>'required|string|min:6',
          'department'=>'required|string|max:255',
          'license_id'=>'required|string|max:255|unique:doctors,license_id,'.$doctor->id,
          'bio'=>'nullable|string',
          'profile_photo'=>'nullable|image',
          'consultation_fee'=>'nullable|numeric|min:0',
        ]);
        $photoPath = $doctor->profile_photo;
        if($request->hasFile('profile_photo')){
        if($doctor->profile_photo && file_exists(public_path('doctor/profile/'.$doctor->profile_photo))){
            unlink(public_path('doctors/profile/'.$doctor->profile_photo));
        }
        $file =$request->file('profile_photo');
        $filename=time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('doctors/profile'),$filename);
        $photoPath =$filename;
       }
       $userData=[
        'name'=>$data['name'],
        'email'=>$data['email'],
        'password'=>Hash::make($data['password']),
       ];
       $doctor->user->update($userData);

       $doctor->update([
        'department'=>$data['department'],
        'license_id'=>$data['license_id'],
        'bio'=>$data['bio'] ?? null ,
        'consultation_fee' => $data['consultation_fee'],
        'profile_photo'=>$photoPath,
       ]);
       return redirect ()->route('super-admin.doctors')->with('success','Doctor Updated successfully');
    }
    public function deleteDoctor(Doctor $doctor){
        if($doctor->profile_photo && file_exists(public_path('doctors/profile/'.$doctor->profile_photo))){
            unlink(public_path('doctors/profile/'.$doctor->profile_photo));
        }
        $user=$doctor->user;
        $doctor->delete();
        if($user){
            $user->delete();
        }
        return redirect()->route('super-admin.doctors')->with('success','Doctor Deleted Successfully!');
    }

    // public function storeUser(Request $request){
    //     $data = $request->validate([
    //         'name'=> 'required',
    //         'email'=>'required|email|unique:users,email',
    //         'role'=>'required',
    //         'password'=>'required'
    //     ]); 
    //     $user = User::create([
    //         'name'=>$data['name'],
    //         'email'=>$data['email'],
    //         'role'=>$data['role'],
    //         'password'=>Hash::make($data['password']),
    //     ]);
    //     if($user->role==='doctor'){
    //         $user->doctor()->create([
    //         ]);
    //     }
    // }

    public function patients(Request $request) : View{
        $query = Patient::with(['user','medicalRecords','labDocuments','appointments.doctor.user']);
        if($request->filled('search')){
            $search=$request->search;
            $query->where(function ($q) use ($search) {
            $q->whereHas('user',function ($uQuery) use ($search) {
                $uQuery->where('name','like',"%{$search}%")
                 ->orWhere('email','like',"%{search}%");
            })
            ->orWhere('number','like',"%{$search}%")
            ->orWhere('disease','like',"%{search}%")
            ->orWhere('id','like',"%{search}%");
        });
        }
        if($request->filled('blood_group')){
            $query->where('blood_group',$request->blood_group);
        }
        $patients = $query->orderBy('created_at','desc')->get();
        return view('super-admin.patients',compact('patients'));
    }
    public function storePatient(Request $request){
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'age' => 'required|string|max:50',
            'gender' => 'required|string|max:50',
            'number' => 'required|string|max:20|unique:patients,number',
            'blood_group' => 'required|string|max:10',
            'address' => 'nullable|string|max:500',
            'disease' => 'nullable|string|max:255',
            'profile' => 'nullable|image',
        ]);
        $photoPath = null;
        if($request->hasFile('profile')){
            $file = $request->file('profile');
            $filename = time() . '.' .$file->getClientOriginalExtension();
            $file->move(public_path('patients'),$filename);
            $photoPath = $filename;
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role'=>'patient',
            'status'=>'active'
        ]);
        Patient::create([
            'user_id'=>$user->id,
            'number'=>$data['number'],
            'age'=>$data['age'],
            'gender'=>$data['gender'],
            'blood_group'=>$data['blood_group'],
            'disease'=>$data['disease'] ?? null,
            'address'=>$data['address'] ?? null,
            'profile'=>$photoPath,
        ]);
        return redirect()->route('super-admin.patients')->with('success','Patient Add Successfully!');
    }
    public function updatePatient(Request $request, $id){
        $patient = Patient::findOrFail($id);
        $user = User::findOrFail($patient->user_id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'age' => 'required|string|max:50',
            'gender' => 'required|string|max:50',
            'number' => 'required|string|max:20|unique:patients,number,' . $patient->id,
            'blood_group' => 'required|string|max:10',
            'address' => 'nullable|string|max:500',
            'disease' => 'nullable|string|max:255',
            'profile' => 'nullable|image',
        ]);

        // Photo Upload
        if ($request->hasFile('profile')) {

            // Delete old photo
            if ($patient->profile && file_exists(public_path('patients/' . $patient->profile))) {
                unlink(public_path('patients/' . $patient->profile));
            }

            $file = $request->file('profile');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('patients'), $filename);

            $patient->profile = $filename;
        }

        // Update User
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        // Update Patient
        $patient->age = $data['age'];
        $patient->gender = $data['gender'];
        $patient->number = $data['number'];
        $patient->blood_group = $data['blood_group'];
        $patient->disease = $data['disease'] ?? null;
        $patient->address = $data['address'] ?? null;

        $patient->save();

        return redirect()->route('super-admin.patients')
            ->with('success', 'Patient Updated Successfully!');
    }
    public function deletePatient(Patient $patient){
        if($patient->profile && file_exists(public_path('patients/'.$patient->profile))){
            unlink(public_path('patients/'.$patient->profile));
        }
        $user = $patient->user;
        $patient->delete();
        if($user){
            $user->delete();
        }
        return redirect()->route('super-admin.patients')->with('success','Patient Deleted Successfully!');
    }
    public function appointments() : View{
        $patients = Patient::with('user')->get();

        $doctors = Doctor::with('user')->get();

        $query = Appointment::with([
                'patient.user',
                'doctor.user'
        ]);
        // Patient Filter
        if(request()->filled('patient')){
            $query->where('patient_id', request('patient'));
        }

        // Doctor Filter
        if(request()->filled('doctor')){
            $query->where('doctor_id', request('doctor'));
        }

        // Status Filter
        if(request()->filled('status')){
            $query->where('status', request('status'));
        }

        // Date Filter
        if(request()->filled('date')){
            $query->whereDate('appointment_date', request('date'));
        }

        $appointments = $query->latest()->get();
         // Calendar
        $month = request('month', now()->month);
        $year = request('year', now()->year);
        $firstDayOfMonth = Carbon::create($year, $month, 1);

        $previousMonth = $firstDayOfMonth->copy()->subMonth();
        $nextMonth     = $firstDayOfMonth->copy()->addMonth();

        $daysInMonth = $firstDayOfMonth->daysInMonth;

        $startDay = $firstDayOfMonth->dayOfWeek;

        // Date wise appointment count
        $calendarAppointments = Appointment::selectRaw(
            'DATE(appointment_date) as appointment_date,
            COUNT(*) as total'
        )
        ->groupBy('appointment_date')
        ->pluck('total','appointment_date');    
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();

        $pendingAppointments = Appointment::where('status','Pending')->count();

        $completedAppointments = Appointment::where('status','Completed')->count();

        $cancelledAppointments = Appointment::where('status','Cancelled')->count();
        return view('super-admin.appointments', compact(
            'patients',
            'doctors',
            'appointments',
            'firstDayOfMonth',
            'daysInMonth',
            'startDay',
            'calendarAppointments',
            'previousMonth',
            'nextMonth',
            'todayAppointments',
            'pendingAppointments',
            'completedAppointments',
            'cancelledAppointments'
        ));
    }
    public function updateAppointment(Request $request, Appointment $appointment)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required',
            'status' => 'required',
            'symptoms' => 'required'
        ]);

        $appointment->update($request->all());

        return back()->with('success','Appointment Updated Successfully');
    }
    public function destroyAppointment(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()
                ->back()
                ->with('success', 'Appointment deleted successfully.');
    }
    public function billing(Request $request) : View{
        $query = Invoice::with(['patient.user','items']);
        if($request->filled('status')){
            $query->where('status',$request->status);
        }
        if($request->filled('search')){
            $search=$request->status;
            $query->where('invoice_number','like',"%{search}%")
            ->orWhere('title','like',"%{search}%")
            ->orWhereHas('patient.user',function ($pq) use ($search) {
                $pq->where('name','like',"%{search}%");
            });
        }
        $invoices = $query->orderBy('created_at','desc')->get();
        $patients=Patient::with('user')->get();
        $totalRevenue = Invoice::where('status','paid')->sum('total_amount');
        $paidCount = Invoice::where('status','paid')->count();
        $unpaidCount = Invoice::where('status','unpaid')->count();
        $totalInvoicesCount = Invoice::count();
        return view('super-admin.billing',compact(
            'invoices',
            'patients',
            'totalRevenue',
            'paidCount',
            'unpaidCount',
            'totalInvoicesCount'
        ));
    }
    public function storeInvoice(Request $request){
        $data = $request->validate([
            'patient_id'=>'required|exists:patients,id',
            'title' => 'required|string|max:255',
            'billing_date'=>'required|date',
            'due_date'=>'required|date|after_or_equal:billing_date',
            'payment_method'=>'nullable|string|max:100',
            'status'=>'required|in:paid,unpaid,cancelled',
            'discount'=>'nullable|numeric|min:0',
            'gst'=>'nullable|numeric|min:0',
            'items'=>'required|array|min:1',
            'items.*description'=>'required|string|max:255',
            'items.*rate'=>'required|numeric|min:0',
            'items.*qty'=>'required|integer|min:1'
        ]);

        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $subtotal += ($item['qty'] * $item['rate']);
        }
        $discount = $data['discount'] ?? 0;
        $gst = $data['gst'] ?? 0;
        $totalAmount = max(0,$subtotal-$discount+$gst);

        $invoiceNumber = 'INV-' .date('Ymd') . '-' .strtoupper(Str::random(4));
        $invoice = Invoice::create([
            'patient_id'=>$data['patient_id'],
            'invoice_number'=>$invoiceNumber,
            'billing_date'=>$data['billing_date'],
            'due_date'=>$data['due_date'],
            'subtotal'=>$subtotal,
            'discount'=>$discount,
            'gst'=>$gst,
            'total_amount'=>$totalAmount,
            'status'=>$data['status'],
            'payment_method'=>$data['payment_method'] ?? 'Pending',
        ]);
        foreach ($data['items'] as $item) {
            InvoiceItem::create([
                'invoice_id'=>$invoice->id,
                'description'=>$item['description'],
                'rate'=>$item['rate'],
                'qty'=>$item['qty'],
                'total'=>($item['qty'] * $item['rate']),
            ]);
        }
        return redirect()->route('super-admin.billing')
        ->with('success','Invoice Generated Successfully!');
    }
    public function updateInvoiceStatus(Request $request,Invoice $invoice){
        $data=$request->validate([
            'status'=>'required|in:paid,unpaid,cancelled',
            'payment_method'=>'nullable|string|max:100',
        ]);
        $invoice->update([
            'status'=>$data['status'],
            'payment_method'=>$data['payment_method'] ?? $invoice->payment_method,
        ]);
        return redirect()->route('super-admin.billing')->with('success','Invoice Status Updated Successfully!');
    }
    public function deleteInvoice(Invoice $invoice){
        $invoice->delete();
        return redirect()->with('success','Invoice Deleted Successfully!');
    }
    public function laboratory(): View
    {
        $patients = Patient::with('user')->get();

        $doctors = Doctor::with('user')->get();

        $reports = LabReport::with([
            'patient.user',
            'doctor.user'
        ])
        ->latest()
        ->get();

        return view(
            'super-admin.laboratory',
            compact(
                'patients',
                'doctors',
                'reports'
            )
        );
    }
    public function storeLabReport(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'report_name' => 'required|string|max:255',
            'report_type' => 'required|string|max:255',
            'status' => 'required',
            'file' => 'required|mimes:pdf|max:5120',
        ]);

        $file = $request->file('file');

        $filename = time().'_'.$file->getClientOriginalName();

        $file->move(public_path('uploads/lab_reports'), $filename);

        LabReport::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'report_name' => $request->report_name,
            'report_type' => $request->report_type,
            'file' => $filename,
            // 'file_size' => round($file->getSize()/1024,2).' KB',
            'status' => $request->status,
        ]);

        return back()->with(
            'success',
            'Lab Report Uploaded Successfully.'
        );
    }
    public function updateLabReport(Request $request, LabReport $labReport)
    {
        $request->validate([

            'patient_id'  => 'required|exists:patients,id',

            'doctor_id'   => 'nullable|exists:doctors,id',

            'report_name' => 'required',

            'report_type' => 'required',

            'status'      => 'required',

            'file'        => 'nullable|mimes:pdf|max:5120',

        ]);

        $data = [

            'patient_id'  => $request->patient_id,

            'doctor_id'   => $request->doctor_id,

            'report_name' => $request->report_name,

            'report_type' => $request->report_type,

            'status'      => $request->status,

        ];

        if($request->hasFile('file')){

            if(File::exists(public_path('uploads/lab_reports/'.$labReport->file))){

                File::delete(public_path('uploads/lab_reports/'.$labReport->file));

            }

            $file = $request->file('file');

            $filename = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/lab_reports'),$filename);

            $data['file']=$filename;

            $data['file_size']=round(
                filesize(public_path('uploads/lab_reports/'.$filename))/1024,
                2
            ).' KB';

        }

        $labReport->update($data);

        return back()->with(
            'success',
            'Lab Report Updated Successfully.'
        );
    }
    public function destroyLabReport(LabReport $labReport)
    {
        $path = public_path('uploads/lab_reports/'.$labReport->file);

        if (File::exists($path)) {
            File::delete($path);
        }

        $labReport->delete();

        return back()->with(
            'success',
            'Lab Report deleted successfully.'
        );
    }
    public function reports() : View{
    $totalPatients = Patient::count();

    $totalDoctors = Doctor::count();

    $totalAppointments = Appointment::count();

    $totalRevenue = Payment::sum('amount');

    $appointments = Appointment::with(['patient.user','doctor.user'])
                        ->latest()
                        ->take(10)
                        ->get();
    $lowStocks = Medicine::whereColumn('stock','<=','minimum_stock')
                    ->orderBy('stock')
                    ->take(5)
                    ->get();
    return view('super-admin.reports', compact(
        'totalPatients',
        'totalDoctors',
        'totalAppointments',
        'totalRevenue',
        'appointments',
        'lowStocks'
    ));
    }
    public function exportPDF()
    {
        $patients = Patient::count();
        $doctors = Doctor::count();
        $appointments = Appointment::count();
        $revenue = Payment::sum('total_amount');

        $pdf = Pdf::loadView('super-admin.reports.pdf', compact(
            'patients',
            'doctors',
            'appointments',
            'revenue'
        ));

        return $pdf->download('Hospital_Report.pdf');
    }
    public function exportExcel()
    {
        return Excel::download(new ReportExport, 'Hospital_Report.xlsx');
    }
    public function settings() : View{
        $setting = HospitalSetting::first();
        $backups = Backup::latest()->get();
        return view('super-admin.settings', compact('setting','backups'));
    }
    public function updateSettings(Request $request)
    {
        // dd($request->hasFile('logo'));
        $request->validate([
            'hospital_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg',
        ]);

        $data = [
            'hospital_name' => $request->hospital_name,
            'phone'         => $request->phone,
            'address'       => $request->address,
        ];

        // Logo Upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/settings'), $logoName);
            $data['logo'] = $logoName;
            // dd($logoName, file_exists(public_path('uploads/settings/'.$logoName)));
        }


        // Favicon Upload
        if ($request->hasFile('favicon')) {
            $icon = $request->file('favicon');
            $faviconName = time().'_'.$icon->getClientOriginalName();
            $icon->move(public_path('uploads/settings'), $faviconName);
            $data['favicon'] = $faviconName;
        }
        // HospitalSetting::updateOrCreate(
        //     [
        //         'id' => 1
        //     ],
        //     [
        //         'hospital_name' => $request->hospital_name,
        //         'phone' => $request->phone,
        //         'address' => $request->address,
        //         'logo' => $logoName ?? null,
        //         'favicon' => $faviconName ?? null,
        //     ]
        // );   
        HospitalSetting::updateOrCreate(
            ['id' => 1],
            $data
        );
        // dd($setting->toArray());

        return back()->with('success', 'Hospital settings updated successfully.');
    }
    public function updateSMTP(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'mail_mailer'      => 'required|string|max:50',
            'mail_host'        => 'required|string|max:255',
            'mail_port'        => 'required|numeric',
            'mail_encryption'  => 'required|string|max:20',
            'mail_username'    => 'required|string|max:255'
        ]);

        HospitalSetting::updateOrCreate(
            ['id' => 1],
            [
                'mail_mailer'       => $request->mail_mailer,
                'mail_host'         => $request->mail_host,
                'mail_port'         => $request->mail_port,
                'mail_encryption'   => $request->mail_encryption,
                'mail_username'     => $request->mail_username
            ]
        );
        // dd($setting->toArray());
        return back()->with('success','SMTP Settings Updated Successfully.');
    }
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'current_password' => 'required',
            'password' => 'required|confirmed',
            ]);
            // dd('1');

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {

            return back()->with('error','Current password is incorrect.');
        }

        $user->update([

            'email' => $request->email,

            'password' => Hash::make($request->password),

        ]);
        // dd('Reached Here');
        return back()->with('success','Profile updated successfully.');
    }
    public function generateBackup()
    {
        $database = env('DB_DATABASE');

        $filename = 'backup_'.date('Y-m-d_H-i-s').'.sql';

        $path = public_path('backups');

        if(!File::exists($path)){
            File::makeDirectory($path,0755,true);
        }

        $filepath = $path.'/'.$filename;

        $command = "mysqldump -u".env('DB_USERNAME').
                " -p".env('DB_PASSWORD').
                " ".$database.
                " > ".$filepath;

        exec($command);

        Backup::create([
            'file_name'=>$filename,
            'file_path'=>$filepath,
            'file_size'=>round(filesize($filepath)/1024,2).' KB',
            'status'=>'Completed'
        ]);

        return back()->with('success','Backup Generated Successfully');
    }
    public function downloadBackup($id)
    {
        $backup = Backup::findOrFail($id);

        return response()->download($backup->file_path);
    }
    public function restoreBackup($id)
    {
        return back()->with(
            'info',
            'Restore functionality will be configured.'
        );
    }
    // public function previewError($type)
    // {
    //     switch ($type) {

    //         case 403:
    //             return response()->view('errors.403', [], 403);

    //         case 404:
    //             return response()->view('errors.404', [], 404);

    //         case 500:
    //             return response()->view('errors.500', [], 500);

    //         case 'maintenance':
    //             return view('errors.maintenance');

    //         default:
    //             abort(404);
    //     }
    // }
}