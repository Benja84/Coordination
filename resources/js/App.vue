<template>
    <div class="app-container">
        <!-- En-tête de l'application -->
        <header>
            <div class="logo">
                <i class="fas fa-hand-holding-medical"></i>
                <span>Coordination Médicale</span>
            </div>
            <div class="user-info">
                <i class="fas fa-user-nurse"></i>
                <span>Infirmier(e) Connecté</span>
                <div class="status-indicator">
                    <div class="status-dot"></div>
                    <span>En ligne</span>
                </div>
            </div>
        </header>
        
        <!-- Titre de l'application -->
        <div class="app-title">
            <h1>La coordination</h1>
            <h2>Fiche de Suivi - Sondage Intermittent / Stomie / Soins des Plaies</h2>
        </div>
        
        <!-- Navigation entre les pages -->
        <div class="navigation">
            <button class="btn btn-nav" :class="{'active': viewMode === 'form'}" @click="viewMode = 'form'">
                <i class="fas fa-file-medical"></i> Nouveau Dossier
            </button>
            <button class="btn btn-nav" :class="{'active': viewMode === 'records'}" @click="viewMode = 'records'; fetchRecords()">
                <i class="fas fa-list"></i> Dossiers Enregistrés
            </button>
        </div>
        
        <!-- Contenu principal -->
        <div class="content-container">
            <!-- Formulaire de création -->
            <div v-if="viewMode === 'form'" class="content">
                <!-- Page 1: Coordination -->
                <div v-if="currentPage === 1">
                    <div class="welcome-screen">
                        <div class="welcome-icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <h3>Bienvenue sur la plateforme de coordination médicale</h3>
                        <p>
                            Cette application permet de gérer les fiches de suivi pour les patients nécessitant 
                            des soins spécifiques tels que sondage intermittent, stomie ou soins des plaies.
                        </p>
                        <button class="btn btn-primary" @click="currentPage = 2">
                            <i class="fas fa-play-circle"></i> Commencer un nouveau dossier
                        </button>
                    </div>
                </div>
                
                <!-- Page 2: Patient Information -->
                <div v-if="currentPage === 2">
                    <div class="form-section">
                        <h3><i class="fas fa-user-injured"></i> Information du patient</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nom / Prénom <span class="required">*</span></label>
                                <input type="text" v-model="patientInfo.name" placeholder="Ex: Dupont Jean" required>
                                <span class="error" v-if="errors.name">{{ errors.name }}</span>
                            </div>
                            <div class="form-group">
                                <label>Date de naissance <span class="required">*</span></label>
                                <input type="date" v-model="patientInfo.birthDate" required>
                                <span class="error" v-if="errors.birthDate">{{ errors.birthDate }}</span>
                            </div>
                            <div class="form-group">
                                <label>Date de prise en charge <span class="required">*</span></label>
                                <input type="date" v-model="patientInfo.startDate" required>
                                <span class="error" v-if="errors.startDate">{{ errors.startDate }}</span>
                            </div>
                            <div class="form-group">
                                <label>Date de livraison <span class="required">*</span></label>
                                <input type="date" v-model="patientInfo.deliveryDate" required>
                                <span class="error" v-if="errors.deliveryDate">{{ errors.deliveryDate }}</span>
                            </div>
                            <div class="form-group">
                                <label>Prescripteur <span class="required">*</span></label>
                                <input type="text" v-model="patientInfo.prescriber" placeholder="Ex: Dr. Martin" required>
                                <span class="error" v-if="errors.prescriber">{{ errors.prescriber }}</span>
                            </div>
                            <div class="form-group">
                                <label>Téléphone <span class="required">*</span></label>
                                <input type="tel" v-model="patientInfo.phone" placeholder="Ex: 06 12 34 56 78" required>
                                <span class="error" v-if="errors.phone">{{ errors.phone }}</span>
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
                        <div class="table-container">
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
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                        <div class="table-container">
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
                        </div>
                        <button class="btn btn-add" @click="addSonde">
                            <i class="fas fa-plus-circle"></i> Ajouter une évaluation de sondage
                        </button>
                    </div>
                    
                    <!-- Stomie Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-stomach"></i> Stomie</h3>
                        <div class="table-container">
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
                        </div>
                        <button class="btn btn-add" @click="addStoma">
                            <i class="fas fa-plus-circle"></i> Ajouter une évaluation de stomie
                        </button>
                    </div>
                    
                    <!-- Plaies Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-bandaid"></i> Plaies</h3>
                        <div class="table-container">
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
                        </div>
                        <button class="btn btn-add" @click="addWound">
                            <i class="fas fa-plus-circle"></i> Ajouter une évaluation de plaie
                        </button>
                    </div>
                    
                    <div class="summary-box">
                        <h4><i class="fas fa-file-medical-alt"></i> Résumé du dossier</h4>
                        <p><strong>Patient:</strong> {{ patientInfo.name || 'Non renseigné' }}</p>
                        <p><strong>Prise en charge:</strong> 
                            <span v-if="careTypes.intermittent">Sondage intermittent</span>
                            <span v-if="careTypes.stomie">, Stomie</span>
                            <span v-if="careTypes.wound">, Soins des plaies</span>
                            <span v-if="careTypes.irrigation">, Irrigation trans-anale</span>
                            <span v-if="!careTypes.intermittent && !careTypes.stomie && !careTypes.wound && !careTypes.irrigation">Aucun</span>
                        </p>
                        <p><strong>Appareillage:</strong> {{ equipments.length }} appareil(s) prescrit(s)</p>
                        <p><strong>Évaluations:</strong> 
                            {{ sondeFollowup.length }} sondage(s), 
                            {{ stomaFollowup.length }} stomie(s), 
                            {{ woundFollowup.length }} plaie(s)
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
            
            <!-- Liste des dossiers enregistrés -->
            <div v-if="viewMode === 'records'" class="records-container">
                <div class="records-header">
                    <h3><i class="fas fa-folder-open"></i> Dossiers Médicaux Enregistrés</h3>
                    <div class="search-box">
                        <input type="text" v-model="searchQuery" placeholder="Rechercher un patient...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                
                <div class="records-list">
                    <div v-if="loading" class="loading">
                        <i class="fas fa-spinner fa-spin"></i> Chargement des dossiers...
                    </div>
                    
                    <div v-if="filteredRecords.length === 0 && !loading" class="no-records">
                        <i class="fas fa-folder-open"></i>
                        <p>Aucun dossier médical enregistré</p>
                        <button class="btn btn-primary" @click="viewMode = 'form'">
                            <i class="fas fa-plus"></i> Créer un nouveau dossier
                        </button>
                    </div>
                    
                    <div v-for="record in filteredRecords" :key="record.id" class="record-card">
                        <div class="record-header">
                            <div class="patient-info">
                                <h4>{{ record.patient_name }}</h4>
                                <p>Créé le: {{ new Date(record.created_at).toLocaleDateString() }}</p>
                            </div>
                            <div class="record-actions">
                                <button class="btn btn-view" @click="viewRecord(record)">
                                    <i class="fas fa-eye"></i> Voir
                                </button>
                                <button class="btn btn-export" @click="exportRecordPdf(record.id)">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                            </div>
                        </div>
                        
                        <div class="record-summary">
                            <p><strong>Prise en charge:</strong> 
                                <span v-if="record.care_types.intermittent">Sondage</span>
                                <span v-if="record.care_types.stomie">, Stomie</span>
                                <span v-if="record.care_types.wound">, Plaies</span>
                                <span v-if="record.care_types.irrigation">, Irrigation</span>
                            </p>
                            <p><strong>Évaluations:</strong> 
                                {{ record.sonde_followup.length }} sondages,
                                {{ record.stoma_followup.length }} stomies,
                                {{ record.wound_followup.length }} plaies
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Détail d'un dossier -->
            <div v-if="viewMode === 'record-detail'" class="record-detail-container">
                <div class="detail-header">
                    <button class="btn btn-back" @click="viewMode = 'records'">
                        <i class="fas fa-arrow-left"></i> Retour
                    </button>
                    <h3>Dossier Médical: {{ currentRecord.patient_name }}</h3>
                    <button class="btn btn-export" @click="exportRecordPdf(currentRecord.id)">
                        <i class="fas fa-file-pdf"></i> Exporter en PDF
                    </button>
                </div>
                
                <div class="detail-content">
                    <!-- Affichage détaillé du dossier -->
                    <div class="detail-section">
                        <h4><i class="fas fa-user-injured"></i> Information du patient</h4>
                        <div class="detail-grid">
                            <div>
                                <label>Nom / Prénom</label>
                                <p>{{ currentRecord.patient_name }}</p>
                            </div>
                            <div>
                                <label>Date de naissance</label>
                                <p>{{ new Date(currentRecord.birth_date).toLocaleDateString() }}</p>
                            </div>
                            <div>
                                <label>Date de prise en charge</label>
                                <p>{{ new Date(currentRecord.start_date).toLocaleDateString() }}</p>
                            </div>
                            <div>
                                <label>Date de livraison</label>
                                <p>{{ new Date(currentRecord.delivery_date).toLocaleDateString() }}</p>
                            </div>
                            <div>
                                <label>Prescripteur</label>
                                <p>{{ currentRecord.prescriber }}</p>
                            </div>
                            <div>
                                <label>Téléphone</label>
                                <p>{{ currentRecord.phone }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sections de suivi -->
                    <div class="detail-section" v-if="currentRecord.sonde_followup.length > 0">
                        <h4><i class="fas fa-catheter"></i> Sondage</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Autonomie</th>
                                        <th>Technique</th>
                                        <th>Complications</th>
                                        <th>Maîtrise</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(sonde, index) in currentRecord.sonde_followup" :key="'sonde-'+index">
                                        <td>{{ new Date(sonde.date).toLocaleDateString() }}</td>
                                        <td>{{ sonde.autonomy }}</td>
                                        <td>{{ sonde.technique }}</td>
                                        <td>{{ sonde.complications || '-' }}</td>
                                        <td>{{ sonde.mastery }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Ajoutez des sections similaires pour stomie et plaies -->
                    
                </div>
            </div>
        </div>
        
        <!-- Indicateur de page (seulement visible dans le formulaire) -->
        <div v-if="viewMode === 'form'" class="page-indicator">
            <div class="page-dot" :class="{'active': currentPage === 1}"></div>
            <div class="page-dot" :class="{'active': currentPage === 2}"></div>
            <div class="page-dot" :class="{'active': currentPage === 3}"></div>
        </div>
        
        <!-- Bouton flottant pour créer un nouveau dossier -->
        <div v-if="viewMode === 'records'" class="floating-btn" @click="viewMode = 'form'; currentPage = 1">
            <i class="fas fa-plus"></i>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            viewMode: 'form', // 'form', 'records', 'record-detail'
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
                stomie: false,
                wound: false,
                irrigation: false
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
            recordId: null,
            records: [],
            currentRecord: null,
            searchQuery: '',
            loading: false
        };
    },
    computed: {
        filteredRecords() {
            if (!this.searchQuery) return this.records;
            const query = this.searchQuery.toLowerCase();
            return this.records.filter(record => 
                record.patient_name.toLowerCase().includes(query)
            );
        }
    },
    methods: {
        // Méthodes pour le formulaire
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
            const requiredFields = [
                { field: 'name', message: 'Le nom du patient est requis' },
                { field: 'birthDate', message: 'La date de naissance est requise' },
                { field: 'startDate', message: 'La date de prise en charge est requise' },
                { field: 'deliveryDate', message: 'La date de livraison est requise' },
                { field: 'prescriber', message: 'Le prescripteur est requis' },
                { field: 'phone', message: 'Le téléphone est requis' },
            ];
            
            requiredFields.forEach(({ field, message }) => {
                if (!this.patientInfo[field]) {
                    this.errors[field] = message;
                    isValid = false;
                }
            });
            
            // Validation des équipements
            this.equipments.forEach((equipment, index) => {
                if (!equipment.material) {
                    this.errors[`equipment_${index}_material`] = "Le matériel est requis";
                    isValid = false;
                }
                if (!equipment.frequency) {
                    this.errors[`equipment_${index}_frequency`] = "La fréquence est requise";
                    isValid = false;
                }
                if (!equipment.supplier) {
                    this.errors[`equipment_${index}_supplier`] = "Le fournisseur est requis";
                    isValid = false;
                }
            });
            
            if (isValid) {
                this.currentPage = 3;
            }
            
            return isValid;
        },
        
        async saveForm() {
            // Validation supplémentaire pour la page 3
            let isValid = true;
            this.errors = {};
            
            // Validation des sondes
            this.sondeFollowup.forEach((sonde, index) => {
                if (!sonde.date) {
                    this.errors[`sonde_${index}_date`] = "La date est requise";
                    isValid = false;
                }
                if (!sonde.technique) {
                    this.errors[`sonde_${index}_technique`] = "La technique est requise";
                    isValid = false;
                }
            });
            
            // Validation des stomies
            this.stomaFollowup.forEach((stoma, index) => {
                if (!stoma.date) {
                    this.errors[`stoma_${index}_date`] = "La date est requise";
                    isValid = false;
                }
                if (!stoma.problem) {
                    this.errors[`stoma_${index}_problem`] = "Le problème rencontré est requis";
                    isValid = false;
                }
            });
            
            // Validation des plaies
            this.woundFollowup.forEach((wound, index) => {
                if (!wound.date) {
                    this.errors[`wound_${index}_date`] = "La date est requise";
                    isValid = false;
                }
                if (!wound.location) {
                    this.errors[`wound_${index}_location`] = "La localisation est requise";
                    isValid = false;
                }
                if (!wound.products) {
                    this.errors[`wound_${index}_products`] = "Les produits utilisés sont requis";
                    isValid = false;
                }
            });
            
            if (!isValid) return;
            
            try {
                const response = await axios.post('/records', {
                    patientInfo: this.patientInfo,
                    careTypes: this.careTypes,
                    equipments: this.equipments,
                    sondeFollowup: this.sondeFollowup,
                    stomaFollowup: this.stomaFollowup,
                    woundFollowup: this.woundFollowup
                });
                
                this.recordId = response.data.recordId;
                alert("Dossier médical enregistré avec succès !");
                
                // Réinitialiser le formulaire après enregistrement
                this.resetForm();
            } catch (error) {
                console.error("Erreur lors de l'enregistrement:", error);
                alert("Une erreur est survenue lors de l'enregistrement.");
            }
        },
        
        resetForm() {
            this.patientInfo = {
                name: '',
                birthDate: '',
                startDate: '',
                deliveryDate: '',
                prescriber: '',
                phone: ''
            };
            this.careTypes = {
                intermittent: false,
                stomie: false,
                wound: false,
                irrigation: false
            };
            this.equipments = [
                { startDate: '', material: '', frequency: '', supplier: '' }
            ];
            this.sondeFollowup = [
                { date: '', autonomy: 'complète', technique: '', complications: '', mastery: 'bonne' }
            ];
            this.stomaFollowup = [
                { date: '', type: 'colostomie', skinState: 'bon', problem: '', comments: '' }
            ];
            this.woundFollowup = [
                { date: '', type: 'chronique', location: '', evolution: 'stabilisation', products: '', comments: '' }
            ];
            this.currentPage = 1;
        },
        
        exportPdf() {
            if (!this.recordId) {
                alert("Veuillez d'abord enregistrer le dossier");
                return;
            }
            
            window.location.href = `/records/${this.recordId}/export-pdf`;
        },
        
        // Méthodes pour la liste des dossiers
        async fetchRecords() {
            this.loading = true;
            try {
                const response = await axios.get('/records');
                this.records = response.data;
            } catch (error) {
                console.error("Erreur lors du chargement des dossiers:", error);
                alert("Une erreur est survenue lors du chargement des dossiers.");
            } finally {
                this.loading = false;
            }
        },
        
        viewRecord(record) {
            this.currentRecord = record;
            this.viewMode = 'record-detail';
        },
        
        exportRecordPdf(recordId) {
            window.location.href = `/records/${recordId}/export-pdf`;
        }
    },
    mounted() {
        // Charger les dossiers si l'utilisateur accède directement à cette vue
        if (this.viewMode === 'records') {
            this.fetchRecords();
        }
    }
};
</script>

