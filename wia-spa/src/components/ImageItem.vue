<template>
  <Card>
    <template #header>
      <img alt="user header" class="image" :src="src" />
    </template>
    <template #content>
      <p class="text-1xl">{{ description }}</p>
    </template>
  </Card>
</template>

<script setup>
  import Card from "primevue/card"
  import Button from "primevue/button"

  defineProps({
    src: String,
    description: String
  })
</script>

<style scoped>
  .image {
    width: 100%;
    aspect-ratio: 3/2;
    border-top-right-radius: 6px;
    border-top-left-radius: 6px;
  }
</style>