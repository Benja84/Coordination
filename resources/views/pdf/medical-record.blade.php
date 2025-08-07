<!DOCTYPE html>
<html>
<head>
    <title>Dossier Médical {{ $record->patient_name }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; color: #5d4037;font-size: 12px; }
        .header { 
            margin-top: -15px;
            margin-bottom: 10px;  
        }
        .title   { 
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            color: #5d4037;
            line-height: 1.3;
        }
        .subtitle-patient,.subtitle-section{ font-size: 12px; color: #8d6e63;}
        .subtitle{ font-size: 20px; color: #8d6e63; text-align: center;}
        /* .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
        .subtitle { font-size: 15px; margin-bottom: 20px; } */
        .section { margin-bottom: 15px; }
        .section-title { font-size: 15px; font-weight: bold; border-bottom: 2px solid #ffb300; padding-bottom: 5px; margin-bottom: 15px; }
        .section table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .section th, .section td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .section th { background-color: #ffb300; }
        .summary { background-color: #fff8e1; padding: 15px; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    {{-- <div class="header">
        <div class="title">La coordination</div>
        <div class="subtitle">Fiche de Suivi - Sondage Intermittent / Stomie / Soins des Plaies</div>
        <div>Dossier médical #{{ $record->id }}</div>
    </div> --}}
    <div class="header">
        <table width="100%" style="border: none;">
            <tr>
                <td style="text-align: left; vertical-align: middle;">
                    <span class="title">La coordination</span><br>
                    <span class="subtitle-patient">Prise en charge du patient</span><br>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('images/Ousoft.png'))) }}"
                        alt="OUSOFT" style="height: 80px;">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; padding-top: 8px;">
                    <span class="subtitle">Fiche de Suivi - Sondage Intermittent / Stomie / Soins des Plaies</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">1. Information du patient</div>
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
        <div class="section-title">2. Type de prise en charge</div>
        <ul>
            @if($record->care_types['intermittent']) <li>Sondage intermittent</li> @endif
            @if($record->care_types['stomie']) <li>Stomie</li> @endif
            @if($record->care_types['wound']) <li>Soins des plaies</li> @endif
            @if($record->care_types['irrigation']) <li>Irrigation trans-anale</li> @endif
        </ul>
    </div>

    <div class="section">
        <div class="section-title">3. Appareillage prescrit</div>
        <table>
            <thead>
                <tr>
                    {{-- <th>Date de début</th> --}}
                    <th>Matériel prescrit</th>
                    <th>Fréquence d'utilisation</th>
                    <th>Fournisseur / Marque</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->equipments as $equipment)
                <tr>
                    {{-- <td>{{ $equipment['startDate'] ? \Carbon\Carbon::parse($equipment['startDate'])->format('d/m/Y') : '-' }}</td> --}}
                    <td>{{ $equipment['material'] }}</td>
                    <td>{{ $equipment['frequency'] }}</td>
                    <td>{{ $equipment['supplier'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    
    <div class="section">
        <div class="section-title">4. Suivi / Évaluation du patient </div>
        @if($record->sonde_followup && count($record->sonde_followup) > 0)
            <span class="subtitle-section">Sondage intermittent </span><br>
            <table>
                <thead>
                    <tr>
                        {{-- <th>Date</th> --}}
                        <th>Autonomie</th>
                        <th>Technique maîtrisée</th>
                        <th>Complications observées</th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->sonde_followup as $sonde)
                    <tr>
                        {{-- <td>{{ \Carbon\Carbon::parse($sonde['date'])->format('d/m/Y') }}</td> --}}
                        <td>{{ $sonde['autonomy'] }}</td>
                        <td>{{ $sonde['technique'] }}</td>
                        <td>{{ $sonde['complications'] ?? '-' }}</td>
                        <td>{{ $sonde['mastery'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @if($record->stoma_followup && count($record->stoma_followup) > 0)
            <div class="subtitle-section">Stoma</div>
            <table>
                <thead>
                    <tr>
                        {{-- <th>Date</th> --}}
                        <th>Type de stomie</th>
                        <th>Etat de la peau péristomiale</th>
                        <th>Problèmes rencotrés</th>
                        <th>Commentaires</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->stoma_followup as $stoma)
                    <tr>
                        {{-- <td>{{ \Carbon\Carbon::parse($sonde['date'])->format('d/m/Y') }}</td> --}}
                        <td>{{ $stoma['type'] }}</td>
                        <td>{{ $stoma['skinState'] }}</td>
                        <td>{{ $stoma['problem'] ?? '-' }}</td>
                        <td>{{ $stoma['comments'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @if($record->wound_followup && count($record->wound_followup) > 0)
            <div class="subtitle-section">Plaies</div>
            <table>
                <thead>
                    <tr>
                        {{-- <th>Date</th> --}}
                        <th>Type de plaie</th>
                        <th>Localisation</th>
                        <th>Evolution</th>
                        <th>Produits utilisés</th>
                        <th>Commentaires</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->wound_followup as $wond)
                    <tr>
                        {{-- <td>{{ \Carbon\Carbon::parse($sonde['date'])->format('d/m/Y') }}</td> --}}
                        <td>{{ $wond['type'] }}</td>
                        <td>{{ $wond['location'] }}</td>
                        <td>{{ $wond['evolution'] ?? '-' }}</td>
                        <td>{{ $wond['products'] }}</td>
                        <td>{{ $wond['comments'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- @if($record->stoma_followup && count($record->stoma_followup) > 0)
    <div class="section">
        <div class="section-title">Stoma</div>
        <table>
            <thead>
                <tr>
                    <th>Type de stomie</th>
                    <th>Etat de la peau</th>
                    <th>Complications</th>
                    <th>Commentaires</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->stoma_followup as $stoma)
                <tr>
                    <td>{{ $stoma['type'] }}</td>
                    <td>{{ $stoma['skinState'] }}</td>
                    <td>{{ $stoma['problem'] ?? '-' }}</td>
                    <td>{{ $stoma['comments'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($record->wond_followup && count($record->wond_followup) > 0)
    <div class="section">
        <div class="section-title">Plaies</div>
        <table>
            <thead>
                <tr>
                    <th>Type de plaie</th>
                    <th>Localisation</th>
                    <th>Evolution</th>
                    <th>Produits utilisés</th>
                    <th>Commentaires</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->wond_followup as $wond)
                <tr>
                    <td>{{ $wond['type'] }}</td>
                    <td>{{ $wond['location'] }}</td>
                    <td>{{ $wond['evolution'] ?? '-' }}</td>
                    <td>{{ $wond['products'] }}</td>
                    <td>{{ $wond['comments'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif --}}

    <div class="section">
        <div class="section-title">5. Observations générales / Suivi à domicile </div>
        <table>
            <thead>
                <tr>
                    <th>Observation / Intervention réalisée </th>
                    <th>Nom du professionnel </th>
                    <th>Signature</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="height: 50px!important"></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
   

    {{-- <div class="summary">
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
    </div> --}}
</body>
</html>