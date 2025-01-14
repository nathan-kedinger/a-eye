<script setup lang="ts">
import CardTitle from "~/components/utilities/cards/CardTitle.vue";
import CardBody from "~/components/utilities/cards/CardBody.vue";
import type {Rob} from "~/utils/types/Rob";
import {getRob} from "~/utils/api/Rob";
import {onBeforeMount, ref} from "vue";


//TODO transmettre le robId
const rob = ref<Rob>({name: "", description: "", id: 0})
const isLoading = ref(true)
onBeforeMount(async () => {
  if (typeof profileId === "string") {
    rob.value = await getRob(parseInt(profileId))
  }
})

</script>

<template>
    <CardTitle>
      <h1>Profile {{ rob.name}} </h1>
    </CardTitle>
    <CardBody>
    </CardBody>
</template>

<style scoped>

</style>