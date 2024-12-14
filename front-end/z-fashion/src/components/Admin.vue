<template>
  <div class="container p-0 m-0">
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 152px; height: 1300px">
      <span class="fs-4">{{ store.state.user.tendangnhap }}</span>
      <hr />
      <ul class="nav nav-pills flex-column mb-auto">
        <li>
          <RouterLink @click="onSelect(0)" to="/admin" class="nav-link text-white" :class="selected === 0 && 'active'">
            Thống kê
          </RouterLink>
        </li>
        <li>
          <RouterLink @click="onSelect(1)" to="/admin/categories" class="nav-link text-white"
            :class="selected === 1 && 'active'">
            Danh mục
          </RouterLink>
        </li>
        <li>
          <RouterLink @click="onSelect(2)" to="/admin/products" class="nav-link text-white"
            :class="selected === 2 && 'active'">
            Sản phẩm
          </RouterLink>
        </li>
        <li>
          <RouterLink @click="onSelect(3)" to="/admin/vouchers" class="nav-link text-white"
            :class="selected === 3 && 'active'">
            Vouchers
          </RouterLink>
        </li>
        <li>
          <RouterLink @click="onSelect(4)" to="/admin/slides" class="nav-link text-white"
            :class="selected === 4 && 'active'">
            Slides
          </RouterLink>
        </li>
        <li>
          <RouterLink @click="onSelect(5)" to="/admin/orders" class="nav-link text-white"
            :class="selected === 5 && 'active'">
            Đơn hàng
          </RouterLink>
        </li>
      </ul>
      <RouterView />
    </div>
  </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { useStore } from "vuex";

const store = useStore();
computed(() => store.state.user);
const selected = ref(0);
const onSelect = (index) => {
  selected.value = index;
};
</script>

<style>
a {
  text-decoration: none;
}
</style>
