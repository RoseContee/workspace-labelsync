import { createStore } from 'vuex';
import { ILicenseResponse } from '@/service/admin.labelsync.it';

export interface IUser {
  name: string;
  email: string;
  isAdmin: boolean;
}

interface IState {
  users: IUser[];
  selectedUsers: string[];
  licenseInfo: ILicenseResponse | null;
}

const store = createStore({
  state: {
    users: [],
    selectedUsers: [],
    licenseInfo: null,
  } as IState,
  getters: {
    users: (state) => state.users,
    selectedUsers: (state) => state.selectedUsers,
    licenseInfo: (state) => state.licenseInfo,
  },
  mutations: {
    SET_USERS: (state, users) => (state.users = users),
    SET_SELECTED_USERS: (state, users) => (state.selectedUsers = users),
    SET_LICENSE_INFO: (state, licenseInfo) => (state.licenseInfo = licenseInfo),
  },
  actions: {
    setUsers: ({ commit }, users) => {
      commit('SET_USERS', users);
    },
    setSelectedUsers: ({ commit }, users) => {
      commit('SET_SELECTED_USERS', users);
    },
    setLicenseInfo: ({ commit }, licenseInfo) => {
      commit('SET_LICENSE_INFO', licenseInfo);
    },
  },
  modules: {},
});

export default store;
