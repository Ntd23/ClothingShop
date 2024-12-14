<template>
  <div class="woman container">
    <router-link v-for="(woman, index) in women" :key="index" :to="`/product/details/${woman.id}`">
      <a-card hoverable style="width: 240px; display: inline-block">
        <template #cover>
          <img :alt="woman.name" :src="woman.images[0].url" :title="woman.name" />
        </template>
        <a-card-meta :title="woman.name">
          <template #description><strong>{{ parseInt(woman.selling_price).toLocaleString('vi-VN', {
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
const women = ref([]);
const getClothingWomen = () => {
  axios.get('http://localhost:8000/women')
    .then(res => {
      women.value = res.data;
    })
    .catch(err => {
      console.log(err);
    })
};
onMounted(() => {
  getClothingWomen()
});
</script>

<style scoped>
.woman {
  padding: 20px 0px;
  display: inline-flex;
  justify-content: space-between;
}
</style>
