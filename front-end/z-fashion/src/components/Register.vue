<template>
  <div class="register-page">
    <div class="container d-flex">
      <div class="row col-6 container m-0 p-0" style="height: 566px;">
        <div class="col p-0">
          <form @submit.prevent="register" class="h-100">
              <div class="col-md-6 w-100 h-100">
                <div class="card h-100" id="form1" style="padding: 38px 0px;">
                  <div class="card-title text-center text-uppercase fw-bold fs-4 mt-2">Đăng ký</div>
                  <div class="form-data mt-3">
                    <div class="forms-inputs mb-1 d-flex px-4">
                      <span class="col-3 label">Tên đăng nhập</span>
                      <input type="text" class="form-control input" v-model="this.name">
                    </div>
                    <span class="text-danger fw-bold error" v-if="errors.name">{{ errors.name.toString() }}</span>
                    <div class="forms-inputs mb-1 d-flex px-4">
                      <span class="col-3 label">Số điện thoại</span>
                      <input type="text" class="form-control input" v-model="this.phone" >
                    </div>
                    <span class="text-danger fw-bold error" v-if="errors.phone">{{ errors.phone.toString() }}</span>
                    <div class="forms-inputs mb-1 d-flex px-4">
                      <span class="col-3 label">Email</span>
                      <input type="text" v-model="email" class="form-control input"/>
                    </div>
                    <span class="text-danger fw-bold error" v-if="errors.email">{{ errors.email.toString() }}</span>
                    <div class="forms-inputs mb-1 d-flex px-4">
                      <span class="col-3 label">Password</span>
                      <input type="password" v-model="password" class="form-control input" />
                    </div>
                    <span class="text-danger fw-bold error" v-if="errors.password && errors.password[0]">{{ errors.password[0].toString() }}</span>
                    <span class="text-danger fw-bold error" v-if="errors.password && errors.password[1]">{{ errors.password[1].toString() }}</span>
                    <div class="forms-inputs mb-1 d-flex px-4">
                      <span class="col-3 label">Xác nhận mật khẩu</span>
                      <input type="password" class="form-control input" v-model="this.password_confirmation">
                    </div>
                    <span class="text-danger fw-bold error" v-if="errors.password && errors.password[0]">{{ errors.password[0].toString() }}</span>
                    <span class="text-danger fw-bold error" v-if="errors.password && errors.password[1]">{{ errors.password[1].toString() }}</span>
                    <span class="text-danger fw-bold error" v-if="errors.length > 0">{{ errors }}</span>
                    <div class="mb-3 mt-2">
                      <button class="btn btn-register" type="submit">Đăng ký</button>
                      <router-link to="/login" class="login-register float-start">Bạn đã có tài khoản?</router-link>
                     </div>
                  </div>
                </div>
              </div>
          </form>
        </div>
      </div>
      <div class="card w-50 float-start opacity-75 col-6" style="height: 100%;">
        <img src="../assets/bg-login.jpg" alt="" class="bg-transparent" style="height: 564px;">
      </div>
    </div>
  </div>
</template>

<script>
import axios from "@/axios";

export default {
  data() {
    return {
      name: '',
      phone: '',
      email: '',
      password: '',
      password_confirmation: '',
      errors: {}
    }
  },
  methods: {
    async register() {
      try {
        const response = await axios.post('/register', {
          name: this.name,
          phone: this.phone,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        });
        console.log(response);
        this.$router.push('/login');
      } catch (e) {
        this.errors = e.response.data.errors
        console.log(this.errors);
      }
    }
  }
}
</script>

<style scoped>
.register-page {
  background-color: snow;
}

.container {
  padding: 0 21px;
  margin-bottom: 20px;
  color: #77203d;
  font-family: "Playwrite NG Modern", cursive;
}
#form1 {
  padding: 0;
}
.label {
  margin-top: 12px;
  font-weight: bold;
  color: #77203d;
  width: 218px;
  padding-left: 0px;
}
.input {
  width: 280px;
  padding: 0px;
  height: 40px;
}
.error {
  margin-left: 240px;
}
.btn-register {
  background-color: rgb(224, 203, 215);
  width: 497px;
  margin-left: 25px;
  padding: 6px 0px;
}

.btn-register:hover {
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
