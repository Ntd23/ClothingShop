<template>
  <div v-if="props.isOpened && props.typeMode === 'edit'" @click="emit('close')" class="modal-overlay">
    <div class="modal-content" @click.stop>
      <button type="submit" class="btn btn-sm btn-dark" @click="emit('close')">Đóng</button>
      <button type="submit" class="btn btn-danger" @click.prevent="updateOrder">Xác nhận đặt hàng</button>
    </div>
  </div>
  <div v-if="props.isOpened && props.typeMode === 'delete'" @click="emits('close')" class="modal-overlay">
    <div class="modal-content" @click.stop>
      <button type="submit" class="btn btn-sm btn-dark" @click="emits('close')">Đóng</button>
      <button type="submit" class="btn btn-danger" @click.prevent="deleteOrder">Xóa đơn hàng</button>
    </div>
  </div>
</template>

<script setup>
import auth from "@/axios";
import { useStore } from "vuex";
const store = useStore();
const token = localStorage.getItem("token");
auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
const props = defineProps({
  isOpened: {
    type: Boolean,
    default: false
  },
  typeMode: {
    type: String,
    default: ''
  },
  id: {
    type: [String, null],
    default: ''
  }
})
const emit = defineEmits(['close', 'updated', 'deleted']);
function updateOrder() {
  auth.patch(`order/update/status/${props.id}`)
    .then((res) => {
      store.commit('SET_MESSAGE', res.data[1])
      emit('updated')
      emit('close')
      setTimeout(() => {
        store.commit('CLEAR_MESSAGE')
      }, 1200)
    })
    .catch(err => {
      console.log(err);
    })
}
function deleteOrder() {
  auth.delete(`order/${props.id}`)
    .then((res) => {
      console.log(res.data);
      store.commit('SET_MESSAGE', res.data)
      emit('close')
      emit('deleted')
      setTimeout(() => {
        store.commit('CLEAR_MESSAGE')
      }, 1200)
    })
    .catch(err => {
      console.log(err);
    })
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: rgba(0, 0, 0, 0.09);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  width: 300px;
  text-align: center;
}

button {
  margin-top: 10px;
}
</style>
