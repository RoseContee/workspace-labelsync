<template>
  <router-view />
</template>

<script setup lang="ts">
import { onBeforeMount } from 'vue';
import { useStore } from 'vuex';
import GoogleService from '@/service/google-service';

const store = useStore();

onBeforeMount(async () => {
  const data = await GoogleService.getData();
  store.dispatch('getState', data);
});
</script>

<style lang="scss">
@import url('../assets/scss/popup.scss');
</style>
