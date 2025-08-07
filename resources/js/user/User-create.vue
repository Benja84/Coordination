<template>
  <div class="content-container" id="create-user">

    <form @submit.prevent="createUser" class="content">
      <div class="d-flex">
        <h2 class="create-title">Ajouter un nouvel utilisateur</h2>
        <a class="btn btn-secondary" href="/">Retour à l'accueil</a>
      </div>
      <div class="form-group">
        <label for="lastname">Nom</label>
        <input
          id="lastname"
          type="text"
          v-model="form.lastname"
          class="form-control"
          :class="{'is-invalid': errors.lastname}"
          required
        />
        <div v-if="errors.lastname" class="invalid-feedback">
          {{ errors.lastname[0] }}
        </div>
      </div>

      <div class="form-group">
        <label for="firstname">Prénoms</label>
        <input
          id="firstname"
          type="text"
          v-model="form.firstname"
          class="form-control"
          :class="{'is-invalid': errors.firstname}"
          required
        />
        <div v-if="errors.firstname" class="invalid-feedback">
          {{ errors.firstname[0] }}
        </div>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input
          id="email"
          type="email"
          v-model="form.email"
          class="form-control"
          :class="{'is-invalid': errors.email}"
          required
        />
        <div v-if="errors.email" class="invalid-feedback">
          {{ errors.email[0] }}
        </div>
      </div>
      <div class="form-group">
        <label for="role">Type</label>
        <select id="role" v-model="form.type" :class="{'is-invalid': errors.type}" required >
          <option value="1">Admin</option>
          <option value="0">Utilisateur</option>
        </select>
        <div v-if="errors.type" class="invalid-feedback">
          {{ errors.type[0] }}
        </div>
      </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input
          id="password"
          type="password"
          v-model="form.password"
          class="form-control"
          :class="{'is-invalid': errors.password}"
          required
        />
        <div v-if="errors.password" class="invalid-feedback">
          {{ errors.password[0] }}
        </div>
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirmation mot de passe</label>
        <input
          id="password_confirmation"
          type="password"
          v-model="form.password_confirmation"
          class="form-control"
          :class="{'is-invalid': errors.password_confirmation}"
          required
        />
        <div v-if="errors.password_confirmation" class="invalid-feedback">
          {{ errors.password_confirmation[0] }}
        </div>
      </div>

      <button type="submit" class="btn btn-primary" :disabled="loading">
        {{ loading ? 'Création...' : 'Créer l’utilisateur' }}
      </button>
    </form>
  </div>
</template>

<script>
import { ref, reactive } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
  name: 'CreateUser',
  setup() {
    const router = useRouter();
    const loading = ref(false);
    const form = reactive({
      lastname: '',
      firstname: '',
      email: '',
      password: '',
      password_confirmation: '',
      type: 0,
    });
    const errors = reactive({});

    const createUser = async () => {
      loading.value = true;
      errors.value = {};
      try {
        await axios.post('/users/save', form);
        // Sur succès, rediriger vers la liste des utilisateurs
        window.location.href = '/';
        router.push({ name: 'dashboard' });
      } catch (error) {
        if (error.response && error.response.status === 422) {
          // Erreurs de validation
          toastr.error('Erreur validation');
          Object.assign(errors, error.response.data.errors);
        } else {
          console.error(error);
        }
      } finally {
        loading.value = false;
      }
    };

    return { form, errors, loading, createUser };
  }
};
</script>
<style>
  #create-user{
    padding-left: 10px;
  }
  .d-flex{
    display: flex;
    justify-content: space-between;
  }
  a{
    height: 20px;
  }
  .create-title{
    margin-top: -5px;
    color: #8d6e63;
  }
  .invalid-feedback{
    color: red;
  }
</style>
