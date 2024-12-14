<template>
  <div class="men container">
    <router-link v-for="(men, index) in mens" :key="index" :to="`/product/details/${men.id}`">
      <a-card hoverable style="width: 240px; display: inline-block">
        <template #cover>
          <img :alt="men.name" :src="men.images[0].url" :title="men.name" />
        </template>
        <a-card-meta :title="men.name">
          <template #description><strong>{{ parseInt(men.selling_price).toLocaleString('vi-VN', {
            style: 'currency',
            currency: 'VND'
          }) }}</strong></template>
        </a-card-meta>
      </a-card>
    </router-link>
  </div>
</template>

<script setup>
import axios from '@/axios';
import { onMounted, ref } from 'vue';
const mens = ref([]);
const getClothingMens = () => {
  axios.get('http://localhost:8000/mens', {
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
    .then(res => {
      mens.value = res.data;
    })
    .catch(err => {
      console.log(err);
    })
};
onMounted(() => {
  getClothingMens()
});
</script>

<style scoped>
.men {
  padding: 20px 0px;
  display: inline-flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
}
</style>
