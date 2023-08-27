<template>
  <div class="user-info">
    <div v-if="!syncStarted">
      <button class="btn btn-sm btn-primary" @click="startSync">Start Sync</button>
    </div>
    <div class="text-info" v-else>
      <p>Next sync time:</p>
      <p class="mb-0">
        <b>{{ dateTimeformat(syncTime) }}</b>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, watchEffect } from 'vue';
import ChromeService from '@/service/chrome';
import { dateTimeformat } from '@/service/utils';
import { SYNC_PERIOD } from '@/service/config';

const syncStarted = ref(false);
const syncTime = ref(0);

onMounted(async () => {
  syncStarted.value = (await ChromeService.get({ syncStarted: false })).syncStarted;
});

const resetNextSyncTime = async () => {
  const { lastSyncTime } = await ChromeService.get({ lastSyncTime: 0 });
  const interval = SYNC_PERIOD * 60 * 1000;
  syncTime.value = lastSyncTime + interval;
};

watchEffect(() => {
  if (syncStarted.value) resetNextSyncTime();
});

chrome.runtime.onMessage.addListener((message) => {
  if (message.signal === 'RunningSync') resetNextSyncTime();
});

const startSync = () => {
  syncStarted.value = true;
  ChromeService.send({ signal: 'StartSync' });
};
</script>
