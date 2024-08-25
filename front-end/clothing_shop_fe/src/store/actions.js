import router from "../router";
export const login= ({commit})=> {
  commit('LOGIN', true);
  // router.push('/');
};

export const logout= ({commit})=> {
  commit('LOGOUT');
  router.push('/login');
};
