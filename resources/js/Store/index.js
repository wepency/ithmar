import { createStore } from 'vuex'

const store = createStore({
  state: {
    count: 0,
  },
  mutations: {
    INCREMENT(state) {
      state.count++
    },
  },
  actions: {}
})

export default store;
