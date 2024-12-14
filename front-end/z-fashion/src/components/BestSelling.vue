<template>
  <div class="men container">
    <router-link v-for="(bestSellingProduct, index) in bestSellingProducts" :key="index"
      :to="`/product/details/${bestSellingProduct.id}`">
      <a-card hoverable style="width: 240px; display: inline-block">
        <template #cover>
          <img :alt="bestSellingProduct.name" :src="bestSellingProduct.images[0].url"
            :title="bestSellingProduct.name" />
        </template>
        <a-card-meta :title="bestSellingProduct.name">
          <template #description><strong>{{
            parseInt(bestSellingProduct.selling_price).toLocaleString(
              "vi-VN",
              {
                style: "currency",
                currency: "VND",
              }
            )
          }}</strong></template>
        </a-card-meta>
      </a-card>
    </router-link>
  </div>
</template>

<script setup>
import axios from "@/axios";
import { onMounted, ref } from "vue";
const bestSellingProducts = ref([]);
const getBestSellingProducts = () => {
  axios
    .get("http://localhost:8000/best_selling")
    .then((res) => {
      bestSellingProducts.value = res.data;
    })
    .catch((err) => {
      console.log(err);
    });
};
onMounted(() => {
  getBestSellingProducts();
});
</script>

<style scoped>
.men {
  padding: 20px 0px;
  display: inline-flex;
  justify-content: space-between;
}
</style>
