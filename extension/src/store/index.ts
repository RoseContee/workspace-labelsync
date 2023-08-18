import { createStore } from 'vuex';

export type IUsers = {
  name: string;
  email: string;
  isAdmin: boolean;
}[];

interface IState {
  users: IUsers;
  selectedUsers: string[];
}

const store = createStore({
  state: {
    users: [],
    selectedUsers: [],
  } as IState,
  getters: {
    users: (state) => state.users,
    selectedUsers: (state) => state.selectedUsers,
  },
  mutations: {
    SET_USERS: (state, users) => (state.users = users),
    SET_SELECTED_USERS: (state, users) => (state.selectedUsers = users),
  },
  actions: {
    setUsers: ({ commit }, users) => {
      commit('SET_USERS', users);
    },
    setSelectedUsers: ({ commit }, users) => {
      commit('SET_SELECTED_USERS', users);
    },
  },
  modules: {},
});

export default store;
