<script setup>
import auth from "@/axios";
import { reactive, ref, watch } from "vue";

const token = localStorage.getItem("token");
auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
let order = reactive({ data: [] });
const showModal = ref(false);
const props = defineProps({
  id: {
    type: String,
    required: false,
  },
});
watch(() => props.id, (id) => {
  getOrder(id);
})
const getOrder = (id) => {
  if (id) {
    auth.get(`http://127.0.0.1:8000/order/${id}`).then((res) => {
      order.data[0] = res.data;
      showModal.value = true;
    });
  }
};
const handleOk = () => {
  showModal.value = false;
}
</script>

<template>
  <div>
    <svg @click.prevent="getOrder(props.id)" class="btn-dark" style="width: 20px;;margin-top: 3px;cursor: pointer;"
      xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
    </svg>
    <a-modal v-model:open="showModal" aria-hidden="false" style="width: 700px;" @ok="handleOk">

      <div v-if="order.data.length > 0 && order.data[0][0]">
        <div class="row">
          <div class="col-6">
            <img :src="order.data[0][0].url" :alt="order.data[0][0].name" class="w-100">
          </div>
          <div class="col-6">
            <h3>{{ order.data[0][0].name }}</h3>
            <div class="quantity">
              <span>Số lượng: </span>
              <p>{{ order.data[0][0].quantity }}</p>
            </div>
            <br>
            <div class="color">
              <span>Màu sắc: </span>
              <p :style="`background-color:${order.data[0][0].color}`" class="colors">
              </p>
            </div>
            <div class="amount">
              <span>Tổng: </span>
              <p>{{ parseInt(order.data[0][0].amount).toLocaleString('vi-VN') + ' VND' }}</p>
            </div>
            <br>
            <div class="payment">
              <span>Thanh toán: </span>
              <p>{{ order.data[0][0].payment_method == 'direct_payment' ? 'Thanh toán khi nhận hàng' :
                order.data[0][0].payment_method }}</p>
            </div>
            <br>
          </div>
        </div>
      </div>
    </a-modal>
  </div>
</template>

<style>
span {
  padding-right: 10px;
}

.quantity,
.color,
.amount,
.payment {
  display: flex;
  height: 38px;
  justify-content: flex-start;
  font-size: x-large;
}

.amount>p {
  color: red;
}

.payment {
  margin-top: 26px;
  font-size: large;
}

.payment>p {
  margin-top: -6px;
  font-size: small;
  line-height: 43px;
}

.colors {
  width: 23px;
  height: 23px;
  border-radius: 50%;
  margin-top: 6px;
}
</style>
