<script setup lang="ts">
import {onBeforeMount, ref, watch} from "vue";
import {getConversationsList} from "./api/Conversation";
import type {Conversation} from "./type/Conversation";


const conversationsList = ref<Conversation[]>()
const lastUpdateConversation = ref<Conversation>()
const props = defineProps({
      robConversations: {
        require: true
      }
    })

const emit = defineEmits<(event: "conversation", conversation: Conversation) => void>();
const loadConversationList = async (robIdForConversations: number) => {
  conversationsList.value = await getConversationsList(robIdForConversations)
  lastUpdateConversation.value = conversationsList.value[0]
}
onBeforeMount(async () => {
  await loadConversationList(props.robConversations)
})

watch(() => props.robConversations,async (newVal) => {
  conversationsList.value = await getConversationsList(newVal)

});
const handleConversationClick = (conversation : Conversation) => {
  emit("conversation", conversation)
}
</script>

<template>
  <div class="drawer lg:drawer-open">
    <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col items-center justify-center">
      <!-- Page content here -->
      <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden">
        Open drawer
      </label>
    </div>
    <div class="drawer-side">
      <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
      <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4">
        <!-- Sidebar content here -->
        <li @click="handleConversationClick(conversation)" v-for="conversation in conversationsList" :key="conversation.id">
          <a>{{ conversation.title }} {{ conversation.lastUpdate }}</a>
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped>

</style>