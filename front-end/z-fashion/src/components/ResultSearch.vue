<template>
  <div class="container">
    <h2 v-if="results.length>0">Kết quả tìm kiếm: {{results.length}}</h2><br>
    <div class="result" v-if="results.length>0">
      <a-card hoverable style="width: 240px; display: inline-block" v-for="(item, index) in results" :key="index">
        <template #cover>
          <img :alt="item.name" :src="item.images[0].url"/>
        </template>
        <router-link :to="`/product/details/${item.id}`">
          <a-card-meta :title="item.name">
            <template #description><strong>{{ parseInt(item.price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }) }}</strong></template>
          </a-card-meta>
        </router-link>
      </a-card>
    </div>
    <div class="result" v-else><h2>Kết quả tìm kiếm: 0</h2></div>
  </div>

</template>

<script setup>
import { computed } from 'vue';
import {useStore} from 'vuex';
const store= useStore();
const results= computed(()=> store.state.results_search);
</script>

<style scoped>
.result {
  padding: 20px 0px;
  display: inline-flex;
  justify-content: space-between;
  gap: 24px;
  flex-wrap: wrap;
}
</style>
