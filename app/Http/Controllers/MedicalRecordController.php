<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);
            $search = $request->input('search', '');

            $query = MedicalRecord::query();

            if ($search) {
                $query->where('patient_name', 'like', '%' . $search . '%');
            }

            if (!auth()->user()->is_admin) {
                $query->where('user_id', auth()->user()->id);
            }

            $records = $query->orderBy('created_at', 'desc')
                            ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data' => $records->items(),
                'pagination' => [
                    'current_page' => $records->currentPage(),
                    'per_page' => $records->perPage(),
                    'total' => $records->total(),
                    'last_page' => $records->lastPage(),
                    'from' => ($records->currentPage() - 1) * $records->perPage() + 1,
                    'to' => min($records->currentPage() * $records->perPage(), $records->total())
                ]
            ]);
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
