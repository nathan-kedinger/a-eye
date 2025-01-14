<script setup lang="ts">

import {ref} from "vue";
import {useAuth} from "vue-auth3";
import axios from "axios";
import {useUserStore} from "../modules/security/store/userStore";
import {getAuthenticatedUser} from "../modules/security/api/Authentication";

const email = ref('')
const password = ref('')
const response = ref('')
const auth = useAuth()
const user = useUserStore()


const storeTokenInCookie = (token: string) => {
  // Stocker le token dans un cookie avec un délai d'expiration (par exemple 30 jours)
  document.cookie = `BEARER=${token}; path=/; expires=${new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toUTCString()}; secure; SameSite=None`;
}

const login = () => {
  const loginData = {
    url: "http://localhost:8050/auth ",
    method: "POST",
    redirect: "/login",
    fetchUser: true,
  };

  const credentials = {
    email: email.value,
    password: password.value,
  };

  axios({
    method: loginData.method,
    url: loginData.url,
    data: credentials,
  })
      .then((response) => {
        const token = response.data.token
        storeTokenInCookie(token);
        if (loginData.fetchUser) {
          getAuthenticatedUser()
          //axios.get('http://localhost:8000/api/me')
              .then(userResponse => {
                user.setUser(userResponse);
              })
              .catch(err => console.error('Erreur lors de la récupération de l\'utilisateur:', err));
        }
        // Gestion de la redirection après login
        //window.location.href = loginData.redirect;
      })
      .catch((error) => {
        console.error('Login échoué:', error);
      })
      .finally(() => {
      })
}
</script>

<template>
  <div>
    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="Email"/>
      <input v-model="password" type="password" placeholder="Password"/>
      <button type="submit">Login</button>
    </form>
  </div>

</template>

<style scoped>

</style>