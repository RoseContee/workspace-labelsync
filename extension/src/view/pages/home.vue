<template>
  <div class="home">
    <div class="row small">
      <div class="col-12 mb-2">
        <div class="text-success" v-if="licenseInfo && licenseInfo.success">
          Your license key will expire on <b>{{ dateFormat(licenseInfo.expires_on) }}.</b>
        </div>
        <div class="text-danger" v-else>
          <span v-if="licenseInfo">{{ licenseInfo.error }}</span>
          <router-link :to="{ name: 'subscription' }">Enter license key here.</router-link>
        </div>
      </div>
      <div class="col-12 text-info" v-if="licenseInfo && licenseInfo.success">
        Next sync time on <b>{{ dateTimeformat(syncTime) }}.</b>
        <button class="btn btn-sm btn-danger ms-2" @click="syncNow">Sync Now</button>
      </div>
    </div>
    <div class="row text-center my-2">
      <div class="col-4">
        <div class="btn btn-sm btn-primary" @click="selectType('users')">Users</div>
      </div>
      <div class="col-4">
        <div class="btn btn-sm btn-primary" @click="selectType('labels')">Labels</div>
      </div>
      <div class="col-4">
        <div class="btn btn-sm btn-success" @click="saveData">Save</div>
      </div>
    </div>
    <div class="row">
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
                      :checked="allSelected && !!filteredItems.length"
                      :disabled="!filteredItems.length"
                      @change="selectAllItems"
                    />
                  </label>
                </div>
              </th>
              <th>{{ type === 'users' ? 'Name' : 'Label' }}</th>
              <th>{{ type === 'users' ? 'Email' : 'members' }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in filteredItems" :key="index">
              <td class="align-middle">
                <div class="form-check">
                  <label class="form-check-label">
                    <input
                      type="checkbox"
                      class="form-check-input"
                      :checked="
                        type === 'users'
                          ? selectedUsers.includes((item as IUser).email)
                          : selectedLabels.includes(item.name)
                      "
                      @change="selectItem(type === 'users' ? (item as IUser).email : item.name)"
                    />
                  </label>
                </div>
              </td>
              <td>{{ item.name }}</td>
              <td class="text-break">{{ type === 'users' ? (item as IUser).email : (item as ILabel).members }}</td>
            </tr>
            <tr v-if="!filteredItems.length">
              <td colspan="3" class="text-center fst-italic">No users</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-6">{{ from }} - {{ to }} of {{ total }} users</div>
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
import { computed, ref, watchEffect } from 'vue';
import { useStore } from 'vuex';
import { IUser, ILabel } from '@/store';
import ChromeService from '@/service/chrome';
import { ILicenseResponse } from '@/service/admin.labelsync.it';
import { dateFormat, dateTimeformat } from '@/service/utils';
import { SYNC_PERIOD } from '@/service/config';

const store = useStore();
const PER_PAGE = 10;

type IType = 'users' | 'labels';

const licenseInfo = computed(() => store.getters.licenseInfo as ILicenseResponse);
const syncTime = ref(0);
const type = ref<IType>('users');
const users = computed(() => (store.getters.users as IUser[]).filter((user) => !user.isAdmin));
const selectedUsers = computed(() => store.getters.selectedUsers as string[]);
const labels = computed(() => store.getters.labels as ILabel[]);
const selectedLabels = computed(() => store.getters.selectedLabels as string[]);
const current_page = ref(1);
const filteredItems = computed(() => {
  if (type.value === 'users') {
    return users.value.filter((user, index) => {
      return PER_PAGE * (current_page.value - 1) <= index && index < PER_PAGE * current_page.value;
    });
  }
  return labels.value.filter((label, index) => {
    return PER_PAGE * (current_page.value - 1) <= index && index < PER_PAGE * current_page.value;
  });
});
const allSelected = computed(() => {
  let all = true;
  if (type.value === 'users') {
    users.value.forEach((user) => {
      if (all && !selectedUsers.value.includes(user.email)) all = false;
    });
  } else {
    labels.value.forEach((label) => {
      if (all && !selectedLabels.value.includes(label.name)) all = false;
    });
  }
  return all;
});
const from = computed(() => PER_PAGE * (current_page.value - 1) + 1);
const to = computed(() => Math.min(PER_PAGE * current_page.value, total.value));
const total = computed(() => (type.value === 'users' ? users.value.length : labels.value.length));
const morePrev = computed(() => current_page.value > 1);
const moreNext = computed(() => PER_PAGE * current_page.value < total.value);

const resetNextSyncTime = async () => {
  const { lastSyncTime } = await ChromeService.get({ lastSyncTime: 0 });
  const interval = SYNC_PERIOD * 60 * 1000;
  syncTime.value = lastSyncTime + interval;
};

watchEffect(() => {
  if ((licenseInfo.value ?? {}).success) resetNextSyncTime();
});

chrome.runtime.onMessage.addListener((message) => {
  if (message.signal === 'RunningSync') resetNextSyncTime();
});

const syncNow = () => {
  ChromeService.send({ signal: 'StartSync' });
};

const selectType = (t: IType) => {
  type.value = t;
  current_page.value = 1;
};

const saveData = async () => {
  await ChromeService.save({ selectedUsers: [...selectedUsers.value], selectedLabels: [...selectedLabels.value] });
  window.close();
};

const selectAllItems = (e: Event) => {
  const { checked } = e.target as HTMLInputElement;
  let newItems: string[] = [];
  if (type.value === 'users') {
    if (checked) {
      newItems = users.value.map((user) => user.email);
    }
    store.dispatch('setSelectedUsers', newItems);
  } else {
    if (checked) {
      newItems = labels.value.map((label) => label.name);
    }
    store.dispatch('setSelectedLabels', newItems);
  }
};

const selectItem = (item: string) => {
  let newItems: string[] = [];
  if (type.value === 'users') {
    if (selectedUsers.value.includes(item)) {
      newItems = selectedUsers.value.filter((email) => email != item);
    } else {
      newItems = [...selectedUsers.value, item];
    }
    store.dispatch('setSelectedUsers', newItems);
  } else {
    if (selectedLabels.value.includes(item)) {
      newItems = selectedLabels.value.filter((label) => label != item);
    } else {
      newItems = [...selectedLabels.value, item];
    }
    store.dispatch('setSelectedLabels', newItems);
  }
};

const prevPage = () => {
  if (morePrev.value) current_page.value--;
};

const nextPage = () => {
  if (moreNext.value) current_page.value++;
};
</script>
