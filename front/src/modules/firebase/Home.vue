<script setup lang="ts">
import {onMounted, ref} from "vue";
import type { User } from "../security/type/User.ts";
import {getAuthenticatedUser} from "../security/api/Authentication.ts";

  const currentUser = ref<User | null>(null)
  onMounted(async () => {
    currentUser.value = await getAuthenticatedUser()
    console.log("current user : ", currentUser.value);
  })
</script>

<template>

</template>

<style scoped>

</style>