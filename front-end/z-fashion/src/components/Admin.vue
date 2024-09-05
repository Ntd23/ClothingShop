<template>
  <div class="container p-0 m-0">
    <div class="navbar col-2 p-0 position-relative">
      <a-menu :checked="theme === 'dark'" v-model:openKeys="openKeys" v-model:selectedKeys="selectedKeys"
        style="width: 256px; height: 1000px" mode="inline" :theme="theme" :items="items">
      </a-menu>
      <div class="card position-absolute" style="width: 500px; top: 3%;left: 956px;">
        <div class="card-header">
          <div class="cart-title">{{ store.state.user.tendangnhap }}</div>
        </div>
      </div>
      <router-view></router-view>
    </div>
  </div>
</template>
<script setup>
import { computed, h, ref } from "vue";
import { useStore } from 'vuex'
const theme = ref("dark");
const selectedKeys = ref(["1"]);
const openKeys = ref(["sub1"]);
const items = ref([
  {
    key: "1",
    label: h(
      "a",
      { href: "/admin/categories", style: "text-decoration: none" },
      "Danh mục"
    ),
    title: "Danh mục",
  },
  {
    key: "2",
    label: h(
      "a",
      { href: "/admin/products", style: "text-decoration: none" },
      "Sản phẩm"
    ),
    title: "Sản phẩm",
  },
  {
    key: "3",
    label: h(
      "a",
      { href: "/admin/slides", style: "text-decoration: none" },
      "Slide"
    ),
    title: "Slide",
  },
  {
    key: "4",
    label: h(
      "a",
      { href: "/admin/vouchers", style: "text-decoration: none" },
      "Voucher"
    ),
    title: "Khuyến mãi",
  },
]);
const store = useStore();
computed(() => store.state.user)
</script>

<style>
a {
  text-decoration: none;
}
</style>
