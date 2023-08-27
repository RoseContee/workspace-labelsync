import { createStore } from 'vuex';
import { ILicenseResponse } from '@/service/admin.labelsync.it';

export interface IUser {
  name: string;
  email: string;
  isAdmin: boolean;
}

export interface ILabel {
  name: string;
  members: number;
}

interface IState {
  licenseInfo: ILicenseResponse | null;
  users: IUser[];
  selectedUsers: string[];
  labels: ILabel[];
  selectedLabels: string[];
}

const store = createStore({
  state: {
    licenseInfo: null,
    users: [],
    selectedUsers: [],
    labels: [],
    selectedLabels: [],
  } as IState,
  getters: {
    licenseInfo: (state) => state.licenseInfo,
    users: (state) => state.users,
    selectedUsers: (state) => state.selectedUsers,
    labels: (state) => state.labels,
    selectedLabels: (state) => state.selectedLabels,
  },
  mutations: {
    SET_LICENSE_INFO: (state, licenseInfo) => (state.licenseInfo = licenseInfo),
    SET_USERS: (state, users) => (state.users = users),
    SET_SELECTED_USERS: (state, users) => (state.selectedUsers = users),
    SET_LABELS: (state, labels) => (state.labels = labels),
    SET_SELECTED_LABELS: (state, labels) => (state.selectedLabels = labels),
  },
  actions: {
    setLicenseInfo: ({ commit }, licenseInfo) => {
      commit('SET_LICENSE_INFO', licenseInfo);
    },
    setUsers: ({ commit }, users) => {
      commit('SET_USERS', users);
    },
    setSelectedUsers: ({ commit }, users) => {
      commit('SET_SELECTED_USERS', users);
    },
    setLabels: ({ commit }, labels) => {
      commit('SET_LABELS', labels);
    },
    setSelectedLabels: ({ commit }, labels) => {
      commit('SET_SELECTED_LABELS', labels);
    },
  },
  modules: {},
});

export default store;
