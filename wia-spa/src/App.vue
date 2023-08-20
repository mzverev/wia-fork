<script setup>
  import "primevue/resources/themes/lara-light-indigo/theme.css";
  import 'primeicons/primeicons.css';
  import "primeflex/primeflex.css"
</script>

<template>
  <div id="app" class="body-container">
    <router-view/>
  </div>
</template>

<style>
  body {
    margin: 0px;
  }
  .body-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    font-family: var(--font-family);
    background-color: #e4e4e7;
  }
</style>