<style scoped>
/* Styles spécifiques au composant */
.app-container {
    display: flex;
    flex-direction: column;
    height: 100vh;
    width: 100%;
    background: white;
    overflow: hidden;
}

header {
    background: linear-gradient(135deg, #ffb300 0%, #ffa000 100%);
    color: white;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    flex-shrink: 0;
}

.logo {
    font-weight: 700;
    font-size: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 15px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

.status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #4caf50;
}

.app-title {
    text-align: center;
    padding: 25px 20px;
    background: #fffde7;
    border-bottom: 1px solid #ffe0b2;
    flex-shrink: 0;
}

.app-title h1 {
    font-size: 32px;
    color: #5d4037;
    margin-bottom: 5px;
}

.app-title h2 {
    font-size: 20px;
    font-weight: 500;
    color: #8d6e63;
}

.navigation {
    display: flex;
    justify-content: center;
    padding: 15px;
    background: #fff8e1;
    border-bottom: 1px solid #ffe0b2;
    gap: 15px;
}

.btn-nav {
    padding: 12px 25px;
    border-radius: 8px;
    background: #f5f5f5;
    color: #5d4037;
    border: 1px solid #e0e0e0;
    transition: all 0.3s;
}

.btn-nav.active {
    background: #ffb300;
    color: white;
    border-color: #ffb300;
}

.content-container {
    flex: 1;
    overflow: hidden;
    display: flex;
    background: #fff9c4;
}

.content {
    flex: 1;
    overflow-y: auto;
    padding: 30px;
    display: flex;
    flex-direction: column;
}

.welcome-screen {
    text-align: center;
    padding: 40px 0;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.welcome-icon {
    font-size: 100px;
    color: #ffb300;
    margin-bottom: 20px;
}

.welcome-screen h3 {
    font-size: 28px;
    margin-bottom: 30px;
    color: #5d4037;
}

.welcome-screen p {
    margin-bottom: 40px;
    max-width: 700px;
    font-size: 18px;
    line-height: 1.8;
}

.form-section {
    margin-bottom: 30px;
    background: #fffde7;
    padding: 25px;
    border-radius: 10px;
    border-left: 5px solid #ffb300;
    box-shadow: 0 4px 8px rgba(141, 110, 99, 0.1);
}

.form-section h3 {
    font-size: 24px;
    margin-bottom: 25px;
    color: #5d4037;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 2px solid #ffd54f;
    padding-bottom: 10px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #6d4c41;
    font-size: 17px;
}

.form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 14px;
    border: 1px solid #ffe0b2;
    border-radius: 8px;
    font-size: 16px;
    background: #fff8e1;
    transition: all 0.3s;
}

.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    outline: none;
    border-color: #ffb300;
    box-shadow: 0 0 0 3px rgba(255, 179, 0, 0.2);
}

