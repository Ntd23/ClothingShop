<template>
  <div class="search">
    <form class="px-4" style="margin-left: 567px;" @submit.prevent="search">
      <input type="text" class="input-search" placeholder="Tìm kiếm sản phẩm ..." v-model="keywords"/>
      <SearchOutlined style="position: absolute; right: 448px; top: 70%" />
    </form>
  </div>
</template>

<script setup>
import {SearchOutlined} from "@ant-design/icons-vue";
import axios from "axios";
import { useStore } from "vuex";
import { ref } from "vue";
import router from "@/router";

const store= useStore();
const keywords= ref('');
const results= ref([]);
const search= ()=> {
  axios.get(`http://127.0.0.1:8000/search/${keywords.value}`)
  .then(res=> {
    results.value= res.data;
    console.log(results.value);
    store.commit("SET_RESULT_SEARCH", results.value);
    router.push('/search');
  })
  .catch(err=> {
    console.log(err);
  })
}

</script>

<style scoped>
.search {
  margin-left: -48px;
}
.input-search {
  border-top: 12px transparent;
  border-left: 12px transparent;
  border-radius: 7px;
  font-size: 18px;
  font-weight: lighter;
  width: 350px;
  height: 35px;
  caret-color: #ccc;
  text-indent: 20x;
  padding-left: 35px; /* Tạo khoảng cách thụt vào cho văn bản và caret */
  text-indent: 20px;
}
.input-search::placeholder {
  padding-left: 10px;
  padding-top: 6px;
}
</style>
