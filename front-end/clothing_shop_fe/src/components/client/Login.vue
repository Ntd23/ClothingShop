<template>
  <div>
    <form @submit.prevent="login">
      <div class="mb-3">
        <label class="form-label">Email <span class="required">*</span></label>
        <input type="text" class="form-control" v-model="email" />
        <span class="text-danger" style="font-size: small;" v-if="errors.email">{{ errors.email.toString()
          }}</span>
      </div>
      <div class="mb-3">
        <label class="form-label">Mật khẩu<span class="required">*</span></label>
        <input type="password" class="form-control" v-model="password" />
        <span class="text-danger" style="font-size: small;" v-if="errors['password']">{{
      errors['password'].toString() }}</span>
      </div>
      <div class="col-mb-3" style="text-align: right">
        <span><a href="" data-bs-toggle="modal" data-bs-target="#registerModal"
            style="margin-right: 134px; font-size: 10px">Bạn chưa có tài khoản?</a></span>
        <button type="submit" class="btn btn-ok" style="margin-right: 10px">
          Đăng nhập
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Bỏ qua
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from "@/axios";
export default {
  data() {
    return {
      email: "",
      password: "",
      errors: {}
    };
  },
  methods: {
    // ...mapActions(['login']),
    async login() {
      try {
        const response = await axios.post("/login", {
          email: this.email,
          password: this.password
        });
        if(response.data.token) {
          localStorage.setItem('token', response.data.token);
        }
        this.$store.commit('LOGIN'); //gọi action lưu trạng thái login
        // this.$router.push("/");
      } catch (e) {
        console.log(e);
        if (e.response.data.errors) {
          const errors = e.response.data.errors;
          if (Object.keys(errors).length > 0) {
            this.errors = errors;
          }
        }
      }
    }
  },
}
</script>
