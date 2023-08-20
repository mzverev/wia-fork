<template>
  <div>
    <span class="p-inputgroup search-block">
      <InputText :class="['border-round-left-3xl', {'p-invalid': errorMessage}]" v-model="searchTerm" placeholder="Search" size="large" @keyup.enter="search"/>
      <Button class="border-round-right-3xl" :icon="['pi', searchInProgress ?  'pi-spin pi-spinner' : 'pi-search']"
              size="large" severity="primary" :disabled="searchInProgress" @click="search" />
    </span>
    <span v-if="errorMessage" class="block mt-3 text-2xl text-red-600">{{ errorMessage }}</span>
  </div>
</template>

<script setup>
  import { ref } from 'vue';
  import InputText from 'primevue/inputtext'
  import Button from 'primevue/button'

  defineProps({
    "searchInProgress": {
      type: Boolean,
      default: false
    },
    "errorMessage": String
  })

  const searchTerm = ref('')
  const emit = defineEmits(['search'])

  const search = () => {
    emit('search', searchTerm.value)
  };
</script>

<style>
  .search-block {
    width: 50rem !important;
  }
  .p-inputtext:enabled:focus {
    box-shadow: 0 0 0 0.2rem var(--blue-300);
    border-color: var(--blue-400);
  }
  .p-inputtext:enabled:hover {
    border-color: var(--blue-400);
  }
</style>