.error {
    color: #e53935;
    font-size: 14px;
    margin-top: 5px;
    display: block;
}

.required {
    color: #e53935;
}

.checkbox-group {
    margin: 20px 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.checkbox-item {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    background: #fff8e1;
    padding: 12px 15px;
    border-radius: 8px;
    transition: all 0.3s;
}

.checkbox-item:hover {
    background: #ffecb3;
}

.checkbox-item input {
    margin-right: 12px;
    width: 20px;
    height: 20px;
    accent-color: #ffb300;
}

.checkbox-item label {
    font-size: 16px;
    color: #5d4037;
    font-weight: 500;
}

.table-container {
    overflow-x: auto;
    max-height: 60vh;
    display: block;
    margin: 20px 0;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    min-width: 800px;
}

th, td {
    border: 1px solid #ffe0b2;
    padding: 14px 16px;
    text-align: left;
}

th {
    background-color: #ffb300;
    color: #5d4037;
    font-weight: 600;
    font-size: 16px;
}

tr:nth-child(even) {
    background-color: #fff8e1;
}

table input, table select, table textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ffe0b2;
    border-radius: 4px;
    background: #fff8e1;
}

table input:focus, table select:focus, table textarea:focus {
    outline: none;
    border-color: #ffb300;
}

.btn {
    padding: 14px 28px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 17px;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #ffb300 0%, #ff8f00 100%);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #ff8f00 0%, #ff6f00 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

.btn-secondary {
    background: linear-gradient(135deg, #8d6e63 0%, #6d4c41 100%);
    color: white;
    margin-right: 10px;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #6d4c41 0%, #4e342e 100%);
    transform: translateY(-2px);
}

.btn-add {
    background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);
    color: white;
    margin-top: 15px;
    padding: 12px 20px;
}

.btn-add:hover {
    background: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%);
}

