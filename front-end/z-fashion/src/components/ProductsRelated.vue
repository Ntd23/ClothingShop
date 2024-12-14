<template>
  <div class="related container">
    <router-link :to="`/product/details/${product.id}`" v-for="(product, index) in productsRelated" :key="index">
      <a-card hoverable style="width: 240px; display: inline-block; margin-right: 24px;"
        @click="$router.push(`/product/details/${product.id}`)">
        <template #cover>
          <img :src="product.images[0].url" :alt="product.name">
        </template>
        <a-card-meta :title="product.name">
          <template #description><strong>{{
            parseInt(product.selling_price).toLocaleString(
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
import { onMounted, ref, watch } from "vue";
const productsRelated = ref([]);
const props = defineProps({
  id: {
    type: String,
    required: true
  },
  name: {
    type: String,
    required: true,
  },
})
const getProductsRelated = () => {
  axios
    .get(`http://127.0.0.1:8000/product/related/${props.name}/${props.id}`)
    .then((res) => {
      productsRelated.value = res.data;
    })
    .catch((err) => {
      console.log(err);
    });
};
watch([() => props.id, () => props.name], () => {
  getProductsRelated();
})
onMounted(() => {
  getProductsRelated();
});
</script>

<style scoped>
.related {
  padding: 20px 0px;
  display: inline-flex;
  justify-content: start;
}
</style>
