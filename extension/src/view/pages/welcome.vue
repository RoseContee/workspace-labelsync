<template>
  <div class="welcome">
    <h3 class="app_name">{{ appName }}</h3>
    <h5 class="app_version">{{ appVersion }}</h5>
    <p class="copyright">&copy;copyright {{ new Date().getFullYear() }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { getAppInfo } from '@/service/config';

const appName = ref('');
const appVersion = ref('');

onMounted(async () => {
  const { name, version } = await getAppInfo();
  appName.value = name;
  appVersion.value = version;
});
</script>
