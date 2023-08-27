<template>
  <div class="subscription">
    <div class="mb-3">
      <p>Enter license key:</p>
      <div class="input-group">
        <input type="text" class="form-control form-control-sm" placeholder="License key..." v-model="licenseKey" />
        <button class="btn btn-sm btn-primary" :disabled="disableSubmit" @click="submitLicenseKey">Submit</button>
      </div>
      <div class="text-danger small">{{ license_error }}</div>
    </div>
    <hr />
    <div class="mb-3">
      <p>Don't you have license key?</p>
      <button class="btn btn-sm btn-success">Subscribe Now</button>
    </div>
    <hr />
    <div class="mb-3">
      <p>Forgot your license key?</p>
      <div class="input-group">
        <input type="email" class="form-control form-control-sm" placeholder="Your email..." v-model="email" />
        <button class="btn btn-sm btn-primary" :disabled="disableRecover" @click="recoverLicenseKey">Recover</button>
      </div>
      <div class="text-danger small">{{ email_error }}</div>
      <div class="mt-2 text-success">{{ recoveredKey }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onBeforeMount } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import GoogleService from '@/service/google';
import AdminLabelsyncItService from '@/service/admin.labelsync.it';
import ChromeService from '@/service/chrome';

const store = useStore();
const router = useRouter();

const userEmail = ref('');
const licenseKey = ref('');
const license_error = ref('');
const disableSubmit = ref(false);
const email = ref('');
const email_error = ref('');
const disableRecover = ref(false);
const recoveredKey = ref('');

onBeforeMount(async () => {
  const access_token = await GoogleService.getAccessToken();
  if (!access_token) {
    router.push({ name: 'welcome' });
    return;
  }
  const { email } = await GoogleService.getUserInfo(access_token);
  userEmail.value = email;
});

function submitLicenseKey() {
  if (!userEmail.value || !licenseKey.value) return;
  disableSubmit.value = true;
  license_error.value = '';
  AdminLabelsyncItService.submitLicenseKey(userEmail.value, licenseKey.value).then((response) => {
    disableSubmit.value = false;
    if (response.success) {
      ChromeService.save({ licenseKey: licenseKey.value });
      ChromeService.send({ signal: 'StartSync' });
      store.dispatch('setLicenseInfo', response);
      router.push({ name: 'home' });
      return;
    }
    license_error.value = response.error ?? 'Invalid key.';
  });
}

function recoverLicenseKey() {
  if (!email.value) return;
  if (email.value !== userEmail.value) {
    email_error.value = 'Email does not match your email.';
    return;
  }
  disableRecover.value = true;
  email_error.value = '';
  AdminLabelsyncItService.recoverLicenseKey(email.value).then((response) => {
    disableRecover.value = false;
    if (response.licenseKey) recoveredKey.value = response.licenseKey;
    else email_error.value = 'Cannot find your information.';
  });
}
</script>
