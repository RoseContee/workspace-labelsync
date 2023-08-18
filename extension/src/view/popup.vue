<template>
  <router-view />
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import ChromeService from '@/service/chrome-service';
import GoogleService from '@/service/google-service';
import LabelsyncService from '@/service/labelsync-service';

const store = useStore();
const router = useRouter();

onMounted(async () => {
  const access_token = await GoogleService.getAccessToken();
  if (!access_token) {
    router.push({ name: 'welcome' });
    return;
  }
  const users = await GoogleService.getDirectoryUsers(access_token);
  const { email } = await GoogleService.getUserInfo(access_token);
  if (users.find((user) => user.email === email && user.isAdmin)) {
    const { selectedUsers, licenseKey } = await ChromeService.get({ selectedUsers: [], licenseKey: '' });
    store.dispatch('setUsers', users);
    store.dispatch('setSelectedUsers', selectedUsers);
    if (licenseKey) {
      store.dispatch('setLicenseInfo', await LabelsyncService.submitLicenseKey(email, licenseKey));
    }
    router.push({ name: 'home' });
  }
});
</script>
