<script setup lang="ts">
import {onBeforeMount, ref, watch} from "vue";
import {getConversationsList, postConversation} from "./api/Conversation";
import type {Conversation} from "./type/Conversation";
import { PlusIcon } from '@heroicons/vue/24/solid'

const conversationsList = ref<Conversation[]>()
//const lastUpdateConversation = ref<Conversation>()
const props = defineProps({
  robConversations: {
    require: true
  }
})
const newConversation = ref<Conversation>()
const emit = defineEmits<(event: "conversation", conversation: Conversation) => void>();
const loadConversationList = async (robIdForConversations: number) => {
  console.log(props.robConversations.id)
  conversationsList.value = await getConversationsList(props.robConversations.id)
  //lastUpdateConversation.value = conversationsList.value[0]
}
const newConversationTitle = ref<string>('')



const createConversation = () => {
  newConversation.value = {
    title: newConversationTitle.value,
    rob: props.robConversations['@id']
  }
  // Actualiser la liste des conversations
  postConversation(newConversation.value)
}
onBeforeMount(async () => {
  await loadConversationList(props.robConversations.id)
})

watch(() => props.robConversations.id,async (newVal) => {
  conversationsList.value = await getConversationsList(newVal)
});
const handleConversationClick = (conversation : Conversation) => {
  emit("conversation", conversation)
}
const handleCreateNewConversation = async () => {
  createConversation()

  await loadConversationList(props.robConversations.id)

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
        <li>
          <button onclick="new_conversation.showModal()" class="btn bg-blue-800">           <PlusIcon class="w-6 h6"></PlusIcon>
            New chat</button>
        </li>
        Sidebar content here
        <li @click="handleConversationClick(conversation)" v-for="conversation in conversationsList" :key="conversation.id">
          <a>{{ conversation.title }} {{ conversation.lastUpdate }}</a>
        </li>
      </ul>
    </div>
    <dialog id="new_conversation" class="modal">
      <div class="modal-box">
        <h3 class="text-lg font-bold">Hello!</h3>
        <p class="py-4">Press ESC key or click the button below to close</p>
        <div class="modal-action">
          <form method="dialog">
            <label for="title">Choose a title for this conversation
              <input name="title" id="title" v-model="newConversationTitle">
            </label>
            <button class="btn" @click="handleCreateNewConversation">Create</button>
            <!-- if there is a button in form, it will close the modal -->
            <button class="btn">Close</button>
          </form>
        </div>
      </div>
    </dialog>
  </div>
</template>

<style scoped>

</style>