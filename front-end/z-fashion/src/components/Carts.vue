<template>
  <div class="container py-5">
    <a-breadcrumb>
      <a-breadcrumb-item><router-link :to="{ path: '/' }">Trang chủ</router-link></a-breadcrumb-item>
      <a-breadcrumb-item>Giỏ hàng</a-breadcrumb-item>
    </a-breadcrumb>
    <div class="total-price-cart mt-5">
      Thanh toán: <span> {{ totalPriceInCart.toLocaleString('vi-VN') + ' VND' }}</span>
      <button type="submit" class="btn btn-danger mx-5" @click.prevent="payment">Thanh toán ngay</button>
      <button type="submit" @click="confirmClearAll(user_id)" class="btn btn-outline-danger" style="float: right;">Xóa
        tất cả</button>
    </div>
    <div class="carts">
      <a-alert :message="store.state.message_success"
        v-if="store.state.message_success && store.state.message_success !== null" type="success" show-icon
        close-text="Đóng" class="my-2" />
      <table>
        <thead style="background: snow;">
          <tr>
            <th>Hình ảnh</th>
            <th width="400px">Sản phẩm</th>
            <th width="220px">Giá</th>
            <th>Số lượng</th>
            <th>Tổng</th>
            <th>Xóa</th>
          </tr>
        </thead>
        <tbody v-if="paginatedCarts.length > 0">
          <tr v-for="cart in paginatedCarts" :key="cart.id">
            <td><img :src="cart.product_carts[0].product.images[0].url" width="100%"></td>
            <td>
              <div class="product">
                <span class="name">{{ cart.product_carts[0].product.name }}</span><br>
                <span>Mã sản phẩm: {{ cart.product_carts[0].product.id }}</span><br>
                <span>Kích thước:
                  <select v-model="cart.size">
                    <option
                      v-for="(size, index) in JSON.parse(cart.product_carts[0].product.product_details[0].size).split(',')"
                      :key="index" :selected="cart.size === size ? true : false" :value="size">
                      {{ size }}
                    </option>
                  </select>
                  | Màu sắc: <select v-model="cart.color">
                    <option
                      v-for="(color, index) in JSON.parse(cart.product_carts[0].product.product_details[0].color).split(',')"
                      :key="index" :selected="cart.color === color ? true : false" :value="color">
                      {{ color }}
                    </option>
                  </select>
                </span>
                <br>
                <span class="inventory">Còn lại: {{ cart.product_carts[0].product.inventory }}</span>
              </div>
            </td>
            <td>
              <div class="price">
                <span>{{ parseInt(cart.product_carts[0].product.selling_price).toLocaleString('vi-VN') + ' VND'
                  }}</span>
              </div>
            </td>
            <td>
              <div class="quantity">
                <span>
                  <input type="number" min="1" v-model="cart.quantity" style="width: 100px;">
                </span>
              </div>
            </td>
            <td>
              <div class="total">
                <span>
                  {{ parseInt(totalPrice(cart)).toLocaleString('vi-VN') + ' VND' }}
                </span>
              </div>
            </td>
            <td>
              <div class="remove">
                <DeleteFilled @click="confirmDelete(cart.id, 'success')" />
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="paginate">
        <a-pagination v-model:current="currentPage" :total="carts.length" show-less-items :pageSize="pageSize"
          @change="pageChanged" />
      </div>
    </div>
    <div class="overlay" v-if="loading">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>

</template>

<script setup>
import { notification } from 'ant-design-vue';
import { DeleteFilled } from '@ant-design/icons-vue';
import { Modal } from "ant-design-vue";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";
import { createVNode } from "vue";
// import axios from '@/axios';
import auth from "@/axios";
import { ref, onMounted, computed, watchEffect } from 'vue';
import { useStore, mapState } from 'vuex';
import router from '@/router';

