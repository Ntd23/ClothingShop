<template>
  <div class="col-10 px-4 py-4 sub-profile">
    <div class="d-flex justify-content-between align-content-center">
      <h3 class="card-title">Địa chỉ của tôi</h3>
      <button class="btn btn-outline-danger" @click="showModal">Thêm địa chỉ</button>
    </div>
    <div class="card-body mt-4" style="height: 400px;" v-if="paginatedUser.length > 0">
      <div class="card-title d-flex justify-content-between align-content-center mt-4 hr-dashed"
        v-for="(user, index) in paginatedUser" :key="index">
        <div>
          {{ user.fullname }} - {{ user.phone }}
          <p>{{ user.address }}</p>
        </div>
        <div class="d-flex w-25">
         <UpdateAddress :user="user" @address-updated="getAddress" />
         <DeleteAddress :id="user.id" @address-deleted="getAddress"/>
        </div>
      </div>
    </div>
    <a-modal v-model:open="open" @ok="addAddress('success')">
      <form @submit.prevent="addAddress('success')" style="margin-top: 16px;">
        <input type="hidden" v-model="user_id" />
        <div class="user-detail row mb-1">
          <label class="form-label w-25 fw-bold">Họ tên</label>
          <a-input class="w-50" v-model:value="user.fullname" style="height: 32px;" />
          <p class="text-danger w-50 error" v-show="validated && validated.fullname">{{
            String(validated.fullname) }}</p>
        </div>
        <div class="user-detail row mb-1">
          <label class="form-label w-25 fw-bold">Email</label>
          <a-input class="w-50" v-model:value="user.email" style="height: 32px;" />
          <span class="text-danger w-50 error" v-if="validated && validated.email">{{
            String(validated.email) }}</span>
        </div>
        <div class="user-detail row mb-1">
          <label class="form-label w-25 fw-bold">Số điện thoại</label>
          <a-input class="w-50" style="height: 32px;" v-model:value="user.phone" />
          <span class="text-danger w-50 error" v-if="validated && validated.phone">{{
            String(validated.phone) }}</span>
        </div>
        <div class="user-detail row mb-1">
          <label class="form-label w-25 fw-bold">Địa chỉ</label>
          <a-textarea class="w-50" style="height: 32px;" v-model:value="user.address" auto-size />
          <span class="text-danger w-50 error" v-if="validated && validated.address">{{
            String(validated.address) }}</span>
        </div>
      </form>
    </a-modal>
    <div class="paginate">
        <a-pagination v-model:current="currentPage" :total="user_details.length" show-less-items :pageSize="pageSize"
          @change="pageChanged" />
      </div>
    <div class="overlay" v-if="loading">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { notification } from 'ant-design-vue';
import auth from "@/axios";
import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import UpdateAddress from './UpdateAddress.vue';
import DeleteAddress from './DeleteAddress.vue';
const store = useStore();
const currentPage = ref(1);
const pageSize = ref(5);
const pageChanged = (page) => {
  currentPage.value = page;
};
const paginatedUser = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return user_details.value.slice(start, end);
});
const user_id = computed(() => {
  const user = store.state.user?.user || store.state.user;
  return user?.id || '';
});
const token = localStorage.getItem("token");
auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
const user = ref({
  fullname: '',
  email: '',
  phone: '',
  address: ''
});
const user_details = ref([]);
const validated = ref({});
const open = ref(false);
const showModal = () => {
  open.value = true;
};
const loading = ref(false);
const addAddress = (type) => {
  auth.post('http://127.0.0.1:8000/profile/address', {
    user_id: user_id.value,
    fullname: user.value.fullname,
    email: user.value.email,
    phone: user.value.phone,
    address: user.value.address
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
      getAddress()
    })
    .catch(err => {
      if (err.status === 422) {
        validated.value = err.response.data.errors;
      }
    })
}
const getAddress = () => {
  auth.get(`http://127.0.0.1:8000/profile/address/${user_id.value}`)
    .then(res => {
      user_details.value = res.data[0];
    })
};
onMounted(() => {
  getAddress();
})
</script>

<style>
.sub-profile {
  background-color: #ffd8bf;
  height: 600px;
}

.user-detail {
  height: 50px;
}

.error {
  margin-left: 116px;
}

.hr-dashed {
  border-bottom: 1px dashed #ffa39e;
}

.btn-modify {
  font-size: x-large;
  margin: 0 12px;
  width: 35px;
  height: 35px;
}

.paginate {
  text-align: center;
  margin-top: 46px;
}
</style>
