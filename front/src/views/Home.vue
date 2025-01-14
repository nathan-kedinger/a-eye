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

const selectedConversation = ref<Conversation>()
const selectedRob = ref<number>()
const robsList = ref<Rob[]>([]);
const dynamicCssRobList = ref<string>("grid grid-cols-4 col-span-12")

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
}
const handleSpeakButton = (robId : number) => {
  selectedRob.value = robId
  dynamicCssRobList.value = "grid grid-cols-1 cols-span-4"
}
</script>

<template>
  <div class="grid grid-cols-12">
    <div v-if="selectedRob" class="col-span-8 grid grid-cols-8">
      <ConversationList :robConversations="selectedRob" @conversation="handleSelectedConversation" class="col-span-3"/>
      <ChatWindow :selectedConversation="selectedConversation" class="col-span-5 "/>
    </div>
    <!-- TODO conditionner la dimension des cols lorsque le chat est ouvert -->
    <div :class="dynamicCssRobList">
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
            <button class="btn btn-primary" @click="handleSpeakButton(rob.id)">{{rob.id}}</button>
            <button class="btn btn-primary">
              <RouterLink :to="`/rob-profiles/${rob.id}`">Voir profil</RouterLink>
            </button>
          </CardAction>
        </CardBody>
      </Card>
    </div>
  </div>
</template>

<style scoped>

</style>