<template>
  <div class="men container">
    <a-card hoverable style="width: 240px; display: inline-block" v-for="(bestSellingProduct, index) in bestSellingProducts" :key="index">
      <template #cover>
        <img :alt="bestSellingProduct.name" :src="bestSellingProduct.images[0].url" :title="bestSellingProduct.name" />
      </template>
      <router-link :to="`/product/details/${bestSellingProduct.id}`">
        <a-card-meta :title="bestSellingProduct.name">
          <template #description><strong>{{ parseInt(bestSellingProduct.price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }) }}</strong></template>
        </a-card-meta>
      </router-link>
    </a-card>
  </div>
</template>

<script setup>
import axios from '@/axios';
import { onMounted, ref } from 'vue';
const bestSellingProducts= ref([]);
const getBestSellingProducts= ()=> {
  axios.get('http://localhost:8000/best_selling')
  .then(res=> {
    bestSellingProducts.value= res.data;
  })
  .catch(err=> {
    console.log(err);
  })
};
onMounted(() => {
  getBestSellingProducts()
});
</script>

<style scoped>
.men {
  padding: 20px 0px;
  display: inline-flex;
  justify-content: space-between;
}
</style>
