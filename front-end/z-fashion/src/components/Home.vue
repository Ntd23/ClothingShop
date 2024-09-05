<template>
  <div class="home">
    <div class="container pb-3">
      <span v-if="$route.path=='/'" style="color: #ccc">Trang chủ</span>
      <slides/>
      <br><br>
      <section>
        <div class="category">Ưu đãi nổi bật</div>
        <hr class="hr-line"><br><br>
        <voucher/>
      </section>
      <section>
        <div class="category">Sản phẩm nổi bật</div>
        <hr class="hr-line">
        <best-selling/>
      </section>
      <section>
        <div class="category">Quần áo nam</div>
        <hr class="hr-line">
        <men/>
      </section>
      <section>
        <div class="category">Quần áo nữ</div>
        <hr class="hr-line">
        <woman/>
      </section>
    </div>
    <hr style="margin: 0;" />
  </div>
</template>

<script setup>

import Slides from './Slides.vue';
import Voucher from './Voucher.vue';
import Men from './Men.vue';
import Woman from './Woman.vue';
import BestSelling from './BestSelling.vue';
</script>

<style scoped>
section {
  color: #77203d;
  font-family: "Playwrite NG Modern", cursive;
}
.category{
  font-family: "Playwrite NG Modern", cursive;
  font-size: 2rem;
  text-align: center;
  font-style: italic;
}
.hr-line{
    width: 10%;
    height: 1px;
    margin: auto;
    background-color: #77203d;
    border-radius: 20%;
    border: 1px solid;
}
</style>>
