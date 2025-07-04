@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dossier Médical: {{ $record->patient_name }}</h1>
        
        <div class="detail-actions">
            <a href="{{ route('records.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <a href="{{ route('records.export-pdf', $record) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exporter en PDF
            </a>
        </div>
        
        <div class="record-detail">
            <!-- Afficher les détails du dossier -->
        </div>
    </div>
@endsection