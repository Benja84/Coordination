<template>
    <div class="login-wrapper">
        <form @submit.prevent="login">
            <h2>Connexion</h2>
            <p v-if="error" class="error">{{ error }}</p>
            <div class="form-group">
                <label>Email</label>
                <input v-model="form.email" type="email" required />
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input v-model="form.password" type="password" required />
            </div>

            <button type="submit" class="btn btn-secondary">Se connecter</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
export default {
  data: () => ({
    form: { email: '', password: '' },
    error: '',
  }),
  methods: {
    async login() {
      try {
        await axios.post('/login', this.form);
        window.location.replace('/'); // rechargement complet → Vue affichera l’appli
      } catch (e) {
        this.error = e.response?.data?.message || 'Erreur';
      }
    },
    getCookie(name) {
      return document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)')?.pop() || '';
    },
  },
};
</script>

<style scoped>
.login-wrapper {
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: white;
}
form {
  background: white;
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,.1);
  width: 320px;
}
h2 {
  margin-bottom: 25px;
  color: #5d4037;
  text-align: center;
}
.form-group {
  margin-bottom: 15px;
}
label {
  display: block;
  margin-bottom: 5px;
  color: #6d4c41;
}
input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ffe0b2;
  border-radius: 6px;
}
.error {
  margin-top: 10px;
  color: #e53935;
  text-align: center;
}
button.btn-secondary {
  display: block;         /* occupe toute la largeur dispo */
  margin: 20px auto 0;    /* 20 px d’espace + centrage horizontal */
}
</style>