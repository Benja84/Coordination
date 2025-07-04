<!-- resources/views/medical-form.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi Médical | Coordination</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div id="app">
        <div class="container">
            <header>
                <div class="logo">
                    <i class="fas fa-hand-holding-medical"></i>
                    <span>Coordination Médicale</span>
                </div>
                {{-- <div class="user-info">
                    <i class="fas fa-user-nurse"></i>
                    <span>Infirmier(e) Connecté</span>
                    <div class="status-indicator">
                        <div class="status-dot"></div>
                        <span>En ligne</span>
                    </div>
                </div> --}}
            </header>
            
            <div class="app-title">
                <h1>La coordination</h1>
                <h2>Fiche de Suivi - Sondage Intermittent / Stomie / Soins des Plaies</h2>
            </div>
            
            <div class="content">
                <!-- Page 1: Coordination -->
                <div v-if="currentPage === 1">
                    <div style="text-align: center; padding: 40px 0;">
                        <div style="font-size: 100px; color: #ffb300; margin-bottom: 20px;">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <h3 style="font-size: 28px; margin-bottom: 30px; color: #5d4037;">
                            Bienvenue sur la plateforme de coordination médicale
                        </h3>
                        <p style="margin-bottom: 40px; max-width: 700px; margin-left: auto; margin-right: auto; font-size: 18px; line-height: 1.8;">
                            Cette application permet de gérer les fiches de suivi pour les patients nécessitant 
                            des soins spécifiques tels que sondage intermittent, stomie ou soins des plaies.
                            Simplifiez votre travail quotidien avec notre interface intuitive et complète.
                        </p>
                        <button class="btn btn-primary" @click="currentPage = 2">
                            <i class="fas fa-play-circle"></i> Commencer
                        </button>
                    </div>
                </div>
                
                <!-- Page 2: Patient Information -->
                <div v-if="currentPage === 2">
                    <div class="form-section">
                        <h3><i class="fas fa-user-injured"></i> Information du patient</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nom / Prénom <span class="required"></span></label>
                                <input type="text" v-model="patientInfo.name" placeholder="Ex: Dupont Jean" required>
                                <span class="error" v-if="errors.name">@{{ errors.name }}</span>
                            </div>
                            <div class="form-group">
                                <label>Date de naissance <span class="required"></span></label>
                                <input type="date" v-model="patientInfo.birthDate" required>
                                <span class="error" v-if="errors.birthDate">@{{ errors.birthDate }}</span>
                            </div>
                            <div class="form-group">
                                <label>Date de prise en charge <span class="required"></span></label>
                                <input type="date" v-model="patientInfo.startDate" required>
                                <span class="error" v-if="errors.startDate">@{{ errors.startDate }}</span>
                            </div>
                            <div class="form-group">
                                <label>Date de livraison <span class="required"></span></label>
                                <input type="date" v-model="patientInfo.deliveryDate" required>
                                <span class="error" v-if="errors.deliveryDate">@{{ errors.deliveryDate }}</span>
                            </div>
                            <div class="form-group">
                                <label>Prescripteur <span class="required"></span></label>
                                <input type="text" v-model="patientInfo.prescriber" placeholder="Ex: Dr. Martin" required>
                                <span class="error" v-if="errors.prescriber">@{{ errors.prescriber }}</span>
                            </div>
                            <div class="form-group">
                                <label>Téléphone <span class="required"></span></label>
                                <input type="tel" v-model="patientInfo.phone" placeholder="Ex: 06 12 34 56 78" required>
                                <span class="error" v-if="errors.phone">@{{ errors.phone }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3><i class="fas fa-stethoscope"></i> Type de prise en charge</h3>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="intermittent" v-model="careTypes.intermittent">
                                <label for="intermittent">Sondage intermittent</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="stomie" v-model="careTypes.stomie">
                                <label for="stomie">Stomie</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="wound" v-model="careTypes.wound">
                                <label for="wound">Soins des plaies</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="irrigation" v-model="careTypes.irrigation">
                                <label for="irrigation">Irrigation trans-anale</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3><i class="fas fa-toolbox"></i> Appareillage prescrit</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date de début</th>
                                    <th>Matériel prescrit</th>
                                    <th>Fréquence d'utilisation</th>
                                    <th>Fournisseur / Marque</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(equipment, index) in equipments" :key="index">
                                    <td><input type="date" v-model="equipment.startDate"></td>
                                    <td><input type="text" v-model="equipment.material" placeholder="Ex: Sondes urinaires" required></td>
                                    <td><input type="text" v-model="equipment.frequency" placeholder="Ex: 3 fois/jour" required></td>
                                    <td><input type="text" v-model="equipment.supplier" placeholder="Ex: MedSupply" required></td>
                                    <td>
                                        <button class="btn btn-delete" @click="removeEquipment(index)">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-add" @click="addEquipment">
                            <i class="fas fa-plus-circle"></i> Ajouter un appareil
                        </button>
                    </div>
                    
                    <div class="footer-buttons">
                        <button class="btn btn-secondary" @click="currentPage = 1">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                        <button class="btn btn-primary" @click="validatePage2">
                            <i class="fas fa-arrow-right"></i> Suivant
                        </button>
                    </div>
                </div>
                
                <!-- Page 3: Suivi / Évaluation -->
                <div v-if="currentPage === 3">
                    <div class="form-section">
                        <h3><i class="fas fa-clipboard-check"></i> Suivi / Évaluation du patient</h3>
                        <p class="section-description">
                            Utilisez les tableaux ci-dessous pour enregistrer les évaluations de suivi.
                            Vous pouvez ajouter autant d'entrées que nécessaire pour chaque type de soin.
                        </p>
                    </div>
                    
                    <!-- Sondage Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-catheter"></i> Sondage</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Autonomie</th>
                                    <th>Technique</th>
                                    <th>Complications observées</th>
                                    <th>Maîtrise ?</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(sonde, index) in sondeFollowup" :key="'sonde-'+index">
                                    <td><input type="date" v-model="sonde.date" required></td>
                                    <td>
                                        <select v-model="sonde.autonomy" required>
                                            <option value="complète">Complète</option>
                                            <option value="partielle">Partielle</option>
                                            <option value="assistée">Assistée</option>
                                        </select>
                                    </td>
                                    <td><textarea v-model="sonde.technique" rows="2" placeholder="Décrire la technique" required></textarea></td>
                                    <td><textarea v-model="sonde.complications" rows="2" placeholder="Décrire les complications"></textarea></td>
                                    <td>
                                        <select v-model="sonde.mastery" required>
                                            <option value="excellente">Excellente</option>
                                            <option value="bonne">Bonne</option>
                                            <option value="moyenne">Moyenne</option>
                                            <option value="faible">Faible</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-delete" @click="removeSonde(index)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-add" @click="addSonde">
                            <i class="fas fa-plus-circle"></i> Ajouter une évaluation de sondage
                        </button>
                    </div>
                    
                    <!-- Stomie Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-stomach"></i> Stomie</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type de stomie</th>
                                    <th>État de la peau</th>
                                    <th>Problème rencontré</th>
                                    <th>Commentaires</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(stoma, index) in stomaFollowup" :key="'stoma-'+index">
                                    <td><input type="date" v-model="stoma.date" required></td>
                                    <td>
                                        <select v-model="stoma.type" required>
                                            <option value="iléostomie">Iléostomie</option>
                                            <option value="colostomie">Colostomie</option>
                                            <option value="urostomie">Urostomie</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select v-model="stoma.skinState" required>
                                            <option value="excellent">Excellent</option>
                                            <option value="bon">Bon</option>
                                            <option value="irritation">Irritation</option>
                                            <option value="inflammation">Inflammation</option>
                                        </select>
                                    </td>
                                    <td><textarea v-model="stoma.problem" rows="2" placeholder="Décrire le problème" required></textarea></td>
                                    <td><textarea v-model="stoma.comments" rows="2" placeholder="Ajouter des commentaires"></textarea></td>
                                    <td>
                                        <button class="btn btn-delete" @click="removeStoma(index)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-add" @click="addStoma">
                            <i class="fas fa-plus-circle"></i> Ajouter une évaluation de stomie
                        </button>
                    </div>
                    
                    <!-- Plaies Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-bandaid"></i> Plaies</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type de plaie</th>
                                    <th>Localisation</th>
                                    <th>Évolution</th>
                                    <th>Produits utilisés</th>
                                    <th>Commentaires</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(wound, index) in woundFollowup" :key="'wound-'+index">
                                    <td><input type="date" v-model="wound.date" required></td>
                                    <td>
                                        <select v-model="wound.type" required>
                                            <option value="aiguë">Aiguë</option>
                                            <option value="chronique">Chronique</option>
                                            <option value="pression">Pression</option>
                                            <option value="diabétique">Diabétique</option>
                                        </select>
                                    </td>
                                    <td><input type="text" v-model="wound.location" placeholder="Ex: Jambe droite" required></td>
                                    <td>
                                        <select v-model="wound.evolution" required>
                                            <option value="amélioration">Amélioration</option>
                                            <option value="stabilisation">Stabilisation</option>
                                            <option value="aggravation">Aggravation</option>
                                        </select>
                                    </td>
                                    <td><textarea v-model="wound.products" rows="2" placeholder="Liste des produits" required></textarea></td>
                                    <td><textarea v-model="wound.comments" rows="2" placeholder="Ajouter des commentaires"></textarea></td>
                                    <td>
                                        <button class="btn btn-delete" @click="removeWound(index)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-add" @click="addWound">
                            <i class="fas fa-plus-circle"></i> Ajouter une évaluation de plaie
                        </button>
                    </div>
                    
                    <div class="summary-box">
                        <p><strong>Patient:</strong> @{{ patientInfo.name || 'Non renseigné' }}</p>
                        <p><strong>Prise en charge:</strong> 
                            <span v-if="careTypes.intermittent">Sondage intermittent</span>
                            <span v-if="careTypes.stomie">, Stomie</span>
                            <span v-if="careTypes.wound">, Soins des plaies</span>
                            <span v-if="careTypes.irrigation">, Irrigation trans-anale</span>
                            <span v-if="!careTypes.intermittent && !careTypes.stomie && !careTypes.wound && !careTypes.irrigation">Aucun</span>
                        </p>
                        <p><strong>Appareillage:</strong> @{{ equipments.length }} appareil(s) prescrit(s)</p>
                        <p><strong>Évaluations:</strong> 
                            @{{ sondeFollowup.length }} sondage(s), 
                            @{{ stomaFollowup.length }} stomie(s), 
                            @{{ woundFollowup.length }} plaie(s)
                        </p>
                    </div>
                    
                    <div class="footer-buttons">
                        <button class="btn btn-secondary" @click="currentPage = 2">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                        <button class="btn btn-primary" @click="saveForm">
                            <i class="fas fa-save"></i> Enregistrer le dossier
                        </button>
                        <button v-if="recordId" class="btn btn-export" @click="exportPdf">
                            <i class="fas fa-file-pdf"></i> Exporter en PDF
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="page-indicator">
                <div class="page-dot" :class="{'active': currentPage === 1}"></div>
                <div class="page-dot" :class="{'active': currentPage === 2}"></div>
                <div class="page-dot" :class="{'active': currentPage === 3}"></div>
            </div>
        </div>
        
        <div class="floating-btn" @click="saveForm">
            <i class="fas fa-save"></i>
        </div>
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                currentPage: 1,
                patientInfo: {
                    name: '',
                    birthDate: '',
                    startDate: '',
                    deliveryDate: '',
                    prescriber: '',
                    phone: ''
                },
                careTypes: {
                    intermittent: false,
                    stomie: true,
                    wound: false,
                    irrigation: true
                },
                equipments: [
                    { startDate: '', material: '', frequency: '', supplier: '' }
                ],
                sondeFollowup: [
                    { date: '', autonomy: 'complète', technique: '', complications: '', mastery: 'bonne' }
                ],
                stomaFollowup: [
                    { date: '', type: 'colostomie', skinState: 'bon', problem: '', comments: '' }
                ],
                woundFollowup: [
                    { date: '', type: 'chronique', location: '', evolution: 'stabilisation', products: '', comments: '' }
                ],
                errors: {},
                recordId: null
            },
            methods: {
                addEquipment() {
                    this.equipments.push({ 
                        startDate: '', 
                        material: '', 
                        frequency: '', 
                        supplier: '' 
                    });
                },
                removeEquipment(index) {
                    if (this.equipments.length > 1) {
                        this.equipments.splice(index, 1);
                    } else {
                        alert("Au moins un appareil doit être présent");
                    }
                },
                addSonde() {
                    this.sondeFollowup.push({
                        date: '',
                        autonomy: 'complète',
                        technique: '',
                        complications: '',
                        mastery: 'bonne'
                    });
                },
                removeSonde(index) {
                    if (this.sondeFollowup.length > 1) {
                        this.sondeFollowup.splice(index, 1);
                    } else {
                        alert("Au moins une évaluation de sondage doit être présente");
                    }
                },
                addStoma() {
                    this.stomaFollowup.push({
                        date: '',
                        type: 'colostomie',
                        skinState: 'bon',
                        problem: '',
                        comments: ''
                    });
                },
                removeStoma(index) {
                    if (this.stomaFollowup.length > 1) {
                        this.stomaFollowup.splice(index, 1);
                    } else {
                        alert("Au moins une évaluation de stomie doit être présente");
                    }
                },
                addWound() {
                    this.woundFollowup.push({
                        date: '',
                        type: 'chronique',
                        location: '',
                        evolution: 'stabilisation',
                        products: '',
                        comments: ''
                    });
                },
                removeWound(index) {
                    if (this.woundFollowup.length > 1) {
                        this.woundFollowup.splice(index, 1);
                    } else {
                        alert("Au moins une évaluation de plaie doit être présente");
                    }
                },
                
                validatePage2() {
                    this.errors = {};
                    let isValid = true;
                    
                    // Validation des champs obligatoires
                    if (!this.patientInfo.name) {
                        this.errors.name = "Le nom du patient est requis";
                        isValid = false;
                    }
                    if (!this.patientInfo.birthDate) {
                        this.errors.birthDate = "La date de naissance est requise";
                        isValid = false;
                    }
                    if (!this.patientInfo.startDate) {
                        this.errors.startDate = "La date de prise en charge est requise";
                        isValid = false;
                    }
                    if (!this.patientInfo.deliveryDate) {
                        this.errors.deliveryDate = "La date de livraison est requise";
                        isValid = false;
                    }
                    if (!this.patientInfo.prescriber) {
                        this.errors.prescriber = "Le prescripteur est requis";
                        isValid = false;
                    }
                    if (!this.patientInfo.phone) {
                        this.errors.phone = "Le téléphone est requis";
                        isValid = false;
                    }
                    
                    // Validation des équipements
                    for (let i = 0; i < this.equipments.length; i++) {
                        const eq = this.equipments[i];
                        if (!eq.material) {
                            this.errors[`equipment_${i}_material`] = "Le matériel est requis";
                            isValid = false;
                        }
                        if (!eq.frequency) {
                            this.errors[`equipment_${i}_frequency`] = "La fréquence est requise";
                            isValid = false;
                        }
                        if (!eq.supplier) {
                            this.errors[`equipment_${i}_supplier`] = "Le fournisseur est requis";
                            isValid = false;
                        }
                    }
                    
                    if (isValid) {
                        this.currentPage = 3;
                    }
                    
                    return isValid;
                },
                
                saveForm() {
                    // Validation supplémentaire pour la page 3
                    let isValid = true;
                    this.errors = {};
                    
                    // Validation des sondes
                    for (let i = 0; i < this.sondeFollowup.length; i++) {
                        const s = this.sondeFollowup[i];
                        if (!s.date) {
                            this.errors[`sonde_${i}_date`] = "La date est requise";
                            isValid = false;
                        }
                        if (!s.technique) {
                            this.errors[`sonde_${i}_technique`] = "La technique est requise";
                            isValid = false;
                        }
                    }
                    
                    // Validation des stomies
                    for (let i = 0; i < this.stomaFollowup.length; i++) {
                        const s = this.stomaFollowup[i];
                        if (!s.date) {
                            this.errors[`stoma_${i}_date`] = "La date est requise";
                            isValid = false;
                        }
                        if (!s.problem) {
                            this.errors[`stoma_${i}_problem`] = "Le problème rencontré est requis";
                            isValid = false;
                        }
                    }
                    
                    // Validation des plaies
                    for (let i = 0; i < this.woundFollowup.length; i++) {
                        const w = this.woundFollowup[i];
                        if (!w.date) {
                            this.errors[`wound_${i}_date`] = "La date est requise";
                            isValid = false;
                        }
                        if (!w.location) {
                            this.errors[`wound_${i}_location`] = "La localisation est requise";
                            isValid = false;
                        }
                        if (!w.products) {
                            this.errors[`wound_${i}_products`] = "Les produits utilisés sont requis";
                            isValid = false;
                        }
                    }
                    
                    if (!isValid) return;
                    
                    // Envoyer les données au serveur
                    axios.post('/save', {
                        patientInfo: this.patientInfo,
                        careTypes: this.careTypes,
                        equipments: this.equipments,
                        sondeFollowup: this.sondeFollowup,
                        stomaFollowup: this.stomaFollowup,
                        woundFollowup: this.woundFollowup
                    })
                    .then(response => {
                        this.recordId = response.data.recordId;
                        alert("Dossier médical enregistré avec succès !");
                    })
                    .catch(error => {
                        console.error("Erreur lors de l'enregistrement:", error);
                        alert("Une erreur est survenue lors de l'enregistrement.");
                    });
                },
                
                exportPdf() {
                    if (!this.recordId) {
                        alert("Veuillez d'abord enregistrer le dossier");
                        return;
                    }
                    
                    window.location.href = `/export-pdf?id=${this.recordId}`;
                }
            }
        });
    </script>
</body>
</html>