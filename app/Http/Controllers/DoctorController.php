<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\LabDocuments;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        $today = now()->toDateString();

        $totalConsultationCount = Appointment::where('doctor_id',$doctor->id)
        ->whereDate('appointment_date',$today)
        ->count();

        $todayCompletedCount = Appointment::where('doctor_id',$doctor->id)
        ->whereDate('appointment_date',$today)
        ->where('status','Completed')
        ->count();
        
        $totalPatientsCount = Appointment::where('doctor_id',$doctor->id)
        ->distinct('patient_id')
        ->count('patient_id');

        $pendingDiagnosesCount = Appointment::where('doctor_id',$doctor->id)
        ->where('status','Pending')
        ->count();

        $emergencyCallsCount = Appointment::where('doctor_id',$doctor->id)
        ->where('consultation_type','Emergency')
        ->where('status','Pending')
        ->count();
        $queueAppointments = Appointment::with('patient.user')
        ->where('doctor_id',$doctor->id)
        ->whereDate('appointment_date',$today)
        ->whereIn('status', ['Pending', 'Checked In'])
        ->orderBy('token_number')
        ->get();
        // ->orderBy('token_number','asc')
        // ->get();

        $patientIds = Appointment::where('doctor_id',$doctor->id)
        ->distinct()->pluck('patient_id');

        $notifications = LabDocuments::with('patient.user')
        ->where('patient_id',$doctor->id)
        ->orderBy('created_at','desc')
        ->take(5)
        ->get();

        return view('doctor.dashboard', compact(
            'user',
            'doctor',
            'totalConsultationCount',
            'todayCompletedCount',
            'totalPatientsCount',
            'pendingDiagnosesCount',
            'emergencyCallsCount',
            'queueAppointments',
            'patientIds',
            'notifications',
        ));
    }
    public function appointments(): View
    {
        $user = Auth::user();
        // $doctor = $user->doctor;
        $doctor = Auth::user()->doctor;
        $appointments = Appointment::with(['patient.user', 'consultation.prescriptions'])
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->get();

        return view('doctor.appointments', compact('user','doctor','appointments'));
    }
    //cancel appointments
    public function cancelAppointments(Appointment $appointment)
    {
        $doctor = Auth::user()->doctor;

        // Ensure the appointment belongs to the logged-in doctor
        if (!$doctor || $appointment->doctor_id != $doctor->id) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent cancelling an already completed appointment (optional)
        if ($appointment->status === 'Completed') {
            return back()->with('error', 'Completed appointments cannot be cancelled.');
        }

        $appointment->update([
            'status' => 'Cancelled',
        ]);

        return back()->with('success', 'Appointment cancelled successfully.');
    }
    //mine
    public function storeConsultation(Request $request, Appointment $appointment)
    {
        $request->validate([
            'diagnosis' => 'required',
            'medicine_name.*' => 'required',
            'dosage.*' => 'required',
            'duration.*' => 'required',
        ]);

        $consultation = Consultation::updateOrCreate(
            [
                'appointment_id' => $appointment->id,
            ],
            [
                'doctor_id'    => Auth::user()->doctor->id,
                'patient_id'   => $appointment->patient_id,
                'diagnosis'    => $request->diagnosis,
                'instructions' => $request->instructions,
                'status'       => 'Completed',
            ]
        );
        $consultation->prescriptions()->delete();

        foreach ($request->medicine_name as $key => $medicine) {

            Prescription::create([
                'consultation_id' => $consultation->id,
                'patient_id'      => $appointment->patient_id,
                'prescribed_by'   => Auth::user()->doctor->id,
                'medicine_name'   => $medicine,
                'dosage'          => $request->dosage[$key],
                'duration'        => $request->duration[$key],
                'status'          => 'Active',
            ]);
        }

        $appointment->update([
            'status' => 'Completed',
        ]);

        return back()->with('success', 'Consultation Saved Successfully.');
    }
    //in class
    // public function storeConsultation(Request $request){
    //     $data = $request->validate([
    //         'appointment_id'=>'required|exists:appointment,id',
    //         'diagnosis'=>'required|string',
    //         'medicines'=>'nullable|array',
    //         'medicines.*.name'=>'required|string',
    //         'medicines.*.dosage'=>'required|string',
    //         'medicines.*.duration'=>'required|string',
    //         'directives'=>'nullable|string'
    //     ]);
    //     $appointment->update([
    //         'status'=>'Completed',
    //     ]);
    //     MedicalRecord::create([
    //         'patient_id'=>$appointment->patient_id,
    //         'title'=>'Consultation-'.$appointment->department,
    //         'type'=>'OPD Consultation',
    //         'doctor_name'=>Auth::user()->name,
    //         'description'=>'Diagnoses Summary:'.$data['diagnoses']."\nDirectives:".$data['directives'],
    //         'record_date'=>now()
    //     ]);
    //     if(!empty($data['medicines'])){
    //         foreach($data['medicines'] as $med){
    //             Prescription::create([
    //                 'patient_id'=>$appointment->patient_id,
    //                 'doctor_id'=>Auth::user()->doctor->id,
    //                 'medicine_name'=>$med['name'],
    //                 'dosage'=>$med['dosage'],
    //                 'duration'=>$med['duration'],
    //                 'prescribed_by'=>Auth::user()->name,
    //                 'status'=>'Active'
    //             ]);
    //         }
    //     }
    //     return redirect()->route('doctor.appointments')->with('success','Consultation details stored successfully!');
    // }
    public function patients(Request $request): View
    {
        $user=Auth::user();
        $doctor = $user->doctor;
        $query=Patient::with(['user','medicalRecords','labDocuments'])
        ->whereHas('appointments',function($q) use ($doctor){
            $q->where('doctor_id',$doctor->id);
        });
        if($request->filled('search')){
            $search = $request->search;
            $query->where(function($q) use ($search){
                $q->whereHas('user',function($uQuery) use ($search){
                    $uQuery->where('name','like',"%{$search}%");
                })
                ->orWhere('id','like',"%($search)%")
                ->orWhere('blood_group','like',"%{$search}%");
            });
        }
        $patients = $query->get();
        foreach($patients as $patient){
            $latestAppointment = Appointment::where('patient_id',$patient->id)
            ->where('doctor_id',$doctor->id)
            ->latest('appointment_date')
            ->first();
            $patient->last_visit_date= $latestAppointment 
            ? $latestAppointment->appointment_date->format('Y-m-d h:i A')
            :null;
        }
        return view('doctor.patients',compact('user','doctor','patients'));
    }
    public function settings(): View
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        return view('doctor.settings', compact('user', 'doctor'));
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        $data = $request->validate([
            'name' => 'required',
            'department' => 'required',
            'license_id' => 'required',
            'profile_photo' => 'required|image|max:2048',
            'bio' => 'required',
        ]);
        if ($request->hasFile('profile_photo')) {
            if ($doctor->profile_photo  && file_exists(public_path('doctors/profile/' . $doctor->profile_photo))) {
                unlink(public_path('doctors/profile/' . $doctor->profile_photo));
            }
            $file = $request->file('profile_photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('doctors/profile'), $filename);
            $data['profile_photo'] = $filename;
        } else {
            $data['profile_photo'] = $doctor->profile_photo;
        }
        $user->update([
            'name' => $data['name'],
        ]);
        $user->doctor()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'department' => $data['department'],
                'license_id' => $data['license_id'],
                'profile_photo' => $data['profile_photo'],
                'bio'=>$data['bio'],
            ]

        );

        return redirect()->route('doctor.settings')->with('success', 'Doctor Updated Successfully');
    }
    public function updateShifts(Request $request){
        $user=Auth::user();
        $doctor=$user->doctor;

        $data=$request->validate([
            'start_time'=>'required',
            'end_time'=>'required',
            'available_days'=>'required|array',
            'consultation_fee'=>'required',

        ]);
        $user->doctor()->updateOrCreate(
            [
                'user_id'=>$user->id
            ],
            [
                'start_time'=>$data['start_time'],
                'end_time'=>$data['end_time'],
                'available_days'=>$data['available_days']??[],
                'consultation_fee'=>$data['consultation_fee'],
            ]
        );
        return redirect()->route('doctor.settings')->with('success','Weekly Shifts And Availability Update Successfully');
    }
    public function updateSecurity(Request $request){
        $data = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The provided password does not match with Old Password'
            ]);
        }

        $user->update([
            'password' => Hash::make($data['new_password'])
        ]);
        return redirect()->route('doctor.settings')->with('success', 'Password Update Successfully');
    }
}
