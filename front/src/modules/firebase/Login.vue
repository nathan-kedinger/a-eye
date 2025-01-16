<script setup lang="ts">
import {ref} from "vue";
import {getAuth, GoogleAuthProvider, signInWithEmailAndPassword, signInWithPopup} from "firebase/auth";
import {useRouter} from "vue-router";

const email = ref<string>("")
const password = ref<string>("")
const errorMessage = ref<string>("")
const router = useRouter()

const signInWithGoogleEmailAndPassword = () => {
  signInWithEmailAndPassword(getAuth(), email.value, password.value)
      .then((idToken) => {
        localStorage.setItem("token", idToken.user?.uid)
        console.log("sign in succes")
      })
      .catch((error) => {
        console.log(error.code)
        switch (error.code){
          case "auth/invalid-email":
            errorMessage.value = "Invalid email"
            break
          case "auth/user-not-found":
            errorMessage.value = "No account with that email was found"
            break
          case "auth/wrong-password":
            errorMessage.value = "Wrong password"
            break
          default :
            errorMessage.value = "Email or password were incorrect"
            break
        }
        alert(error.message)
      })
  router.push("/feed")
}

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
      <h1 class="text-2xl font-bold text-center text-gray-800 mb-4">Sign In</h1>
      <div class="space-y-4">
        <!-- Email Input -->
        <div>
          <input
              type="text"
              placeholder="Email"
              v-model="email"
              class="input input-bordered w-full"
          />
        </div>
        <!-- Password Input -->
        <div>
          <input
              type="password"
              placeholder="Password"
              v-model="password"
              class="input input-bordered w-full"
          />
        </div>
        <!-- Error Message -->
        <div v-if="errorMessage" class="text-red-500 text-sm">
          {{ errorMessage }}
        </div>
        <!-- Buttons -->
        <div class="space-y-2">
          <button
              @click="signInWithGoogleEmailAndPassword"
              class="btn btn-primary w-full"
          >
            Login
          </button>
          <button
              @click="signInWithGoogle"
              class="btn btn-outline w-full"
          >
            Login with Google
          </button>
          <RouterLink to="/register">
            <button class="btn btn-link w-full text-center">
              Register
            </button>
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>


<style scoped>

</style>