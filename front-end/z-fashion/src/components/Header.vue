<template>
  <div class="position-sticky">
    <div class="subslider-first">
      Miễn phí trả hàng trong vòng 30 ngày
    </div>

    <div class="navbar p-0" style="margin-left: 103px;">
      <!-- <div class="z-fashion col-md-3 m-0 position-relative">Z <span class="position-absolute top-0">Fashion</span></div> -->
      <a href="/">
        <img src="../assets/logo.png" alt="">
      </a>
      <router-link to="/carts" class="d-flex text-decoration-none" style="margin-top: 26px;margin-right: 187px;">
        <p class="quantity-cart">{{ quantityCart }}</p>
        <ShoppingCartOutlined style="margin-left: 62px; font-size: 40px; color: #000" />
      </router-link>
      <a-dropdown>
        <UserOutlined style="
        position: absolute;
        right: 105px;
        top: 44%;
        font-size: 40px;
        cursor: pointer; color: black" />
        <CaretDownOutlined style="margin-top: 9px;margin-right: 158px; cursor: pointer;" />
        <template #overlay>
          <a-menu @click="handleMenuClick">
            <a-menu-item :key="0" v-if="!isLoggedIn"><router-link :to="{ name: 'Login' }" class="login-register"
                v-if="!isLoggedIn">Đăng
                nhập</router-link></a-menu-item>
            <a-menu-item :key="1" v-if="!isLoggedIn"> <router-link :to="{ name: 'Register' }" class="login-register"
                v-if="!isLoggedIn">Đăng kí</router-link></a-menu-item>
            <a-menu-item :key="3" v-if="isLoggedIn && store.state.user">
              <RouterLink :to="`/profile/${user_id}`">{{ username }}</RouterLink>
            </a-menu-item>
            <a-menu-item :key="2" v-if="isLoggedIn"><router-link to="/" @click="logout" class="login-register"
                v-if="isLoggedIn">Đăng
                xuất</router-link></a-menu-item>

          </a-menu>
        </template>
      </a-dropdown>
    </div>
    <hr style="margin: 0; color: #ebcfcf" />
    <div class="container d-flex header p-0 m-0" style="margin-left: 20px;">
      <div class="col-md-8">
        <div class="container-fluid d-flex align-items-center justify-content-between" style="padding-left: 95px">
          <div class="nav-left">
            <router-link :to="{ name: 'Home' }" class="navbar-brand px-2">Trang chủ</router-link>
            <category />
            <a class="navbar-brand px-2" href="#">Tin tức</a>
            <router-link :to="{ name: 'Contact' }" class="navbar-brand px-2" @click="redirectToContact">Liên
              hệ</router-link>
          </div>
          <div class="nav-right" style="margin-left: 348px;">
            <search />
          </div>
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
import { computed, onMounted, } from "vue";
export default {
  components: {
    UserOutlined,
    ShoppingCartOutlined,
    CaretDownOutlined,
    Category,
    Search,
  },
  computed: {
    ...mapState(['isLoggedIn', 'token',]),
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
import { useStore } from "vuex";
import auth from "@/axios";
const store = useStore();
const handleMenuClick = () => {
  console.log(user_id.value);

};
const user_id = computed(() => {
  const user = store.state.user?.user || store.state.user;
  return user?.id || '';
});
const username = computed(() => {
  const user = store.state.user?.user || store.state.user;
  return user?.tendangnhap || '';
});
const token = localStorage.getItem("token");
auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
const getQuantityCart = () => {
  if (user_id.value) {
    auth.get(`http://127.0.0.1:8000/carts/${user_id.value}`)
      .then(res => {
        store.commit('SET_QUANTITY_CART', res.data[1]);
      })
  }
}
const quantityCart = computed(() => store.state.quantity_cart);

onMounted(() => {
  getQuantityCart()
})
</script>
<style scoped>
.header {
  height: 110px;
}

.subslider-first {
  background-color: rgb(196, 17, 17);
  text-align: center;
  font-weight: bolder
}

.z-fashion {
  font-family: "Montserrat", sans-serif;
  font-optical-sizing: auto;
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
  font-family: "Montserrat", sans-serif;
  font-optical-sizing: auto;
  font-weight: bold;
}

.col-md-8>.container-fluid {
  height: 110px;
  padding-left: 88px;
}

.login {
  width: 100%;
  font-family: "Montserrat", sans-serif;
  font-optical-sizing: auto;
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
  font-family: "Montserrat", sans-serif;
  font-optical-sizing: auto;
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

.nav-left,
.nav-right {
  display: inline-flex;
}

.nav-right {
  margin-left: 363px;
}

.quantity-cart {
  font-size: medium;
  font-family: monospace;
  color: white;
  background: red;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  text-align: center;
  margin-top: -8px;
  margin-right: -106px;
  z-index: 20;
}

router-link {
  font-family: "Montserrat", sans-serif !important;
  font-optical-sizing: auto;
}
</style>
