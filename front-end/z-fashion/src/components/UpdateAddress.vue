<template>
  <div>
    <FormOutlined @click="showModal"/>
    <a-modal v-model:open="open" @ok="updateAddress('success')">
      <form @submit.prevent="addAddress('success')" style="margin-top: 16px;">
        <div class="user-detail row mb-1">
          <label class="form-label w-25 fw-bold">Họ tên</label>
          <a-input class="w-50" v-model:value="userChild.fullname" style="height: 32px;" />
          <p class="text-danger w-50 error" v-show="validated && validated.fullname">{{
            String(validated.fullname) }}</p>
        </div>
        <div class="user-detail row mb-1">
          <label class="form-label w-25 fw-bold">Email</label>
          <a-input class="w-50" v-model:value="userChild.email" style="height: 32px;" />
          <span class="text-danger w-50 error" v-if="validated && validated.email">{{
            String(validated.email) }}</span>
        </div>
        <div class="user-detail row mb-1">
          <label class="form-label w-25 fw-bold">Số điện thoại</label>
          <a-input class="w-50" style="height: 32px;" v-model:value="userChild.phone" />
          <span class="text-danger w-50 error" v-if="validated && validated.phone">{{
            String(validated.phone) }}</span>
        </div>
        <div class="user-detail row mb-1">
          <label class="form-label w-25 fw-bold">Địa chỉ</label>
          <a-textarea class="w-50" style="height: 32px;" v-model:value="userChild.address" auto-size />
          <span class="text-danger w-50 error" v-if="validated && validated.address">{{
            String(validated.address) }}</span>
        </div>
      </form>
    </a-modal>
    <div class="overlay" v-if="loading">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { FormOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import auth from "@/axios";
import { reactive, ref } from 'vue';

const open = ref(false);
const showModal = () => {
  open.value = true;
};
const loading= ref(false);
const token = localStorage.getItem("token");
auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
const user = defineProps({
  user: {
    type: Object,
    required: true
  }
})
const userChild = reactive({ ...user.user });
const validated = ref({});
const emit= defineEmits(['addressUpdated']);
const updateAddress = (type) => {
  auth.put('http://127.0.0.1:8000/profile/address/'+userChild.id, {
    fullname: userChild.fullname,
    email: userChild.email,
    phone: userChild.phone,
    address: userChild.address,
  })
    .then(res => {
      loading.value = true;
      open.value = false;
      setTimeout(() => {
        notification[type]({
          message: res.data[1],
        });
        loading.value = false;
      }, 2000);
      emit('addressUpdated');
    })
    .catch(err => {
      if (err.status === 422) {
        validated.value = err.response.data.errors;
      }
    })
}
</script>

<style scoped>
.user-detail {
  height: 50px;
}
.hidden-visually {
  position: absolute;
  overflow: hidden;
  white-space: nowrap;
  margin: 0;
  padding: 0;
  height: 1px;
  width: 1px;
  clip: rect(0 0 0 0);
  clip-path: inset(100%);
}
</style>