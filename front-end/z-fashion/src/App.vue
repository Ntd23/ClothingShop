<template>
  <div class="p-0 m-0 w-100">
    <Chat v-if="!isAdminPage" />
    <router-view />
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useRoute } from "vue-router";
import Chat from "./components/Chat.vue";

const route = useRoute();

// Kiểm tra nếu đường dẫn hiện tại bắt đầu bằng "/admin"
const isAdminPage = computed(() => route.path.startsWith("/admin"));
</script>
<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>
