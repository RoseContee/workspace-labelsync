<template>
  <div class="p-3">
    <div class="row">
      <div class="col-12 d-flex justify-content-around">
        <button class="btn btn-sm btn-primary" @click="which = 'users'">Fetch Users</button>
        <button class="btn btn-sm btn-success" @click="which = 'labels'">Fetch Labels</button>
      </div>
      <div class="col-12 mt-2">
        <p class="text-center fw-bold mb-1">
          {{ which == 'users' ? 'Users' : 'Labels' }}
        </p>
        <table class="table table-striped table-bordered table-sm">
          <thead>
            <tr>
              <th>{{ which == 'users' ? 'Name' : 'Label' }}</th>
              <th>{{ which == 'users' ? 'Email' : 'Contacts' }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in data" :key="index">
              <td>{{ which == 'users' ? item.name : item.formattedName }}</td>
              <td>{{ which == 'users' ? item.email : item.memberCount }}</td>
            </tr>
            <tr v-if="!data.length">
              <td colspan="2" class="text-center fst-italic">No data</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

const which = ref('users');
const data = computed(() => store.getters.data(which.value));
</script>
