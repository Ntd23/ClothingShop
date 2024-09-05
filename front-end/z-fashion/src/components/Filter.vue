<template>
  <div class="filter">
    <a-button type="primary" @click="showModal" style="background-color: #fff;">
      <MenuFoldOutlined style="color: black" />
    </a-button>
    <a-modal v-model:open="open" title="Chọn mức giá" :confirm-loading="confirmLoading" @ok="handleFilter">
      <form>
        <span>Từ</span>
        <input type="text" style="margin-left: 6px;" placeholder="200,000" v-model="min_price">
        <span style="margin-left: 6px;">VND</span>
        <span style="margin-left: 10px;">Đến</span>
        <input type="text" style="margin-left: 6px;" placeholder="1,000,000" v-model="max_price">
        <span style="margin-left: 6px;">VND</span>
      </form>
    </a-modal>


  </div>
</template>

<script setup>
import { MenuFoldOutlined } from '@ant-design/icons-vue';
import { ref } from 'vue';
import { useStore } from 'vuex';
import axios from 'axios';
import router from '@/router';
const open = ref(false);
const confirmLoading = ref(false);
const min_price= ref('');
const max_price= ref('');
const store= useStore();
const results= ref([]);
const showModal = () => {
  open.value = true;
};
const handleFilter = () => {
  confirmLoading.value = true;
  axios.get(`http://127.0.0.1:8000/filter/${min_price.value}/${max_price.value}`)
  .then(res=> {
    console.log(res.data);
    results.value= res.data;
  })
  .catch(err=> console.log(err))
  setTimeout(() => {
    open.value = false;
    confirmLoading.value = false;
  }, 2000);
  store.commit('SET_RESULT_FILTER', results.value);
  router.push('/filter');
};
</script>

<style>
.filter {
  margin-left: -18px;
  height: 35px;
}

MenuFoldOutlined {
  color: black;
}

input {
  margin-left: 6px;
}
</style>
