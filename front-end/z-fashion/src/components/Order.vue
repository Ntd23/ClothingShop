<template>
  <div>
    <button class="btn btn-danger mt-3 w-100" @click.prevent="order('success')">
      ĐẶT HÀNG
    </button>
  </div>
</template>

<script setup>
import { notification } from "ant-design-vue";
import auth from "@/axios";
import router from "@/router";
import { computed, ref, watch } from "vue";
import { useStore } from "vuex";
const token = localStorage.getItem("token");
auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
const store = useStore();
const user_id = computed(() => {
  const user = store.state.user?.user || store.state.user;
  return user?.id || "";
});
const orderInfo = defineProps({
  user: {
    type: Object,
    required: true,
  },
  voucher_id: {
    type: null,
    required: false,
  },
  payment_methods: {
    type: String,
    required: true,
  },
  totalPayment: {
    type: Number,
    required: true,
  },
  errors: {
    type: Object,
    required: false,
  },
  quantity: {
    type: null,
    required: false,
  },
});
const validate = ref(orderInfo.errors || {});
watch(
  () => orderInfo.errors,
  (newError) => {
    validate.value = newError;
  }
);
const emit = defineEmits(["update:error", "updateCart", "checkOrderLoad"]);
const order = (type) => {
  emit("checkOrderLoad");
  auth
    .post(`http://127.0.0.1:8000/order`, {
      user_id: user_id.value,
      voucher_id: orderInfo.voucher_id,
      amount: orderInfo.totalPayment,
      receive_user: orderInfo.user.fullname,
      email: orderInfo.user.email,
      phone: orderInfo.user.phone,
      address: orderInfo.user.address,
      payment_method: orderInfo.payment_methods,
    })
    .then((res) => {
      if (orderInfo.payment_methods == "momo") {
        window.location.href = res.data.redirect;
      }

      emit("updateCart");
      setTimeout(() => {
        notification[type]({
          message: res.data[3],
        });
        store.commit("DELETE_QUANTITY_CART", orderInfo.quantity);
        router.push("/");
      }, 2500);
    })
    .catch((err) => {
      if (err.status == 422) {
        validate.value = err.response.data.errors;
        emit("update:error", validate.value);
      }
    });
};
</script>
