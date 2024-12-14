<!-- <template>
  <div class="filter">
    <a-button type="primary" @click="showModal" style="background-color: #fff;">
      <MenuFoldOutlined style="color: black" />
    </a-button>
    <a-modal v-model:open="open" title="Chọn mức giá" :confirm-loading="confirmLoading" @ok="handleFilter">
      <form>
        <span>Từ</span>
        <input type="text" style="margin-left: 6px;" v-model="min_price">
        <span style="margin-left: 6px;">VND</span>
        <span style="margin-left: 10px;">Đến</span>
        <input type="text" style="margin-left: 6px;" v-model="max_price">
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
const min_price= ref(200000);
const max_price= ref(1000000);
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
    store.commit('SET_RESULT_FILTER', results.value);
  })
  .catch(err=> console.log(err))
  setTimeout(() => {
    open.value = false;
    confirmLoading.value = false;
    router.push('/filter');
  }, 2000);
};
</script>

<style>
.filter {
  margin-left: 16px;
  height: 35px;
}

MenuFoldOutlined {
  color: black;
}

input {
  margin-left: 6px;
}
</style> -->

<template>
  <form @submit.prevent="handleFilter">
    <div class="row">
      <div class="mb-3 col-4 d-flex align-baseline">
        <label class="form-label fs-4">Từ</label>
        <input type="number" class="form-control mx-3" v-model="min_price" /> <span class="fs-4">VND</span>
      </div>
      <div class="mb-3 col-4 d-flex align-baseline">
        <label class="form-label fs-4">Đến</label>
        <input type="number" class="form-control mx-3" v-model="max_price" /> <span class="fs-4">VND</span>
      </div>
      <div class="mb-3 col-4">
        <button type="submit" class="btn btn-outline-danger btn-group" style="line-height: 29px;height: 43px;">ÁP
          DỤNG</button>
      </div>
    </div>
  </form>
</template>

<script setup>
import axios from 'axios';
import router from '@/router';
import { ref } from 'vue';
import { useStore } from 'vuex';
const min_price = ref(200000);
const max_price = ref(500000);

const store = useStore();
const results = ref([]);

const handleFilter = () => {
  axios.get(`http://127.0.0.1:8000/filter/${min_price.value}/${max_price.value}`)
    .then(res => {
      results.value = res.data;
      store.commit('SET_RESULT_FILTER', results.value);
    })
    .catch(err => console.log(err))
  setTimeout(() => {
    router.push('/filter');
  }, 2000);
};

</script>
