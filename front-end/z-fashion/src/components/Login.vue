<template>
  <div class="login-page">
    <div class="container d-flex">
      <div class="card w-50 float-start opacity-75 col-6" style="height: 100%;">
        <img src="../assets/bg-login.jpg" alt="" class="bg-transparent" style="height: 392px;">
      </div>
      <div class="row col-6 container m-0 p-0">
        <div class="col p-0" >
          <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="message&&message!=''">
            <strong>{{ message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <form @submit.prevent="login" class="h-100">
              <div class="col-md-6 w-100 h-100">
                <div class="card py-5 h-100" id="form1" style="padding: 38px 0px;">
                  <div class="card-title text-center text-uppercase fw-bold fs-4 mt-2">Đăng nhập</div>
                  <div class="form-data mt-3">
                    <div class="forms-inputs mb-1 d-flex px-4">
                      <span class="col-3 label" style="margin-left: -24px;">Email</span>
                      <input type="text" v-model="email" class="form-control" style="width: 375px;margin-left: 24px;" />
                    </div>
                    <p class="text-danger fw-bold error" v-if="errors.email">{{ errors.email.toString() }}</p>
                    <div class="forms-inputs mb-1 d-flex">
                      <span class="col-3 label">Password</span>
                      <input type="password" v-model="password" class="form-control" style="width: 375px;margin-left: 12px;" />
                    </div>
                    <span class="text-danger fw-bold error" v-if="errors.password">{{ errors.password.toString() }}</span>
                    <span class="text-danger fw-bold error" v-if="errors.length > 0">{{ errors }}</span>
                    <div class="mb-3 mt-2">
                      <button class="btn btn-login" type="submit" style="width: 516px;">Login</button>
                      <router-link to="/register" class="login-register float-start">Bạn chưa có tài khoản?</router-link>
                     </div>
                  </div>
                </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from '@/axios';
import { mapState } from 'vuex';
import router from '@/router';

export default {
  data() {
    return {
      email: '',
      password: '',
      errors: {},
      message: ''
    }
  },
  computed: {
    ...mapState(['user']),
  },
  mounted() {
    this.message = this.$store.state.message_success;
    setTimeout(() => {
      this.$store.commit("CLEAR_MESSAGE");
      this.message = '';
    }, 2000);
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('/login', {
          email: this.email,
          password: this.password
        })
        if (response.data.token) {
          localStorage.setItem('token', response.data.token);
        }
        const { user, token } = response.data;
        // const expirationTokenTime= new Date().getTime()+ (60* 60* 1000);
        // console.log({user, token});
        this.$store.commit('LOGIN', { user, token });
        if (response.data.user.vaitro === 1) {
          router.push('/admin');
        } else {
          router.push('/');
        }
      } catch (e) {
        console.log(e);
        this.errors = e.response.data.errors || e.response.data.message_error;
        console.log(this.message);
      }
    }
  }
}
</script>

<style scoped>
.login-page {
  background-color: snow;
}

.container {
  padding: 0 21px;
  margin-bottom: 20px;
  color: #77203d;
  font-family: "Playwrite NG Modern", cursive;
}

.label {
  margin-top: 12px;
  font-weight: bold;
  color: #77203d;
  padding-left: 30px;
}
.error {
  margin-left: 150px;
}
.btn-login {
  background-color: rgb(224, 203, 215);
  width: 490px;
  margin-left: 32px;
}

.btn-login:hover {
  background-color: rgb(243, 94, 119);
}

.login-register {
  width: 100%;
  margin-top: 10px;
  margin-left: 30px;
}

.login-register:hover {
  color: #77203d;
  cursor: pointer;
}
</style>
