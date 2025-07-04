<!DOCTYPE html>
<html>
<head>
    <title>Dossier Médical #{{ $record->id }}</title>
    <style>
        body { font-family: 'Roboto', sans-serif; color: #5d4037; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
        .subtitle { font-size: 18px; margin-bottom: 20px; }
        .section { margin-bottom: 30px; }
        .section-title { font-size: 20px; font-weight: bold; border-bottom: 2px solid #ffb300; padding-bottom: 5px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #ffb300; }
        .summary { background-color: #fff8e1; padding: 15px; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">La coordination</div>
        <div class="subtitle">Fiche de Suivi - Sondage Intermittent / Stomie / Soins des Plaies</div>
        <div>Dossier médical #{{ $record->id }}</div>
    </div>

    <div class="section">
        <div class="section-title">Information du patient</div>
        <table>
            <tr>
                <th>Nom / Prénom</th>
                <td>{{ $record->patient_name }}</td>
                <th>Date de naissance</th>
                <td>{{ $record->birth_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Date de prise en charge</th>
                <td>{{ $record->start_date->format('d/m/Y') }}</td>
                <th>Date de livraison</th>
                <td>{{ $record->delivery_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Prescripteur</th>
                <td>{{ $record->prescriber }}</td>
                <th>Téléphone</th>
                <td>{{ $record->phone }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Type de prise en charge</div>
        <ul>
            @if($record->care_types['intermittent']) <li>Sondage intermittent</li> @endif
            @if($record->care_types['stomie']) <li>Stomie</li> @endif
            @if($record->care_types['wound']) <li>Soins des plaies</li> @endif
            @if($record->care_types['irrigation']) <li>Irrigation trans-anale</li> @endif
        </ul>
    </div>

    <div class="section">
        <div class="section-title">Appareillage prescrit</div>
        <table>
            <thead>
                <tr>
                    <th>Date de début</th>
                    <th>Matériel prescrit</th>
                    <th>Fréquence d'utilisation</th>
                    <th>Fournisseur / Marque</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->equipments as $equipment)
                <tr>
                    <td>{{ $equipment['startDate'] ? \Carbon\Carbon::parse($equipment['startDate'])->format('d/m/Y') : '-' }}</td>
                    <td>{{ $equipment['material'] }}</td>
                    <td>{{ $equipment['frequency'] }}</td>
                    <td>{{ $equipment['supplier'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(count($record->sonde_followup) > 0)
    <div class="section">
        <div class="section-title">Sondage</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Autonomie</th>
                    <th>Technique</th>
                    <th>Complications observées</th>
                    <th>Maîtrise</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->sonde_followup as $sonde)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($sonde['date'])->format('d/m/Y') }}</td>
                    <td>{{ $sonde['autonomy'] }}</td>
                    <td>{{ $sonde['technique'] }}</td>
                    <td>{{ $sonde['complications'] ?? '-' }}</td>
                    <td>{{ $sonde['mastery'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Ajouter des sections similaires pour stomie et plaies -->

    <div class="summary">
        <strong>Résumé du dossier:</strong><br>
        <strong>Patient:</strong> {{ $record->patient_name }}<br>
        <strong>Prise en charge:</strong>
        @if($record->care_types['intermittent']) Sondage intermittent @endif
        @if($record->care_types['stomie']), Stomie @endif
        @if($record->care_types['wound']), Soins des plaies @endif
        @if($record->care_types['irrigation']), Irrigation trans-anale @endif
        <br>
        <strong>Appareillage:</strong> {{ count($record->equipments) }} appareil(s) prescrit(s)<br>
        <strong>Évaluations:</strong>
        {{ count($record->sonde_followup) }} sondage(s),
        {{ count($record->stoma_followup) }} stomie(s),
        {{ count($record->wound_followup) }} plaie(s)
    </div>
</body>
</html>