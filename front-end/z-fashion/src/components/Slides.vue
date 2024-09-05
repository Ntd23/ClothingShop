<template>
  <div class="slides">
    <a-carousel :after-change="onChange">
      <div v-for="(image, index) in images" :key="index">
        <img :src="image" alt="" style="width: 100%;max-height: 433px;">
      </div>
    </a-carousel>
  </div>
</template>

<script setup>
import axios from "@/axios";
import { onMounted, ref } from 'vue';
const onChange = current => {
  console.log(current);
};
const images= ref();
const getSlides= ()=> {
  axios.get(`http://localhost:8000/slide/show`)
  .then(res=> {
    images.value= res.data;
  })
  .catch(err=> console.log(err))
}
onMounted(()=> {
  getSlides();
})
</script>

<style scoped>
.slides {
  padding: 20px 0px;
}
:deep(.slick-slide) {
  text-align: center;
  height: auto;
  overflow: hidden;
}
:deep(.slick-slide img) {
  color: #472828;
}
:deep(.slick-track) {
  width: 1296px;
}
:deep(.slick-dots) {
  margin-top: -28px;
  position: static;
}
</style>
