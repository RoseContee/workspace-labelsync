import { createStore } from 'vuex';

type IStateType = 'users' | 'labels';

const store = createStore({
  state: {
    users: [],
    labels: [],
  },
  getters: {
    data: (state) => (which: IStateType) => state[which],
  },
  mutations: {
    SET_USERS: (state, users) => (state.users = users),
    SET_LABELS: (state, labels) => (state.labels = labels),
  },
  actions: {
    getState: async ({ commit }, data) => {
      const { users, labels } = data;
      commit('SET_USERS', users);
      commit('SET_LABELS', labels);
    },
  },
  modules: {},
});

export default store;
