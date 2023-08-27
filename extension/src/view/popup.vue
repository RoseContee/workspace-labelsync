<template>
  <router-view />
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import ChromeService from '@/service/chrome';
import GoogleService from '@/service/google';
import AdminLabelsyncItService from '@/service/admin.labelsync.it';

const store = useStore();
const router = useRouter();

onMounted(async () => {
  const access_token = await GoogleService.getAccessToken();
  if (!access_token) {
    router.push({ name: 'welcome' });
    return;
  }
  const { email } = await GoogleService.getUserInfo(access_token);
  const users = await GoogleService.getDirectoryUsers(access_token);
  if (users.find((user) => user.email === email && user.isAdmin)) {
    const labels = await GoogleService.getGroupPersons(access_token);
    const { selectedUsers, selectedLabels, licenseKey } = await ChromeService.get({
      selectedUsers: [],
      selectedLabels: [],
      licenseKey: '',
    });
    store.dispatch('setUsers', users);
    store.dispatch('setSelectedUsers', selectedUsers);
    store.dispatch(
      'setLabels',
      Object.keys(labels).map((name) => ({ name: name, members: labels[name].persons.length })),
    );
    store.dispatch('setSelectedLabels', selectedLabels);
    if (licenseKey) {
      store.dispatch('setLicenseInfo', await AdminLabelsyncItService.submitLicenseKey(email, licenseKey));
    }
    router.push({ name: 'home' });
  } else {
    router.push({ name: 'user-info' });
  }
});
</script>
