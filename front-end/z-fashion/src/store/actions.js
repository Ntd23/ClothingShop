import router from "../router";
// import axios from "@/axios";
import auth from "@/axios";
//auth
export const login = ({ commit }, user) => {
  commit("LOGIN", user);
};

export const logout = async ({commit}) => {
    const token = localStorage.getItem("token");
    auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    auth.post('/logout')
      .then(res=> console.log(res))
      .then(
        commit("LOGOUT"),
        router.push("/")
      )
      .catch(err => console.log(err));
};

export const admin_login = ({ commit }, user) => {
  commit("ADMIN_LOGIN", user);
  if (user.vaitro == 1) router.push("/admin");
  else router.push("/admin/login");
};

export const navigateToLogin = () => {
  router.push({
    name: "Login",
  });
};
//end auth