const loading = ref(false);
const store = useStore();
const currentPage = ref(1);
const pageSize = ref(5);
const pageChanged = (page) => {
  currentPage.value = page;
};
const paginatedCarts = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return carts.value.slice(start, end);
});
const token = localStorage.getItem("token");
auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
const user_id = computed(() => {
  const user = store.state.user?.user || store.state.user;
  return user?.id || '';
});
const carts = ref([]);
const getCarts = () => {
  auth.get(`http://127.0.0.1:8000/carts/${user_id.value}`)
    .then(res => {
      carts.value = res.data[0];
      console.log(carts.value);

    })
    .catch(e => {
      console.log(e);
    })
}
const totalPrice = (cart) => {
  let price = 0;
  if (Array.isArray(cart.product_carts) && cart.product_carts.length > 0) {
    price = cart.product_carts[0].product.selling_price * cart.quantity;
  }
  return price;
};
let totalPriceInCart = computed(() => {
  return carts.value.reduce((sumCurrent, sumNext) => {
    return sumCurrent + totalPrice(sumNext);
  }, 0);
});
const confirmDelete = (id, type) => {
  Modal.confirm({
    title: " Bạn muốn xóa sản phẩm này khỏi giỏ hàng?",
    icon: createVNode(ExclamationCircleOutlined),
    okText: "Đồng ý",
    okType: "danger",
    cancelText: "Hủy",
    onOk() {
      auth.delete(`http://127.0.0.1:8000/cart/${id}`)
        .then((res) => {
          carts.value = carts.value.filter(
            (cart) => cart.id !== id
          );
          store.commit("DELETE_QUANTITY_CART");
          loading.value = true;
          setTimeout(() => {
            notification[type]({
              message: res.data.message,
            });
            loading.value = false;
          }, 2000);
        })
        .catch(e => {
          console.log(e);
        })
    },
    onCancel() {
      console.log("Cancel");
    },
  });
};
const confirmClearAll = (user_id) => {
  Modal.confirm({
    title: " Bạn muốn xóa tất cả sản phẩm khỏi giỏ hàng?",
    icon: createVNode(ExclamationCircleOutlined),
    okText: "Đồng ý",
    okType: "danger",
    cancelText: "Hủy",
    onOk() {
      auth.delete(`http://127.0.0.1:8000/cart/clear_all/${user_id}`)
        .then(res => {
          carts.value = [];
          store.commit('SET_MESSAGE', res.data.message);
        })
        .catch(e => {
          console.log(e);
        })
    },
    onCancel() {
      console.log("Cancel");
    },
  });
};
const payment = () => {
  const cartsToUpdate = paginatedCarts.value.map(cart => ({
    user_id: user_id.value,
    cart_id: cart.id,
    size: cart.size,
    color: cart.color,
    quantity: cart.quantity
  }));
  auth.put(`http://127.0.0.1:8000/cart`, { carts: cartsToUpdate })
    .then(() => {
      router.push('/payment');
    })
    .catch(err => {
      console.log(err);
    })
}
onMounted(() => {
  getCarts();
})
watchEffect(() => {
  setTimeout(() => {
    if (store.state.message_success) {
      store.commit("CLEAR_MESSAGE");
    }
  }, 2000);
})
computed(() => [
  ...mapState(["message_success"]),
])
</script>

<style scoped>
.total-price-cart {
  font-weight: bolder;
  font-size: larger;
}

.total-price-cart>span {
  color: red;
}

.carts {
  padding: 20px 0;
}

table {
  width: 100%;
  font-size: large;
  table-layout: fixed;
  border-collapse: collapse;
}

thead {
  font-weight: bold;
  height: 50px;
}

thead>tr>td {
  text-align: center;
  text-overflow: ellipsis;
}

th,
td {
  border-bottom: 1px solid #ddd;
}

th {
  padding: 0 20px;
}

td {
  border-left: 1px solid #ddd;
  border-right: 1px solid #ddd;
}

.product {
  width: 400px;

}

.remove {
  color: rgb(245, 28, 28);
}

.name {
  font-weight: 700;
}

.inventory {
  color: #ccc;
}

.product,
.price,
.quantity,
.total,
.remove {
  margin-top: -45px;
  padding: 0 20px;
}

.anticon>svg {
  width: 2em;
  height: 2em;
}

.paginate {
  text-align: center;
  padding: 20px 0;
}
</style>
