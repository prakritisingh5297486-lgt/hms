<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Prescription;
use App\Models\LabReport;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        $patient = Auth::user()->patient;
        // Next Appointment
        $nextAppointment = Appointment::with('doctor.user')
            ->where('patient_id', $patient->id)
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->first();

        // Pending Payment
        $pendingPayment = Payment::where('patient_id', $patient->id)
            ->where('payment_status', 0)
            ->sum('amount');

        // Ready Reports
        $readyReports = LabReport::where('patient_id', $patient->id)
            ->where('status', 'Completed')
            ->count();

        // Active Prescriptions
        $activePrescriptions = Prescription::where('patient_id', $patient->id)
            ->count();
        $appointments = Appointment::with('doctor.user')
            ->where('patient_id',$patient->id)
            ->latest()
            ->take(5)
            ->get();
        $prescriptions = Prescription::with('doctor.user')
            ->where('patient_id',Auth::user()->patient->id)
            ->latest()
            ->get();
        $reports = LabReport::where('patient_id', Auth::user()->patient->id)
            ->latest()
            ->get();  

        return view('patient.dashboard', compact(
            'user', 
            'patient',
            'appointments',
            'prescriptions',
            'reports',
            'nextAppointment',
            'pendingPayment',
            'readyReports',
            'activePrescriptions'
        ));
    }
    public function printPrescription(Prescription $prescription)
    {
        $pdf = Pdf::loadView(
            'patient.prescription.print',
            compact('prescription')
        );

        return $pdf->download('Prescription_'.$prescription->id.'.pdf');
    }
    public function appointments(): View
    {
        $patient = Auth::user()->patient;
        $appointments = $patient->appointments()
            ->with('doctor.user')
            ->orderBy('appointment_date', 'desc')
            ->get();

        $doctors = Doctor::with('user')->get();
        $departments = Doctor::select('department')->distinct()->pluck('department');
        return view('patient.appointments', compact('appointments', 'doctors', 'departments'));
    }
    public function bookAppointments(Request $request)
    {
        $patient = Auth::user()->patient;
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'symptoms' => 'nullable|string',
            'consultation_type' => 'nullable|string'
        ]);
        $doctor = Doctor::findOrFail($data['doctor_id']);
        $dataString = date('Y-m-d', strtotime($data['appointment_date']));
        $tokenNumber = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $dataString)
            ->count() + 1;

        $appointments = $patient->appointments()->create([
            'doctor_id' => $doctor->id,
            'appointment_date' => $data['appointment_date'],
            'department' => $doctor->department,
            'symptoms' => $data['symptoms'] ?? 'NA',
            'consultation_type' => $data['consultation_type'] ?? 'NA',
            'token_number' => $tokenNumber
        ]);
        return redirect()->route('patient.appointments')->with([
            'success' => 'Appointment booked with Successfully with Token Number',
            'booked_token' => $appointments->id
        ]);
    }
    public function cancelAppointment(Appointment $appointment)
    {
        // Security: Patient sirf apni appointment cancel kar sake
        if ($appointment->patient_id != Auth::user()->patient->id) {
            abort(403);
        }

        // Sirf Pending appointment hi cancel ho
        if ($appointment->status != 'Pending') {
            return back()->with('error', 'Only pending appointments can be cancelled.');
        }

        $appointment->update([
            'status' => 'Cancelled',
        ]);

        return back()->with('success', 'Appointment cancelled successfully.');
    }
    public function billing(): View
    {
        return view('patient.billing');
    }
    public function records(): View
    {
        $patient = Auth::user()->patient;
        $timelineRecords = $patient->medicalRecords()
            ->orderBy('record_date', 'desc')
            ->get();
        $activePrescriptions = $patient->prescriptions()->get();
        $labDocuments = $patient->labDocuments()->get();
        $prescriptions = Prescription::with('doctor.user')
            ->where('patient_id',Auth::user()->patient->id)
            ->latest()
            ->get();
        //medical records
        $timeline = Appointment::with(['doctor.user'])
        ->where('patient_id', Auth::user()->patient->id)
        ->latest()
        ->take(10)
        ->get();
        return view('patient.records',compact('timeline','prescriptions'));
    }
    public function settings(): View
    {
        return view('patient.settings');
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required',
            'disease' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'gender' => 'required',
            'age' => 'required',
            'blood_group' => 'required',
            'number' => 'required',
            'address' => 'required',
            // for update profile without add image in registration 
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email']
        ]);
        //for update image

        if ($request->hasFile('image')) {
            if (Auth::user()->patient->profile && file_exists('patients' . Auth::user()->patient->profile)) {
                unlink('patients' . Auth::user()->patient->profile);
            }
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('patients'), $filename);
            $data['profile'] = 'patients/' . $filename;
        }
        // if($request->hasFile('image')){
        // $oldprofile=$user->patient->profile;
        //     if(Auth::user()->patient->profile && file_exists('patients'.Auth::user()->patient->profile)){
        //         unlink('patients'.Auth::user()->patient->profile);
        //     }
        //     $file=$request->file('image');
        //     $filename = time().'.'.$file->getClientOriginalExtension();
        //     $file->move(public_path('patients'),$filename);
        //     $data['profile']='patients'.$filename;
        // }
        // else{
        //     $data['profile']= $user->patient->profile;
        // }
        $user->patient()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'disease' => $data['disease'],
                'gender' => $data['gender'],
                'age' => $data['age'],
                'number' => $data['number'],
                'blood_group' => $data['blood_group'],
                'address' => $data['address'],
                //for update image
                'profile' => $data['profile'] ?? Auth::user()->patient->profile
            ]

        );

        return redirect()->route('patient.settings')->with('success', 'Patient Updated Successfully');
    }
    public function updateSecurity(Request $request)
    {
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
        return redirect()->route('patient.settings')->with('success', 'Password Update Successfully');
    }
}