.btn-delete {
    background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
    color: white;
    padding: 8px 14px;
    font-size: 14px;
    border-radius: 6px;
}

.btn-delete:hover {
    background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
}

.btn-export {
    background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
    color: white;
}

.footer-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
    padding-top: 25px;
    border-top: 2px solid #ffe0b2;
}

.page-indicator {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 100;
}

.page-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #bdbdbd;
    transition: all 0.3s;
}

.page-dot.active {
    background: #ffb300;
    transform: scale(1.2);
}

.floating-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ffb300 0%, #ff8f00 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 6px 15px rgba(255, 179, 0, 0.4);
    cursor: pointer;
    z-index: 100;
    transition: all 0.3s;
}

.floating-btn:hover {
    transform: scale(1.1);
}

.summary-box {
    background: linear-gradient(135deg, #fffde7 0%, #fff8e1 100%);
    border-radius: 10px;
    padding: 20px;
    margin-top: 30px;
    border: 1px solid #ffd54f;
}

.section-description {
    font-style: italic;
    color: #8d6e63;
    margin-bottom: 15px;
    padding-left: 10px;
    border-left: 3px solid #ffb300;
}

/* Styles pour la liste des dossiers */
.records-container {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.records-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #ffb300;
}

.records-header h3 {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #5d4037;
}

.search-box {
    position: relative;
    width: 300px;
}

.search-box input {
    width: 100%;
    padding: 12px 15px 12px 40px;
    border: 1px solid #ffe0b2;
    border-radius: 30px;
    background: #fff8e1;
    font-size: 16px;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #8d6e63;
}

.records-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.record-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s;
}

