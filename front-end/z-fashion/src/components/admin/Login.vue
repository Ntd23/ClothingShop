<template>
  <div class="container d-flex align-items-center justify-content-center" style="margin-top: 100px;">
    <div class="card w-50 bg-transparent">
      <div class="card-header">
        <div class="card-title text-center text-uppercase">Đăng nhập hệ thống</div>
      </div>
      <div class="card-body">
        <form @submit.prevent="login">
          <div class="mb-3">
            <label class="form-label">Địa chỉ Email</label>
            <input type="text" class="form-control" v-model="this.email">
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" v-model="password">
          </div>
          <div class="mb-3 text-center">
            <button type="submit" class="btn btn-lg btn-secondary">Đăng nhập</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from '@/axios';
import router from '@/router';
import {mapState} from 'vuex';

export default {
  data() {
    return {
      email: '',
      password: '',
      errors: {},
    }
  },
  computed: {
    ...mapState(['user']),
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('/login', {
          email: this.email,
          password: this.password
        })
        if(response.data.token) {
          localStorage.setItem('token', response.data.token);
        }
        this.$store.commit('ADMIN_LOGIN', response.data.user);
        router.push('/admin');
      } catch (e) {
        console.log(e);
        this.errors = e.response.data.errors || e.response.data.message_error;
      }
    }
  },
  beforeCreate() {
    const user = JSON.parse(localStorage.getItem('user'));
    const token = localStorage.getItem('token');

    if (user && user.vaitro === 1 && token) {
      // Nếu người dùng đã đăng nhập và cố gắng truy cập trang đăng nhập, chuyển hướng đến /admin
      router.push('/admin');
    }
  }
}
</script>
