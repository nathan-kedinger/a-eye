<script setup lang="ts">
import {onBeforeMount, ref, watch} from "vue";
import type {Message} from "../message/type/Message";
import CardTitle from "../../components/utils/cards/CardTitle.vue";
import Card from "../../components/utils/cards/Card.vue";
import CardBody from '../../components/utils/cards/CardBody.vue';
import {InformationCircleIcon, MinusIcon, UserCircleIcon, PaperAirplaneIcon} from "@heroicons/vue/24/outline"
import type {Conversation} from "../conversations/type/Conversation";
import {getConversation, getLatestConversation} from "../conversations/api/Conversation";
import {getMessages, postMessage} from "../message/api/Message.ts";
import type {Rob} from "../Rob/type/Rob.ts";
const windowOpen = defineModel<boolean>()
const messageBody = ref<string>("")
const messages = ref<Message[]>([])

const conversation = ref<Conversation | null>(null)
const isLoading = ref(false)

const props = defineProps({
  selectedConversation: {
    type: Object as () => Conversation,
    required: true
  },
  selectedRob: {
    type: Object as () => Rob,
    required: true
  }
});

onBeforeMount(async () => {
  isLoading.value = true
  if(props.selectedRob.id)
  conversation.value = await getLatestConversation(props.selectedRob.id)
  if(conversation.value && conversation.value.id) {
    //Comportement dû à l'utilisation d'un controller dans le back plutôt qu'apiplatform
    conversation.value["@id"] = "api/conversations/" + conversation.value?.id
    console.log('conversation.value', conversation.value)
    messages.value = await getMessages(conversation.value.id)
  }
  isLoading.value = false
})

watch(() => props.selectedConversation,async (newValue: Conversation) => {
  if(newValue.id)
  conversation.value = await getConversation(newValue.id)
  if(conversation.value && conversation.value.id)
  messages.value = await getMessages(conversation.value.id)
});

const closeWindow = () => {
  windowOpen.value = false
}

const submitMessage = async (messageContent: string) => {
  if (conversation.value && conversation.value.id) {
    console.log('conversation.value', conversation.value)
    const humanMessage = {
      content: messageContent,
      sentByHuman: true,
      readed: false,
      timeStamp: new Date().toISOString(),
      conversation: conversation.value["@id"],
      rob: props.selectedRob["@id"],
    };

    messages.value.push(humanMessage);
    try {
      await postMessage(humanMessage);
    } catch (e) {
      console.error('Erreur lors de l’envoi du message :', e);
    } finally {
      messageBody.value = '';
      messages.value = await getMessages(conversation.value.id)
      // TODO remplacer la ligne suivante par une méthode qui met à jour la conversation
      conversation.value = await getConversation(conversation.value.id);
    }
  }
};

</script>

<template>
  <Card class="w-auto h-full flex flex-col">
    <!-- Titre fixé en haut -->
    <CardTitle class="flex items-center justify-between border-b border-gray-200 p-4">
      <h1 class="text-lg font-semibold">Chat with {{ conversation?.rob?.name}} | Conversation : {{ conversation?.title}}</h1>
      <div class="flex items-center gap-2">
        <!-- Icône d'information pour afficher la description -->
        <span class="tooltip tooltip-bottom" :data-tip="conversation?.description">
          <InformationCircleIcon class="w-6 h-6 text-gray-400"/>
        </span>
        <!-- Bouton vers le profil de Rob -->
        <RouterLink :to="`/rob-profiles/${props.selectedRob}`" class="btn btn-primary btn-sm">
          <UserCircleIcon class="w-6 h-6 text-white"/>
        </RouterLink>
        <button @click="closeWindow" class="text-gray-500 hover:text-gray-700">
          <MinusIcon class="w-6 h-6"/>
        </button>
      </div>
    </CardTitle>

    <!-- Liste des messages (défilable) -->
    <CardBody class="flex-1 overflow-y-auto p-4 space-y-4">
      <div v-if="!isLoading">
        <!-- Boucle des messages -->
        <div v-for="message in messages" :key="message.id"
             :class="message.sentByHuman ? 'chat chat-end' : 'chat chat-start'">
          <!-- Date et heure du message -->
          <p class="chat-header text-xs text-gray-400 mb-1">{{
              new Date(message.timeStamp).toLocaleString()
            }}</p>
          <!-- Bulle de message avec couleur dynamique -->
          <div
              :class="['chat-bubble max-w-xs', message.sentByHuman ? 'bg-blue-500 text-white' : 'bg-gray-200 text-black']">
            {{ message.content }}
          </div>
          <p class="chat-footer">{{ message.readed }}</p>
        </div>
      </div>
    </CardBody>

    <!-- Section d'écriture et d'envoi fixée en bas -->
    <div class="flex items-center gap-2 p-4 border-t border-gray-200">
      <textarea
          placeholder="Écrivez votre message..."
          class="textarea textarea-bordered w-full resize-none rounded-lg"
          rows="1"
          v-model="messageBody">
      </textarea>
      <button @click="submitMessage(messageBody)" class="btn btn-primary flex-shrink-0 px-4 py-2">
        <PaperAirplaneIcon class="w-6 h-6 text-white"/>
      </button>
    </div>
  </Card>
</template>
