<template>
  <div class="grid justify-content-center column-gap-3 row-gap-5">
    <div class="col-3" v-for="(image, index) in images" :key="index">
      <ImageItem :src="image.src" :description="image.description" />
    </div>
  </div>
</template>

<script setup>
  import ImageItem from "./ImageItem.vue"

  defineProps({
    images: Array,
  })
</script>