<script setup lang="ts">

import CardBody from "../../components/utils/cards/CardBody.vue";
import CardTitle from "../../components/utils/cards/CardTitle.vue";
import Carousel from "../../components/utils/cards/Carousel.vue";
import Card from "../../components/utils/cards/Card.vue";
import CardAction from "../../components/utils/cards/CardAction.vue";
import {computed, onMounted, ref} from "vue";
import type {Rob} from "./type/Rob";
import {getRobsCollection} from "./api/Rob";

const  emit = defineEmits<{
(event: "robId", rob: number )
}>()

const pictures = [
  "https://img.daisyui.com/images/stock/photo-1559703248-dcaaec9fab78.webp",
  "https://img.daisyui.com/images/stock/photo-1565098772267-60af42b81ef2.webp",
  "https://img.daisyui.com/images/stock/photo-1572635148818-ef6fd45eb394.webp",
  "https://img.daisyui.com/images/stock/photo-1494253109108-2e30c049369b.webp",
  "https://img.daisyui.com/images/stock/photo-1550258987-190a2d41a8ba.webp",
  "https://img.daisyui.com/images/stock/photo-1559181567-c3190ca9959b.webp",
  "https://img.daisyui.com/images/stock/photo-1601004890684-d8cbf643f5f2.webp",
];
const robsList = ref<Rob[]>([]);



onMounted(async () => {
  robsList.value = await getRobsCollection()
});

const handleSpeakButton = (rob : Rob) => {
  const robId = rob.id
  emit("robId", robId)
}

const gridClasses = computed(() => {
  return "grid-cols-[repeat(auto-fill,minmax(250px,1fr))]";
});
</script>

<template>
  <div class="grid gap-8 overflow-y-auto h-100" :class="gridClasses">
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
          <button class="btn btn-primary" @click="handleSpeakButton(rob)">Parler</button>
          <button class="btn btn-primary">
            <RouterLink :to="`/rob-profiles/${rob.id}`">Voir profil</RouterLink>
          </button>
        </CardAction>
      </CardBody>
    </Card>
  </div>
</template>

<style scoped>

</style>