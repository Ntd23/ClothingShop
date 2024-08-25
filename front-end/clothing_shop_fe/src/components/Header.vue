<template>
  <div>
  <div class="container-fluid d-flex header">
    <div class="z-fashion col-md-3">Z <span>Fashion</span></div>
    <div class="col-md-8">
      <div class="container-fluid d-flex align-items-center">
        <a class="navbar-brand px-2" href="#">Trang chủ</a>
        <a class="navbar-brand px-2">Cửa hàng</a>
        <a class="navbar-brand px-2" href="#">Tin tức</a>
        <a class="navbar-brand px-2">Liên hệ</a>
        <form class="px-4">
          <input type="text" class="input-search" placeholder="Tìm kiếm sản phẩm ..." />
          <SearchOutlined style="position: absolute; right: 165px; top: 8%" />
        </form>
        <UserOutlined style="
            position: absolute;
            right: 90px;
            top: 7%;
            font-size: 26px;
            cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal" />
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                 <router-link to="/login" v-if="!isAuthenticated">Đăng nhập</router-link>
                <!-- <router-link to="/register" v-if="!isAuthenticated">Đăng kí</router-link>  -->
              </div>
            </div>
          </div>
        </div>
        <ShoppingCartOutlined style="position: absolute; right: 38px; top: 7%; font-size: 30px" />
      </div>
    </div>
  </div>
  <router-view></router-view>
  </div>
</template>

<script>
// import axios from "@/axios";
import {
  SearchOutlined,
  UserOutlined,
  ShoppingCartOutlined,
} from "@ant-design/icons-vue";
import {  } from 'vuex';
// import router from '@/router';

export default {
  components: {
    SearchOutlined,
    UserOutlined,
    ShoppingCartOutlined,
  },
  computed: {
    // ...mapState(['isAuthenticated'])
  },
  methods: {

  },
};
</script>

<style scoped>
.header {
  background-color: snow;
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

.input-search {
  border-top: 12px transparent;
  border-left: 12px transparent;
  border-radius: 7px;
  font-size: 18px;
  font-weight: lighter;
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
    margin-top: 60px;
}
.modal-header {
    width: 115%;
    height: 73px;
}
</style>
