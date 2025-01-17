<script setup lang="ts">
import { onMounted, ref } from "vue";
import {getAuth, onAuthStateChanged, signOut} from "firebase/auth";
import router from "../router";
import axios from "axios";
import { HomeIcon } from "@heroicons/vue/24/outline"
const isLoggedIn = ref<boolean>(false)
const auth = ref()
const currentUserPicture = ref<string | null>('https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp')
const logout = {
  url:  "http://localhost:8051/logout",
  method: "POST",
};
const handleSignOut = () => {
  signOut(auth.value).then(() => {
    localStorage.removeItem('token');
    axios({
      method: logout.method,
      url: logout.url,
    }).then(
        (response) => {
          console.log(response)
        }
    ).catch((error) => {
      console.log(error)
    })
    router.push('/register')
  })
}

onMounted(() => {
  auth.value = getAuth()
  onAuthStateChanged(auth.value, (user) => {
    if(user?.photoURL)
    currentUserPicture.value = user?.photoURL
    isLoggedIn.value = !!user;
  })
})
</script>

<template>
  <!--  <div class="navbar bg-base-100 fixed top-0 left-0 w-full z-50 shadow-md">
      <a v-for="page in navbarPagesList" :key="page.page" class="btn btn-ghost text-xl">
        <RouterLink :to="page.path">{{ page.page }}</RouterLink>
      </a>
      <a class="btn btn-ghost text-xl" @click="handleSignOut" v-if="isLoggedIn">Sign out</a>
    </div>-->

  <RouterLink to="/" class="fixed top-5 left-5 z-50"><HomeIcon class="w-6 h-6"/></RouterLink>
  <RouterLink to="/login" class="avatar fixed top-2 end-2 z-50">
    <div class="w-12 rounded-full">
      <img
          :src="isLoggedIn ? currentUserPicture! : 'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp'"
          alt="Tailwind-CSS-Avatar-component" />
    </div>
  </RouterLink>


</template>

<style scoped>

</style>
