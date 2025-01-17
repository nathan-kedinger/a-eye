<script setup lang="ts">

import ChatWindow from "../modules/chat/ChatWindow.vue";
import {onMounted, ref} from "vue";
import ConversationList from "../modules/conversations/ConversationList.vue";
import type {Conversation} from "../modules/conversations/type/Conversation";
import Carousel from "../components/utils/cards/Carousel.vue";
import CardTitle from "../components/utils/cards/CardTitle.vue";
import CardAction from "../components/utils/cards/CardAction.vue";
import CardBody from "../components/utils/cards/CardBody.vue";
import Card from "../components/utils/cards/Card.vue";
import type {Rob} from "../modules/Rob/type/Rob";
import {getRobsCollection} from "../modules/Rob/api/Rob";
import { IdentificationIcon, ChatBubbleLeftIcon } from '@heroicons/vue/24/solid'
const selectedConversation = ref<Conversation>()
const selectedRob = ref<Rob>()
const robsList = ref<Rob[]>([]);
const dynamicCssRobList = ref<string>("grid grid-cols-4 col-span-12")
const windowOpen = ref<boolean>(false);
const pictures = [
  "https://img.daisyui.com/images/stock/photo-1559703248-dcaaec9fab78.webp",
  "https://img.daisyui.com/images/stock/photo-1565098772267-60af42b81ef2.webp",
  "https://img.daisyui.com/images/stock/photo-1572635148818-ef6fd45eb394.webp",
  "https://img.daisyui.com/images/stock/photo-1494253109108-2e30c049369b.webp",
  "https://img.daisyui.com/images/stock/photo-1550258987-190a2d41a8ba.webp",
  "https://img.daisyui.com/images/stock/photo-1559181567-c3190ca9959b.webp",
  "https://img.daisyui.com/images/stock/photo-1601004890684-d8cbf643f5f2.webp",
];


onMounted(async () => {
  robsList.value = await getRobsCollection()
});

const handleSelectedConversation = (conversation: Conversation) => {
  selectedConversation.value = conversation
  console.log('selectedConversation', selectedConversation.value)
}
const handleSpeakButton = (rob : Rob) => {
  windowOpen.value = true
  selectedRob.value = rob
  dynamicCssRobList.value = "grid grid-cols-1 cols-span-4"
}
</script>

<template>
    <div class="grid grid-cols-12">
      <div v-if="windowOpen" class="col-span-9 grid grid-cols-8 h-screen overflow-y-auto">
        <ConversationList
            :robConversations="selectedRob"
            @conversation="handleSelectedConversation"
            class="col-span-3 h-svh overflow-y-auto "
        />
        <ChatWindow
            v-model="windowOpen"
            :selectedRob="selectedRob"
            :selectedConversation="selectedConversation"
            class="col-span-5 h-svh overflow-y-auto"
        />
      </div>
      <!-- Conditionne la dimension des cols lorsque le chat est ouvert -->
      <div :class="windowOpen ? 'grid grid-cols-1 col-span-3' : 'grid grid-cols-4 col-span-12'" class="h-screen overflow-y-auto pt-10 ">
        <!-- Cette Partie -->

        <Card
            class="m-4 flex items-center gap-x-4 text-xs"
        >
          <figure>
            <img src="https://cdn.pixabay.com/photo/2016/12/21/17/11/signe-1923369_1280.png" alt="Plus">
          </figure>
          <CardBody>
            <CardTitle>
              <h2>New rob</h2>
            </CardTitle>
            <p>Click here to create a new chatbot.</p>
            <CardAction class="justify-end">
              <button onclick="new_bot.showModal()" class="btn bg-blue-800">+</button>
              <button class="btn bg-blue-800">
                +
              </button>
              <button class="btn">open modal</button>
            </CardAction>
          </CardBody>
        </Card>
        <Card
            class="m-4 flex items-center gap-x-4 text-xs"
            v-for="rob in robsList"
            :key="rob.id"
        >
          <figure>
            <Carousel v-model="pictures" />
          </figure>
          <CardBody>
            <CardTitle>
              <h2>{{ rob.name }}</h2>
            </CardTitle>
            <p>{{ rob.description }}</p>
            <CardAction class="justify-end">
              <button class="btn " @click="handleSpeakButton(rob)"><ChatBubbleLeftIcon class="w-6 h-6 text-blue-500"/></button>
              <button class="btn "><IdentificationIcon class="w-6 h-6 text-blue-500"/>
                <RouterLink :to="`/rob-profiles/${rob.id}`">Voir profil</RouterLink>
              </button>
            </CardAction>
          </CardBody>
        </Card>
      </div>
    </div>
  <dialog id="new_bot" class="modal">
    <div class="modal-box">
      <h3 class="text-lg font-bold">Hello!</h3>
      <p class="py-4">Press ESC key or click the button below to close</p>
      <div class="modal-action">
        <form method="dialog">
          <!-- if there is a button in form, it will close the modal -->
          <button class="btn">Close</button>
        </form>
      </div>
    </div>
  </dialog>
</template>


<style scoped>
.col-span-3 {
  transition: all 2.9s ease-in-out;
}
</style>