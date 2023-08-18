<template>
  <div class="home">
    <div class="row">
      <div class="col-12 mb-2 small">
        <div class="text-success" v-if="licenseInfo && licenseInfo.success">
          Your license key will expire on <b>{{ UtilsService.dateFormat(licenseInfo.expires_on) }}</b
          >.
        </div>
        <div class="text-danger" v-else>
          <span v-if="licenseInfo">{{ licenseInfo.error }}</span>
          <router-link :to="{ name: 'subscription' }">Enter license key here.</router-link>
        </div>
      </div>
      <div class="col-12">
        <h5 class="text-center fw-bold mb-1">Select sync users</h5>
        <table class="table table-striped table-bordered table-sm">
          <thead>
            <tr>
              <th class="align-middle">
                <div class="form-check">
                  <label class="form-check-label">
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :checked="all && !!filteredUsers.length"
                      :disabled="!filteredUsers.length"
                      @change="selectAll"
                    />
                  </label>
                </div>
              </th>
              <th>Name</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in filteredUsers" :key="index">
              <td class="align-middle">
                <div class="form-check">
                  <label class="form-check-label">
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :checked="selectedUsers.includes(user.email)"
                      @change="selectUser(user.email)"
                    />
                  </label>
                </div>
              </td>
              <td>{{ user.name }}</td>
              <td class="text-break">{{ user.email }}</td>
            </tr>
            <tr v-if="!users.length">
              <td colspan="3" class="text-center fst-italic">No users</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-6">
        {{ per_page * (current_page - 1) + 1 }} - {{ Math.min(per_page * current_page, users.length) }} of
        {{ users.length }} users
      </div>
      <div class="col-6 d-flex justify-content-end">
        <ul class="pagination pagination-sm">
          <li :class="`page-item ${morePrev ? '' : 'disabled'}`">
            <span class="page-link" @click="prevPage">&lt;</span>
          </li>
          <li :class="`page-item ${moreNext ? '' : 'disabled'}`">
            <span class="page-link" @click="nextPage">&gt;</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { useStore } from 'vuex';
import { IUser } from '@/store';
import ChromeService from '@/service/chrome-service';
import UtilsService from '@/service/utils-service';
import { ILicenseResponse } from '@/service/labelsync-service';

const store = useStore();

const licenseInfo = computed(() => store.getters.licenseInfo as ILicenseResponse);
const per_page = ref(10);
const current_page = ref(1);
const filteredUsers = computed(() => {
  return users.value.filter((user, index) => {
    return per_page.value * (current_page.value - 1) <= index && index < per_page.value * current_page.value;
  });
});
const users = computed(() => {
  return (store.getters.users as IUser[]).filter((user) => !user.isAdmin);
});
const selectedUsers = computed(() => store.getters.selectedUsers as string[]);
const all = computed(() => {
  let all = true;
  users.value.forEach((user) => {
    if (all && !selectedUsers.value.includes(user.email)) all = false;
  });
  return all;
});
const morePrev = computed(() => current_page.value > 1);
const moreNext = computed(() => per_page.value * current_page.value < users.value.length);

function selectAll(e: Event) {
  let newUsers: string[] = [];
  if ((e.target as HTMLInputElement).checked) {
    newUsers = users.value.map((user) => user.email);
  }
  saveSelectedUsers(newUsers);
}

function selectUser(email: string) {
  let newUsers = [...selectedUsers.value];
  if (selectedUsers.value.includes(email)) newUsers = newUsers.filter((item) => item != email);
  else newUsers.push(email);
  saveSelectedUsers(newUsers);
}

function saveSelectedUsers(newUsers: string[]) {
  store.dispatch('setSelectedUsers', newUsers);
  ChromeService.save({ selectedUsers: newUsers });
}

function prevPage() {
  if (current_page.value > 1) current_page.value--;
}

function nextPage() {
  if (per_page.value * current_page.value < users.value.length) current_page.value++;
}
</script>
