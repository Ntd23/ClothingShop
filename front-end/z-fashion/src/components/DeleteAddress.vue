<template>
  <div>
    <DeleteOutlined @click="showModal" />
    <a-modal v-model:open="open" @ok="deleteAddress('success')">
      <div class="card-title">Bạn muốn xóa địa chỉ này?</div>
    </a-modal>
    <div class="overlay" v-if="loading">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { DeleteOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';
import { ref } from 'vue';
import auth from "@/axios";

const open = ref(false);
const loading = ref(false);
const showModal = () => {
  open.value = true;
};
const props= defineProps({
  id: {
    type: null,
    required: true
  }
})
const emit= defineEmits(['addressDeleted'])
const token = localStorage.getItem("token");
auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
const deleteAddress= (type)=> {
  auth.delete(`http://127.0.0.1:8000/profile/address/${props.id}`)
  .then(res=> {
    loading.value = true;
      open.value = false;
      setTimeout(() => {
        notification[type]({
          message: res.data,
        });
        loading.value = false;
      }, 2000);
      emit('addressDeleted');
  })
};
</script>