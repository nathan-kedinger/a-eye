<script setup lang="ts">
const props = defineProps<{
  title?: string
}>()
</script>

<template>
  <div class="card-title">
    <h1>{{ props.title }}</h1>
    <slot></slot>
  </div>
</template>

<style scoped>

</style>