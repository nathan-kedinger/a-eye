<script setup lang="ts">
import { ref, onBeforeMount } from "vue";
import { User } from "../modules/security/type/User";
import { Message } from "../modules/message/type/Message";
import { getAuthenticatedUser } from "../modules/security/api/Authentication";
import { getMessages } from "../modules/message/api/Message";
import { useChatStore } from "../modules/chat/store/ChatStore";

const chatStore = useChatStore();

const props = defineProps<{
  robId: number;
}>();

console.log("tets")

const messages = ref<Message[]>([]);
const me = ref<User>();
const isLoading = ref(false);
const messageText = ref("");

onBeforeMount(async () => {
  isLoading.value = true;
  try {
    me.value = await getAuthenticatedUser();
    messages.value = await getMessages(13);
  } catch (error) {
    console.error("Error loading chat data:", error);
  } finally {
    isLoading.value = false;
  }
  console.log("messages : ",messages.value);
});

/*const sendMessage = async () => {
  if (messageText.value.trim() === "") return;
  const newMessage: Message = {
    id: Date.now(),
    content: messageText.value,
    sentByHuman: true,
    timeStamp: new Date().toISOString(),
    read: false,
  };
  messages.value.push(newMessage);
  messageText.value = "";
};*/
</script>


<template>
  <div class="flex-1 p-2 sm:p-6 justify-between flex flex-col h-screen bg-blue-50">
    <!-- En-tête -->
    <div class="flex sm:items-center justify-between py-3 border-b-2 border-gray-200">
      <div class="relative flex items-center space-x-4">
        <div class="relative">
          <span class="absolute text-green-500 right-0 bottom-0">
            <svg width="20" height="20">
              <circle cx="8" cy="8" r="8" fill="currentColor"></circle>
            </svg>
          </span>
          <img
              src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=3&w=144&h=144"
              alt="Rob Avatar"
              class="w-10 sm:w-16 h-10 sm:h-16 rounded-full"
          />
        </div>
        <div class="flex flex-col leading-tight">
          <div class="text-2xl mt-1 flex items-center">
          </div>
        </div>
      </div>
      <div class="flex items-center space-x-2">
        <button type="button" class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
          <span>Close</span>
        </button>
      </div>
    </div>

    <!-- Messages -->
    <div id="messages" class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
      <div
          v-for="message in messages"
          :key="message.id"
          class="chat-message"
      >
        <div :class="message.sentByHuman ? 'flex items-end justify-end' : 'flex items-end'">
          <div
              class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1"
              :class="message.sentByHuman ? 'items-end' : 'items-start'"
          >
            <div>
              <span
                  class="px-4 py-2 rounded-lg inline-block"
                  :class="message.sentByHuman ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-300 text-gray-600 rounded-bl-none'"
              >
                {{ message.content }}
              </span>
            </div>
          </div>
          <img
              src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=3&w=144&h=144"
              alt="User Avatar"
              class="w-6 h-6 rounded-full"
          />
        </div>
      </div>
    </div>

    <!-- Champ de texte -->
    <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0">
      <div class="relative flex">
        <input
            v-model="messageText"
            type="text">
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>