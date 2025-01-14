<script setup lang="ts">


import {onBeforeMount, ref, watch} from "vue";
import type {Message} from "../message/type/Message";
import CardTitle from "../../components/utils/cards/CardTitle.vue";
import Card from "../../components/utils/cards/Card.vue";
import CardBody from '../../components/utils/cards/CardBody.vue';
import {InformationCircleIcon, MinusIcon, UserCircleIcon, XMarkIcon, PaperAirplaneIcon} from "@heroicons/vue/24/outline"
import type {Conversation} from "../conversations/type/Conversation";
import {getConversation} from "../conversations/api/Conversation";
import {postMessage} from "../message/api/Message.ts";


const messageBody = ref<string>("")
const conversationId = defineModel<number>({
  type: Number,
  default: 1
})
/*const props = defineProps<{
  robId: number
}>()*/
const messages = ref<Message[]>([])

const conversation = ref<Conversation>(1)
const isLoading = ref(false)

const props = defineProps({
  selectedConversation: {
    required: true
  }
});

onBeforeMount(async () => {
  isLoading.value = true
  isLoading.value = false
})

watch(() => props.selectedConversation,async (newValue: Conversation) => {
  conversation.value = await getConversation(newValue.id)
  messages.value = conversation.value.messages
});

const submitMessage = async (messageBody: string) => {

  postMessage(message)
  console.log("Message envoyé : ", message)
}
</script>

<template>
  <div class="relative h-full">

    <div class="overflow-y-auto">

      <Card class="border-blue-300 w-100 ">
        <CardTitle>
          <h1>Chat avec {{ conversation.title }}</h1>
          <!-- Icône d'information pour afficher la description -->
          <span class="tooltip tooltip-bottom" :data-tip="conversation?.description">
              <InformationCircleIcon class="w-6 h-6 text-gray-400"/>
            </span>
          <!-- Bouton vers le profil de Rob -->
          <RouterLink :to="`/rob-profiles/${conversation?.rob?.id}`" class="btn btn-primary btn-sm ml-2">
            <UserCircleIcon class="w-6 h-6 text-gray-400"/>
          </RouterLink>
          <button @click="closeWindow" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <MinusIcon class="w-6 h-6 text-gray-400"/>
          </button>
        </CardTitle>

        <CardBody>
          <div v-if="!isLoading" class="space-y-4 max-h-80 overflow-y-auto ">
            <!-- max-h-80 pour limite la hauteur du conteneur -->

            <!-- Boucle des messages -->
            <div v-for="message in messages" :key="message.id"
                 :class="message.sentByHuman ? 'chat chat-end' : 'chat chat-start'">

              <!-- Date et heure du message -->
              <p class=" chat-header text-xs text-gray-400 mb-1">{{
                  new Date(message.timeStamp).toLocaleString()
                }}</p>

              <!-- Bulle de message avec couleur dynamique -->
              <div
                  :class="['chat-bubble max-w-xs', message.sentByHuman ? 'bg-blue-500 text-white' : 'bg-gray-200 text-black']">
                {{ message.content }}
              </div>
              <p class="chat-footer">{{ message.readed }}</p>
            </div>

            <!-- Section pour écrire et envoyer un message -->
            <div class="flex items-center gap-2 mt-4">
              <textarea
                  placeholder="Écrivez votre message..."
                  class="textarea textarea-bordered w-full resize-none rounded-lg"
                  rows="1"
              v-model="messageBody">{{ messageBody }}
              </textarea>
              <button @click="submitMessage(messageBody)" class="btn btn-primary flex-shrink-0 px-4 py-2">
                <PaperAirplaneIcon class="w-6 h-6 text-gray-400"/>
              </button>
            </div>
          </div>
        </CardBody>
      </Card>
    </div>
  </div>
  <!--      </ul>
      </div>-->
</template>

<style scoped>

</style>