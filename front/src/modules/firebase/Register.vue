<script setup lang="ts">
import { ref } from "vue";
import {
  getAuth,
  createUserWithEmailAndPassword,
  GoogleAuthProvider,
  signInWithPopup,
} from "firebase/auth";
import { useRouter } from "vue-router";

const email = ref<string>("");
const password = ref<string>("");
const router = useRouter();
const user = ref(null);
const register = () => {
  createUserWithEmailAndPassword(getAuth(), email.value, password.value)
      .then((data) => {
        console.log(data);
        console.log("Enregistrement réussi");
        router.push("/");
      })
      .catch((error) => {
        console.log(error.code);
        alert(error.message);
      });
};

const signInWithGoogle = () => {
  const provider = new GoogleAuthProvider();
  signInWithPopup(getAuth(), provider)
      .then((result) => {
        router.push("/");
      })
      .catch((error) => {
        console.log(error.code);
        alert(error.message);
      });
};

</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
      <h1 class="text-2xl font-bold text-center text-gray-800 mb-4">
        Créer un compte
      </h1>
      <div class="space-y-4">
        <!-- Email Input -->
        <div>
          <input
              type="text"
              placeholder="Courriel"
              v-model="email"
              class="input input-bordered w-full"
          />
        </div>
        <!-- Password Input -->
        <div>
          <input
              type="password"
              placeholder="Mot de passe"
              v-model="password"
              class="input input-bordered w-full"
          />
        </div>
        <!-- Buttons -->
        <div class="space-y-2">
          <button
              @click="register"
              class="btn btn-primary w-full"
          >
            Register
          </button>
          <button
              @click="signInWithGoogle"
              class="btn btn-outline w-full"
          >
            Register with Google
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>

