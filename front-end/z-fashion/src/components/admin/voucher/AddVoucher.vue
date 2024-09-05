<template>
  <div class="col-10 position-absolute" style="top: 10%; left: 120%;width: 1192px;">
    <div class="card">
      <div class="card">
        <div class="card-header">
          <div class="card-title text-uppercase">Thêm khuyến mãi</div>
        </div>
        <div class="card-body">
          <a-button type="primary" ><router-link to="/admin/vouchers">Quay lại</router-link></a-button>
          <form @submit.prevent="addVoucher" class="mt-3" style="border: 1px solid #ccc">
            <div class="row m-3">
              <div class="row">
                <div class="col">
                  <label for="">Mã khuyến mãi</label>
                  <input type="text" class="form-control" v-model="voucher.code">
                  <span class="text-danger" v-if="errors.code">{{errors.code.toString()}}</span>
                </div>
                <div class="col">
                  <label for="">Lượt dùng tối đa</label>
                  <input type="number" class="form-control" v-model="voucher.use_max">
                  <span class="text-danger" v-if="errors.use_max">{{errors.use_max.toString()}}</span>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="">Ngày bắt đầu</label>
                  <input type="date" class="form-control" v-model="voucher.start_date">
                  <span class="text-danger" v-if="errors.start_date">{{errors.start_date.toString()}}</span>
                </div>
                <div class="col">
                  <label for="">Ngày kết thúc</label>
                  <input type="date" class="form-control" v-model="voucher.end_date">
                  <span class="text-danger" v-if="errors.end_date">{{errors.end_date.toString()}}</span>
                </div>
              </div>
            </div>
            <div class="row col-sm-2 m-auto pb-3">
              <a-button type="primary" html-type="submit" >Thêm khuyến mãi</a-button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <router-view/>
  </div>
</template>

<script setup>
import auth from "@/axios";
import router from "@/router";
import store from "@/store";
import { ref } from 'vue';
const voucher= ref({
  code: '',
  start_date: '',
  end_date: '',
  use_max: ''
});
const errors= ref({});
const addVoucher= ()=> {
  let formData= new FormData();
  formData.append('code', voucher.value.code);
  formData.append('start_date', voucher.value.start_date);
  formData.append('end_date', voucher.value.end_date);
  formData.append('use_max', voucher.value.use_max);
  const token = localStorage.getItem("token");
  auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  auth.post('/voucher', formData)
  .then(res=> {
    router.push('/admin/vouchers');
    if(res.data.message!==undefined) {
      store.commit('SET_MESSAGE', res.data.message);
    }
  })
  .catch(err=> {
    errors.value= err.response.data.errors;
  })
};
</script>
