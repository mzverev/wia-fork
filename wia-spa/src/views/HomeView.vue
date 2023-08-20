<template>
  <div :class="['search-container', 'p-5', {'fixed': imagesNotEmpty}]">
    <div v-if="!imagesNotEmpty" class="pb-6" style="color:var(--gray-700)">
      <div class="text-5xl pb-2">Web Assistant for Impaired People</div>
      <div class="text-1xl">from any public website</div>
    </div>
    <SearchBlock ref="childComponent" @search="fetchImages" :search-in-progress="searchInProgress"
                 :error-message="errorMessage"/>
  </div>
  <div v-if="imagesNotEmpty" class="images-container">
    <ImagesBlock :images="images"/>
  </div>
</template>

<script setup>
  import { ref, computed } from "vue"
  import { describeImages } from "../services/api"
  import SearchBlock from "../components/SearchBlock.vue"
  import ImagesBlock from "../components/ImagesBlock.vue"
  import {channel} from "../services/pusher";

  const images = ref([])
  const searchInProgress = ref(false)
  const errorMessage = ref('')

  const imagesNotEmpty = computed(() => images.value.length != 0)

  channel.bind('images.described', data => {
    searchInProgress.value = false
    images.value.push(data.image)
  });

  const fetchImages = async (searchTerm) => {
    try {
      images.value = [];
      searchInProgress.value = true
      errorMessage.value = ''
      await describeImages(searchTerm)
    } catch (error) {
      searchInProgress.value = false
      errorMessage.value = error.response.data.message;
    }
  }
</script>

<style scoped>
  .search-container {
    display: flex;
    flex-grow: 1;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: auto;
    text-align: center;
  }
  .search-container.fixed {
    width: 100%;
    background-color: #ffffff;
    box-shadow: 0px 1px 8px rgba(0, 0, 0, 0.08), 0px 3px 4px rgba(0, 0, 0, 0.1), 0px 1px 4px -1px rgba(0, 0, 0, 0.1);
    z-index: 100;
  }
  .images-container {
    flex-grow: 4;
    padding: 10px;
    margin-top: 150px;
  }
</style>
