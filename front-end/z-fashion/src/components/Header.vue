<template>
  <div class="position-sticky">
    <div class="navbar p-0" style="margin-left: 98px;">
      <div class="z-fashion col-md-3 m-0 position-relative">Z <span class="position-absolute top-0">Fashion</span></div>
      <a-dropdown>
        <UserOutlined style="
        position: absolute;
        right: 105px;
        top: 44%;
        font-size: 26px;
        cursor: pointer;" />
        <CaretDownOutlined style="margin-top: 9px;margin-right: 158px; cursor: pointer;" />
        <template #overlay>
          <a-menu @click="handleMenuClick">
            <a-menu-item :key="0" v-if="!isLoggedIn"><router-link :to="{ name: 'Login' }" class="login-register"
                v-if="!isLoggedIn">Đăng
                nhập</router-link></a-menu-item>
            <a-menu-item :key="1" v-if="!isLoggedIn"> <router-link :to="{ name: 'Register' }" class="login-register"
                v-if="!isLoggedIn">Đăng kí</router-link></a-menu-item>
            <a-menu-item :key="3" v-if="isLoggedIn&&store.state.user"> <span v-if="store.state.user.user.tendangnhap!==undefined">{{ store.state.user.user.tendangnhap || store.state.user.tendangnhap
                }}</span></a-menu-item>
            <a-menu-item :key="2" v-if="isLoggedIn"><router-link to="/" @click="logout" class="login-register"
                v-if="isLoggedIn">Đăng
                xuất</router-link></a-menu-item>

          </a-menu>
        </template>
      </a-dropdown>
    </div>
    <hr style="margin: 0;" />
    <div class="container-fluid d-flex header p-0 m-0" style="margin-left: 20px;">
      <div class="col-md-8">
        <div class="container-fluid d-flex align-items-center" style="padding-left: 88px; font-family: none">
          <router-link :to="{ name: 'Home' }" class="navbar-brand px-2">Trang chủ</router-link>
          <category/>
          <a class="navbar-brand px-2" href="#">Tin tức</a>
          <router-link :to="{ name: 'Contact' }" class="navbar-brand px-2" @click="redirectToContact">Liên
            hệ</router-link>
          <search/>
            <Filter/>
          <ShoppingCartOutlined style="position: absolute; right: 220px; top: 20.5%; font-size: 30px" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import router from '@/router';
import {
  UserOutlined,
  ShoppingCartOutlined,
  CaretDownOutlined,
} from "@ant-design/icons-vue";
import Category from "./Category.vue";
import Search from "./Search.vue";
import Filter from "./Filter.vue";
import store from "@/store";
export default {
  components: {
    UserOutlined,
    ShoppingCartOutlined,
    CaretDownOutlined,
    Category,
    Search,
    Filter
  },
  data() {
    return {
      username: ''
    }
  },
  computed: {
    ...mapState(['isLoggedIn', 'token', 'user']),
  },
  methods: {
    ...mapActions(['logout']),
    redirectToContact() {
      if (this.username == '') {
        this.$store.commit("SET_MESSAGE", "Bạn cần phải đăng nhập.");
        router.push("/login");
      }
    }
  },
};
</script>
<script setup>
const handleMenuClick = () => {

};
</script>
<style scoped>
.header {
  height: 110px;
}

.z-fashion {
  font-family: "Playwrite NG Modern", cursive;
  font-size: 4rem;
  font-weight: bold;
  line-height: 108px;
  color: rgb(224, 203, 215);
  text-align: left;
  margin-left: 100px;
}

.z-fashion span {
  color: #77203d;
  font-style: italic;
}

div .col-md-8 {
  color: #810d34;
  font-size: 20px;
  font-family: "Playwrite NG Modern", cursive;
  font-weight: bold;
}

.col-md-8>.container-fluid {
  height: 110px;
  padding-left: 88px;
}

.login {
  width: 100%;
  font-family: "Playwrite NG Modern", cursive;
  font-size: 22px;
  font-weight: bold;
  line-height: 60px;
  color: rgb(224, 203, 215);
  text-align: left;
  margin-left: 180px;
}

.login span {
  color: #77203d;
  font-style: italic;
}

span.required {
  color: red;
}

.btn-ok {
  background-color: rgb(224, 203, 215);
}

.btn-ok:hover {
  background-color: pink;
}

.modal-dialog {
  width: 100px;
  font-size: small;
  margin-right: 88px;
  margin-top: 80px;
}

.modal-header {
  width: 100%;
  height: 73px;
  padding: 0;
  display: block;
  text-align: center;
}

.modal-content {
  border: 0;
  border-radius: 16px rgb(224, 203, 215);
}

.login-register {
  font-family: "Playwrite NG Modern", cursive;
  text-decoration: none;
  width: 100%;
  display: block;
  margin-top: 10px;
  font-weight: bold;
}

.login-register:hover {
  color: rgb(224, 203, 215);
  cursor: pointer;
  font-weight: bold;
}
</style>
