<template>
  <div class="container">
    <a-breadcrumb>
      <a-breadcrumb-item><router-link :to="{path: '/'}">Trang chủ</router-link></a-breadcrumb-item>
          <a-breadcrumb-item><router-link :to="`/clothing/${root_name}`">{{ root_name }}</router-link></a-breadcrumb-item>
          <a-breadcrumb-item>{{nameOfCategory.toString()}}</a-breadcrumb-item>
    </a-breadcrumb>
    <div class="product">
      <a-card hoverable style="width: 240px; display: inline-block" v-for="(product, index) in products" :key="index">
        <template #cover>
          <img :alt="product.name" :src="product.images[0].url" :title="product.name" />
        </template>
        <router-link :to="`/product/details/${product.id}`">
          <a-card-meta :title="product.name">
            <template #description><strong>{{ parseInt(product.price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }) }}</strong></template>
          </a-card-meta>
        </router-link>
      </a-card>
    </div>
  </div>
</template>

<script setup>
import axios from '@/axios';
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';

const route= useRoute();
const products= ref([]);
const nameOfCategory= ref('');
const cate_id= ref(route.params.cate_id);
const root_name= ref(route.params.name);
const getCategoryById= ()=> {
  axios.get(`http://127.0.0.1:8000/category/${cate_id.value}`)
  .then(res=> {
    nameOfCategory.value= res.data;
    console.log(nameOfCategory.value);
  })
};
const getProductByCategory= ()=> {
  axios.get(`http://127.0.0.1:8000/products/${cate_id.value}`)
  .then(res=> {
    products.value= res.data;
  })
  .catch(err=> console.log(err))
};
watch(()=> route.params.cate_id, (newCateId)=> {
  cate_id.value= newCateId;
  getCategoryById();
  getProductByCategory();
})
onMounted(()=> {
  getCategoryById();
  getProductByCategory();
})
</script>

<style scoped>
.product {
  padding: 20px 0px;
  display: inline-flex;
  justify-content: space-between;
  gap: 24px;
  flex-wrap: wrap;
}
</style>
