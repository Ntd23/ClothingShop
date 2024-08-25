import router from "@/router";
import { createStore } from "vuex";

export default createStore({
  state: {
    isAuthenticated: !!localStorage.getItem('token'),
  },
  mutations: {
    LOGIN(state) {
      state.isAuthenticated= true; //ON
    },
    LOGOUT(state) {
      state.isAuthenticated= false; //OFF
    }
  },
  actions: {
    login({commit}) {
      commit('LOGIN', true);
      // router.push('/');
    },
    logout({commit}) {
      commit('LOGOUT');
      router.push('/login');
    },

  },
})