.record-card:hover {
    transform: translateY(-5px);
}

.record-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #fff8e1;
    border-bottom: 1px solid #ffe0b2;
}

.patient-info h4 {
    font-size: 18px;
    color: #5d4037;
    margin-bottom: 5px;
}

.patient-info p {
    font-size: 14px;
    color: #8d6e63;
}

.record-actions {
    display: flex;
    gap: 10px;
}

.btn-view {
    background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
    color: white;
    padding: 8px 15px;
    font-size: 14px;
}

.record-summary {
    padding: 15px;
    font-size: 14px;
}

.record-summary p {
    margin-bottom: 8px;
    color: #5d4037;
}

.loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 50px;
    color: #5d4037;
    font-size: 18px;
    gap: 15px;
}

.no-records {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 50px;
    text-align: center;
    color: #8d6e63;
}

.no-records i {
    font-size: 60px;
    margin-bottom: 20px;
    color: #bdbdbd;
}

.no-records p {
    font-size: 18px;
    margin-bottom: 30px;
}

/* Styles pour le détail du dossier */
.record-detail-container {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid #ffb300;
}

.detail-header h3 {
    color: #5d4037;
    flex: 1;
    text-align: center;
}

.btn-back {
    background: linear-gradient(135deg, #8d6e63 0%, #6d4c41 100%);
    color: white;
}

.detail-content {
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
}

.detail-section {
    margin-bottom: 30px;
    background: #fffde7;
    padding: 25px;
    border-radius: 10px;
    border-left: 5px solid #ffb300;
}

.detail-section h4 {
    font-size: 22px;
    margin-bottom: 25px;
    color: #5d4037;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 2px solid #ffd54f;
    padding-bottom: 10px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.detail-grid > div {
    margin-bottom: 15px;
}

.detail-grid label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #6d4c41;
    font-size: 16px;
}

.detail-grid p {
    padding: 10px;
    background: #fff8e1;
    border-radius: 8px;
    border: 1px solid #ffe0b2;
    min-height: 44px;
}

@media (max-width: 768px) {
    .form-grid, .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .records-list {
        grid-template-columns: 1fr;
    }
    
    .records-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .search-box {
        width: 100%;
    }
    
    .footer-buttons {
        flex-direction: column;
        gap: 15px;
    }
    
    .btn {
        width: 100%;
    }
    
    .detail-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}
</style>