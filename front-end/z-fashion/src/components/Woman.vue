<template>
  <div class="woman container">
    <a-card hoverable style="width: 240px; display: inline-block" v-for="(woman, index) in women" :key="index">
      <template #cover>
        <img :alt="woman.name" :src="woman.images[0].url"  :title="woman.name" />
      </template>
      <router-link :to="`/product/details/${woman.id}`">
        <a-card-meta :title="woman.name">
          <template #description><strong>{{ parseInt(woman.price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }) }}</strong></template>
        </a-card-meta>
      </router-link>
    </a-card>
  </div>
</template>

<script setup>
import axios from '@/axios';
import { onMounted, ref } from 'vue';
const women= ref([]);
const getClothingWomen= ()=> {
  axios.get('http://localhost:8000/women')
  .then(res=> {
    women.value= res.data;
  })
  .catch(err=> {
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
