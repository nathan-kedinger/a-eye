<script setup lang="ts">
import Header from "./layouts/Header.vue";
import ChatComponent from "./modules/chat/ChatComponent.vue";

</script>

<template>
  <Header></Header>
  <RouterView></RouterView>
  <ChatComponent/>

</template>

<style scoped>
</style>
