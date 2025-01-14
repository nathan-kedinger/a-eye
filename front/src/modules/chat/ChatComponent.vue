<script setup lang="ts">

import ChatWindow from "./ChatWindow.vue";
import {useChatStore} from "./store/ChatStore";
const chatStore = useChatStore();

</script>

<template>
  <div v-if="chatStore.chats.length > 0" v-for="chat in chatStore.chats"
       class="join sticky bottom-0">
    <ChatWindow  :key="chat.id" :rob-id="chat.id" class="w-64 join-item" />
  </div>
</template>

<style scoped>

</style>