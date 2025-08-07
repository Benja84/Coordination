<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        // $records = MedicalRecord::orderBy('created_at', 'desc')->get();
        // return view('records', compact('records'));
        if ($request->ajax() || $request->wantsJson()) {
            if(auth()->user()->is_admin)
                $records = MedicalRecord::orderBy('created_at', 'desc')->get();
            else
                $records = MedicalRecord::where('user_id',auth()->user()->id)->orderBy('created_at', 'desc')->get();
            return response()->json($records);
        }
        return view('form');
    }

    public function create()
    {
        return view('form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patientInfo.name' => 'required',
            'patientInfo.birthDate' => 'required|date',
            'patientInfo.startDate' => 'required|date',
            'patientInfo.deliveryDate' => 'required|date',
            'patientInfo.prescriber' => 'required',
            'patientInfo.phone' => 'required',
        ]);

        $record = MedicalRecord::create([
            'patient_name' => $request->patientInfo['name'],
            'birth_date' => $request->patientInfo['birthDate'],
            'start_date' => $request->patientInfo['startDate'],
            'delivery_date' => $request->patientInfo['deliveryDate'],
            'prescriber' => $request->patientInfo['prescriber'],
            'phone' => $request->patientInfo['phone'],
            'care_types' => $request->careTypes,
            'equipments' => $request->equipments,
            'sonde_followup' => $request->sondeFollowup,
            'stoma_followup' => $request->stomaFollowup,
            'wound_followup' => $request->woundFollowup,
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'success' => true,
            'recordId' => $record->id
        ]);
    }

    public function show(MedicalRecord $record)
    {
        return view('record-detail', compact('record'));
    }

    public function exportPdf(MedicalRecord $record)
    {
        $data = [
            'record' => $record,
        ];

        $pdf = PDF::loadView('pdf.medical-record', $data);
        return $pdf->download('dossier-medical-'.$record->patient_name.'.pdf');
    }
}
