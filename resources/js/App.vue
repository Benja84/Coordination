<template>
    <div class="app-container">
        <!-- En-tête de l'application -->
        <!--<header>
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
        </header>-->
        
        <!-- Titre de l'application -->
        <div class="app-title">
            <h1>La coordination</h1>
            <h2>Fiche de Suivi - Sondage Intermittent / Stomie / Soins des Plaies</h2>
        </div>
        
        <!-- Navigation entre les pages -->
        <div class="navigation" :style="navStyle">
            <button class="btn btn-nav" :class="{'active': viewMode === 'form'}" @click="viewMode = 'form'">
                <i class="fas fa-file-medical"></i> Nouveau dossier
            </button>
            <button class="btn btn-nav" :class="{'active': viewMode === 'records'}" @click="viewMode = 'records'; fetchRecords()">
                <i class="fas fa-list"></i> Dossiers enregistrés
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
                                        <!--<th>Date de début</th>-->
                                        <th>Matériel prescrit</th>
                                        <th>Fréquence d'utilisation</th>
                                        <th>Fournisseur / Marque</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(equipment, index) in equipments" :key="index">
                                        <!--<td><input type="date" v-model="equipment.startDate"></td>-->
                                        <td><input type="text" v-model="equipment.material" placeholder="Ex: Sondes urinaires" required></td>
                                        <td><input type="text" v-model="equipment.frequency" placeholder="Ex: 3 fois/jour" required></td>
                                        <td><input type="text" v-model="equipment.supplier" placeholder="Ex: Urimed" required></td>
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
                    <div class="form-section-title">
                        <h3><i class="fas fa-clipboard-check"></i> Suivi / Évaluation du patient</h3>
                        <!--<p class="section-description">
                            Utilisez les tableaux ci-dessous pour enregistrer les évaluations de suivi.
                            Vous pouvez ajouter autant d'entrées que nécessaire pour chaque type de soin.
                        </p>-->
                    </div>
                    
                    <!-- Sondage Section -->
                    <div class="form-section" v-if="careTypes.intermittent">
                        <h3><i class="fas fa-catheter"></i> Sondage</h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <!--<th>Date</th>-->
                                        <th>Autonomie</th>
                                        <th>Technique</th>
                                        <th>Complications observées</th>
                                        <th>Maîtrise ?</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(sonde, index) in sondeFollowup" :key="'sonde-'+index">
                                        <!--<td><input type="date" v-model="sonde.date" required></td>-->
                                        <td>
                                            <select v-model="sonde.autonomy" required>
                                                <option value="Complète">Complète</option>
                                                <option value="Partielle">Partielle</option>
                                                <option value="Assistée">Assistée</option>
                                            </select>
                                        </td>
                                        <td><textarea v-model="sonde.technique" rows="2" placeholder="Décrire la technique" required></textarea></td>
                                        <td><textarea v-model="sonde.complications" rows="2" placeholder="Décrire les complications"></textarea></td>
                                        <td>
                                            <select v-model="sonde.mastery" required>
                                                <option value="Excellente">Excellente</option>
                                                <option value="Bonne">Bonne</option>
                                                <option value="Moyenne">Moyenne</option>
                                                <option value="Faible">Faible</option>
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
                        <!--<button class="btn btn-add" @click="addSonde">
                            <i class="fas fa-plus-circle"></i> Ajouter une évaluation de sondage
                        </button>-->
                    </div>
                    
                    <!-- Stomie Section -->
                    <div class="form-section" v-if="careTypes.stomie">
                        <h3><i class="fas fa-stomach"></i> Stomie</h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <!--<th>Date</th>-->
                                        <th>Type de stomie</th>
                                        <th>État de la peau</th>
                                        <th>Problème rencontré</th>
                                        <th>Commentaires</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(stoma, index) in stomaFollowup" :key="'stoma-'+index">
                                        <!--<td><input type="date" v-model="stoma.date" required></td>-->
                                        <td>
                                            <select v-model="stoma.type" required>
                                                <option value="Iléostomie">Iléostomie</option>
                                                <option value="Colostomie">Colostomie</option>
                                                <option value="Urostomie">Urostomie</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select v-model="stoma.skinState" required>
                                                <option value="Excellent">Excellent</option>
                                                <option value="Bon">Bon</option>
                                                <option value="Irritation">Irritation</option>
                                                <option value="Inflammation">Inflammation</option>
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
                        <!--<button class="btn btn-add" @click="addStoma">
                            <i class="fas fa-plus-circle"></i> Ajouter une évaluation de stomie
                        </button>-->
                    </div>
                    
                    <!-- Plaies Section -->
                    <div class="form-section" v-if="careTypes.wound">
                        <h3><i class="fas fa-bandaid"></i> Plaies</h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <!--<th>Date</th>-->
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
                                        <!--<td><input type="date" v-model="wound.date" required></td>-->
                                        <td>
                                            <select v-model="wound.type" required>
                                                <option value="Aiguë">Aiguë</option>
                                                <option value="Chronique">Chronique</option>
                                                <option value="Pression">Pression</option>
                                                <option value="Diabétique">Diabétique</option>
                                            </select>
                                        </td>
                                        <td><input type="text" v-model="wound.location" placeholder="Ex: Jambe droite" required></td>
                                        <td>
                                            <select v-model="wound.evolution" required>
                                                <option value="Amélioration">Amélioration</option>
                                                <option value="Stabilisation">Stabilisation</option>
                                                <option value="Aggravation">Aggravation</option>
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
                        <!--<button class="btn btn-add" @click="addWound">
                            <i class="fas fa-plus-circle"></i> Ajouter une évaluation de plaie
                        </button>-->
                    </div>
                    <!-- Ajout de la section Observations générales / Suivi à domicile -->
                    <!--<div class="form-section">
                        <h3><i class="fas fa-clipboard-list"></i> 5. Observations générales / Suivi à domicile</h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Observation / Intervention réalisée</th>
                                        <th>Nom du professionnel</th>
                                        <th>Signature</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(observation, index) in generalObservations" :key="'obs-'+index">
                                        <td><textarea v-model="observation.description" rows="2" placeholder="Décrire l'observation ou l'intervention" required></textarea></td>
                                        <td><input type="text" v-model="observation.professional" placeholder="Nom du professionnel" required></td>
                                        <td><input type="text" v-model="observation.signature" placeholder="Signature" required></td>
                                        <td>
                                            <button class="btn btn-delete" @click="removeObservation(index)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-add" @click="addObservation">
                            <i class="fas fa-plus-circle"></i> Ajouter une observation
                        </button>
                    </div>-->
                    
                    <div class="summary-box">
                        <h4><i class="fas fa-file-medical-alt"></i> Résumé du dossier</h4>
                        <p><strong>Patient:</strong> {{ patientInfo.name || 'Non renseigné' }}</p>
                        <p><strong>Prise en charge:</strong> 
                            <span v-if="careTypes.intermittent">Sondage intermittent ,</span>
                            <span v-if="careTypes.stomie">Stomie , </span>
                            <span v-if="careTypes.wound">Soins des plaies ,</span>
                            <span v-if="careTypes.irrigation">Irrigation trans-anale</span>
                            <span v-if="!careTypes.intermittent && !careTypes.stomie && !careTypes.wound && !careTypes.irrigation">Aucun</span>
                        </p>
                        <p><strong>Appareillage:</strong> {{ equipments.length }} appareil(s) prescrit(s)</p>
                        <!--<p><strong>Évaluations:</strong> 
                            <span v-if="careTypes.intermittent">{{ sondeFollowup.length }} sondage(s), </span>
                            <span v-if="careTypes.stomie">{{ stomaFollowup.length }} stomie(s), </span>
                            <span v-if="careTypes.wound">{{ woundFollowup.length }} plaie(s)</span>
                        </p>-->
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
                    <h3><i class="fas fa-folder-open"></i> Dossiers médicaux enregistrés</h3>
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
                            <p><strong>Prise en charge: </strong> 
                                <span v-if="record.care_types.intermittent">Sondage , </span>
                                <span v-if="record.care_types.stomie">Stomie , </span>
                                <span v-if="record.care_types.wound">Plaies , </span>
                                <span v-if="record.care_types.irrigation">Irrigation</span>
                            </p>
                            <!--<p><strong>Évaluations:</strong> 
                                <span>{{ record.sonde_followup.length }} sondages,</span>
                                <span>{{ record.stoma_followup.length }} stomies,</span>
                                <span>{{ record.wound_followup.length }} plaies</span>
                            </p>-->
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
                    

                    <div class="detail-section" v-if="currentRecord.sonde_followup && currentRecord.sonde_followup.length > 0">
                        <h4><i class="fas fa-catheter"></i> Sondage</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <!--<th>Date</th>-->
                                        <th>Autonomie</th>
                                        <th>Technique</th>
                                        <th>Complications</th>
                                        <th>Maîtrise</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(sonde, index) in currentRecord.sonde_followup" :key="'sonde-'+index">
                                        <!--<td>{{ new Date(sonde.date).toLocaleDateString() }}</td>-->
                                        <td>{{ sonde.autonomy }}</td>
                                        <td>{{ sonde.technique }}</td>
                                        <td>{{ sonde.complications || '-' }}</td>
                                        <td>{{ sonde.mastery }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="detail-section" v-if="currentRecord.stoma_followup && currentRecord.stoma_followup.length > 0">
                        <h4><i class="fas fa-catheter"></i> Stoma</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <!--<th>Date</th>-->
                                        <th>Type de stomie</th>
                                        <th>Etat de la peau</th>
                                        <th>Complications</th>
                                        <th>Commentaires</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(stoma, index) in currentRecord.stoma_followup " :key="'stoma-'+index">
                                        <!--<td>{{ new Date(sonde.date).toLocaleDateString() }}</td>-->
                                        <td>{{ stoma.type }}</td>
                                        <td>{{ stoma.skinState }}</td>
                                        <td>{{ stoma.problem || '-' }}</td>
                                        <td>{{ stoma.comments }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="detail-section" v-if="currentRecord.wound_followup && currentRecord.wound_followup.length > 0">
                        <h4><i class="fas fa-catheter"></i> Plaies</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <!--<th>Date</th>-->
                                        <th>Type de plaie</th>
                                        <th>Localisation</th>
                                        <th>Evolution</th>
                                        <th>Produits utilisés</th>
                                        <th>Commentaires</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(wound, index) in currentRecord.wound_followup " :key="'stoma-'+index">
                                        <!--<td>{{ new Date(sonde.date).toLocaleDateString() }}</td>-->
                                        <td>{{ wound.type }}</td>
                                        <td>{{ wound.location }}</td>
                                        <td>{{ wound.evolution }}</td>
                                        <td>{{ wound.products }}</td>
                                        <td>{{ wound.comments }}</td>
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
                // { startDate: '', material: '', frequency: '', supplier: '' }
                { material: '', frequency: '', supplier: '' }
            ],
            sondeFollowup: [
                // { date: '', autonomy: 'complète', technique: '', complications: '', mastery: 'bonne' }
                { autonomy: 'Complète', technique: '', complications: '', mastery: 'Bonne' }
            ],
            stomaFollowup: [
                // { date: '', type: 'colostomie', skinState: 'bon', problem: '', comments: '' }
                { type: 'Colostomie', skinState: 'Bon', problem: '', comments: '' }
            ],
            woundFollowup: [
                // { date: '', type: 'chronique', location: '', evolution: 'stabilisation', products: '', comments: '' }
                { type: 'Chronique', location: '', evolution: 'Stabilisation', products: '', comments: '' }
            ],
            observation:[
                { description:'', professional: '', signature:'' }
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
                autonomy: 'Complète',
                technique: '',
                complications: '',
                mastery: 'Bonne'
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
                type: 'Colostomie',
                skinState: 'Bon',
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
                type: 'Chronique',
                location: '',
                evolution: 'Stabilisation',
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
        // addObservation() {
        //     this.generalObservations.push({
        //         description: '',
        //         professional: '',
        //         signature: ''
        //     });
        // },
        
        // removeObservation(index) {
        //     if (this.generalObservations.length > 1) {
        //         this.generalObservations.splice(index, 1);
        //     } else {
        //         alert("Au moins une observation doit être présente");
        //     }
        // },
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
                    toastr.error(message);
                    isValid = false;
                }
            });
            
            // Validation des équipements
            this.equipments.forEach((equipment, index) => {
                if (!equipment.material) {
                    this.errors[`equipment_${index}_material`] = "Le matériel est requis";
                    toastr.error('Le matériel est requis','Erreur !');
                    isValid = false;
                }
                if (!equipment.frequency) {
                    this.errors[`equipment_${index}_frequency`] = "La fréquence est requise";
                    toastr.error('La fréquence est requise','Erreur !');
                    isValid = false;
                }
                if (!equipment.supplier) {
                    this.errors[`equipment_${index}_supplier`] = "Le fournisseur est requis";
                    toastr.error('Le fournisseur est requis','Erreur !');
                    
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
            if(this.careTypes.intermittent){
                this.sondeFollowup.forEach((sonde, index) => {
                    // if (!sonde.date) {
                    //     this.errors[`sonde_${index}_date`] = "La date est requise";
                    //     isValid = false;
                    // }
                    if (!sonde.technique) {
                        this.errors[`sonde_${index}_technique`] = "La technique est requise";
                        toastr.error('La technique est requise','Erreur !');
                        isValid = false;
                    }
                });
            }
            
            // Validation des stomies
            if(this.careTypes.stomie){
                this.stomaFollowup.forEach((stoma, index) => {
                    // if (!stoma.date) {
                    //     this.errors[`stoma_${index}_date`] = "La date est requise";
                    //     isValid = false;
                    // }
                    if (!stoma.problem) {
                        this.errors[`stoma_${index}_problem`] = "Le problème rencontré est requis";
                        toastr.error('Le problème rencontré est requis','Erreur !');
                        isValid = false;
                    }
                });
            }
            
            // Validation des plaies
            if(this.careTypes.wound){
                this.woundFollowup.forEach((wound, index) => {
                    // if (!wound.date) {
                    //     this.errors[`wound_${index}_date`] = "La date est requise";
                    //     isValid = false;
                    // }
                    if (!wound.location) {
                        this.errors[`wound_${index}_location`] = "La localisation est requise";
                        toastr.error('La localisation est requise','Erreur !');
                        isValid = false;
                    }
                    if (!wound.products) {
                        this.errors[`wound_${index}_products`] = "Les produits utilisés sont requis";
                        toastr.error('Les produits utilisés sont requis','Erreur !');
                        isValid = false;
                    }
                });
            }
            
            if (!isValid) return;
            
            try {
                const response = await axios.post('/records', {
                    patientInfo: this.patientInfo,
                    careTypes: this.careTypes,
                    equipments: this.equipments,
                    sondeFollowup: this.careTypes.intermittent ? this.sondeFollowup : null,
                    stomaFollowup: this.careTypes.stomie ? this.stomaFollowup : null,
                    woundFollowup: this.careTypes.wound ? this.woundFollowup : null
                });
                
                this.recordId = response.data.recordId;
                toastr.success("Dossier médical enregistré avec succès !", 'Succès !');
                
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
                // { startDate: '', material: '', frequency: '', supplier: '' }
                { material: '', frequency: '', supplier: '' }
            ];
            this.sondeFollowup = [
                // { date: '', autonomy: 'Complète', technique: '', complications: '', mastery: 'Bonne' }
                { autonomy: 'Complète', technique: '', complications: '', mastery: 'Bonne' }
            ];
            this.stomaFollowup = [
                // { date: '', type: 'Colostomie', skinState: 'Bon', problem: '', comments: '' }
                { type: 'Colostomie', skinState: 'Bon', problem: '', comments: '' }
            ];
            this.woundFollowup = [
                // { date: '', type: 'Chronique', location: '', evolution: 'Stabilisation', products: '', comments: '' }
                { type: 'Chronique', location: '', evolution: 'Stabilisation', products: '', comments: '' }
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
    /* * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    } */
    /* Styles spécifiques au composant */
    /* .app-container {
        display: flex;
        flex-direction: column;
        height: 100vh;
        width: 100%;
        background: white;
        overflow: hidden;
    } */

    
</style>