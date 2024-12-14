<template>
  <div class="container">
    <a-breadcrumb>
      <a-breadcrumb-item><router-link :to="{ path: '/' }">Trang chủ</router-link></a-breadcrumb-item>
      <a-breadcrumb-item>{{ $route.params.name }}</a-breadcrumb-item>
    </a-breadcrumb>
    <div class="woman">
      <router-link v-for="(clothing, index) in clothings" :key="index" :to="`/product/details/${clothing.id}`">
        <a-card hoverable style="width: 240px; display: inline-block">
          <template #cover>
            <img :alt="clothing.name" :src="clothing.images[0].url" :title="clothing.name" />
          </template>
          <a-card-meta :title="clothing.name">
            <template #description><strong>{{ parseInt(clothing.selling_price).toLocaleString('vi-VN', {
              style:
                'currency', currency: 'VND'
            }) }}</strong></template>
          </a-card-meta>
        </a-card>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import axios from '@/axios';
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const name = ref(route.params.name);
const clothings = ref([]);
const getClothings = () => {
  axios.get('http://127.0.0.1:8000/clothing/' + name.value)
    .then(res => {
      clothings.value = res.data;
    })
    .catch(err => {
      console.log(err);
    })
}
watch(() => route.params.name, (newName) => {
  name.value = newName;
  getClothings();
})
onMounted(() => {
  getClothings();
})
</script>

<style scoped>
.woman {
  padding: 20px 0px;
  display: inline-flex;
  justify-content: space-between;
  gap: 24px;
  flex-wrap: wrap;
}
</style>
