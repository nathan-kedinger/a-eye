<script setup lang="ts">

import {onBeforeMount, ref} from "vue";
import Card from "../../components/utils/cards/Card.vue";
import type {Rob} from "./type/Rob.ts";
import CardTitle from "../../components/utils/cards/CardTitle.vue";
import CardBody from "../../components/utils/cards/CardBody.vue";
import {getRob} from "./api/Rob.ts";


const props = defineProps({
  robId: {
    type: Number,
    required: true
  }
})
//const route = useRoute()
const rob = ref<Rob>()
const description = ref<string>('')
const isLoading = ref(true)


onBeforeMount(async () => {
  rob.value = await getRob(props.robId)
  isLoading.value = false
})

const handleUpdateRob = async () => {
//  await updateRob(rob.value)
}

</script>

<template>
  <card>
    <CardTitle>
      <h1>Profile {{ rob?.name}} </h1>
    </CardTitle>
    <CardBody>
      <textarea
          :placeholder="rob?.description"
          class="textarea textarea-bordered textarea-lg w-full max-w-xs"
          v-model="description"
          >

      </textarea>
    </CardBody>
    <button @click="handleUpdateRob">Save</button>
  </card>
</template>

<style scoped>

</style>