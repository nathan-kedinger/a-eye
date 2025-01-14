<script setup lang="ts">

const model = defineModel()
</script>

<template>
  <div class="carousel rounded-box w-64">
    <div v-for="(item, index) in model" :key="index" class="carousel-item w-full">
      <img :src="item" class="w-full" alt="Carousel image" />
    </div>
  </div>
</template>

<style scoped>

</style>