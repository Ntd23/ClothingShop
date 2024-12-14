<template>
  <div class="col-10 px-4 py-4 sub-profile">
    <form @submit.prevent="changePassword('success')" class="mx-5">
      <input type="hidden" v-model="user_id" />
      <div class="mb-3 row">
        <label class="form-label w-25 fw-bold">Mật khẩu hiện tại</label>
        <a-input-password class="w-25" v-model:value="password.current_password" />
        <span class="text-danger w-50" v-if="validated && validated.current_password">{{
          String(validated.current_password) }}</span>
        <span class="text-danger w-50" v-if="currPasswordInvalid">{{
          String(currPasswordInvalid) }}</span>
      </div>
      <div class="mb-3 row">
        <label class="form-label w-25 fw-bold">Mật khẩu mới</label>
        <a-input-password class="w-25" v-model:value="password.new_password" />
        <span class="text-danger w-50" v-if="validated && validated.new_password">{{
          String(validated.new_password) }}</span>
      </div>
      <div class="mb-3 row">
        <label class="form-label w-25 fw-bold">Xác nhận mật khẩu</label>
        <input type="password" v-model="password.new_password_confirmation" class="form-control"
          style="width: 184px;height: 32px;" />
        <span class="text-danger w-50" v-if="validated && validated.new_password_confirmation">{{
          String(validated.new_password_confirmation) }}</span>
      </div>
      <div class="mb-3 row d-grid" style="margin-left: 200px;">
        <button type="submit" class="btn btn-danger w-25">Xác nhận</button>
      </div>
    </form>
    <div class="overlay" v-if="loading">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { notification } from 'ant-design-vue';
import { computed, ref } from 'vue';
import { useStore } from 'vuex';
import auth from "@/axios";
const store = useStore();
const user_id = computed(() => {
  const user = store.state.user?.user || store.state.user;
  return user?.id || '';
});
const password = ref({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})
const validated = ref({});
const currPasswordInvalid = ref('');
const token = localStorage.getItem("token");
auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
const loading = ref(false);
const changePassword = (type) => {
  auth.put('http://127.0.0.1:8000/re-password', {
    user_id: user_id.value,
    current_password: password.value.current_password,
    new_password: password.value.new_password,
    new_password_confirmation: password.value.new_password_confirmation
  })
    .then(res => {
      loading.value = true;
      setTimeout(() => {
        notification[type]({
          message: res.data[0],
        });
        loading.value = false;
      }, 2000);
    })
    .catch(err => {
      console.log(err);
      if (err.status == 422) {
        validated.value = err.response.data.errors;
      }
      if (err.status == 400) {
        currPasswordInvalid.value = err.response.data[0];
      }
    })
};
</script>

<style>
.sub-profile {
  background-color: #ffd8bf;
}
</style>
