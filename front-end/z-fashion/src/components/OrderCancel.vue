<template>
  <div>
    <DeleteOutlined style="cursor: pointer" @click="showModal" />
    <a-modal v-model:open="open" @ok="cancelOrder('success')">
      <div class="card-title">Bạn muốn hủy đơn hàng này?</div>
    </a-modal>
    <div class="overlay" v-if="loading">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { DeleteOutlined } from "@ant-design/icons-vue";
import { notification } from "ant-design-vue";
import auth from "@/axios";
import { ref } from "vue";

const token = localStorage.getItem("token");
auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
const props = defineProps({
  id: {
    type: String,
    required: true,
  },
});
const emits = defineEmits(["cancel:order"]);
const open = ref(false);
const loading = ref(false);
const showModal = () => {
  open.value = true;
};
const cancelOrder = (type) => {
  auth.delete(`http://127.0.0.1:8000/order/${props.id}`).then((res) => {
    loading.value = true;
    open.value = false;
    setTimeout(() => {
      notification[type]({
        message: res.data,
      });
      loading.value = false;
      emits("cancel:order", props.id);
    }, 1200);
  });
};
</script>

<style scoped></style>
