<template>
  <div class="container py-5">
    <a-breadcrumb>
      <a-breadcrumb-item><router-link :to="{ path: '/' }">Trang chủ</router-link></a-breadcrumb-item>
      <a-breadcrumb-item>Thanh toán</a-breadcrumb-item>
    </a-breadcrumb>
    <form>
      <div class="carts">
        <div class="d-flex justify-content-between bill-payment row">
          <div class="payment col-7">
            <div class="payment-details">
              <div class="payment-order">
                <div class="d-flex">
                  <h2 class="icon-address">
                    <EnvironmentOutlined />
                  </h2>
                  <h2>Địa chỉ giao hàng</h2>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-6 user-info">
                  <p>Họ tên <span class="required">*</span></p>
                  <input type="text" v-model="user.fullname" class="form-control" placeholder="Nhập họ tên của bạn" />
                  <span class="text-danger" v-show="errorFormat.receive_user">{{
                    String(errorFormat.receive_user)
                  }}</span>
                </div>
                <div class="col-6 user-info">
                  <p>Email <span class="required">*</span></p>
                  <input type="text" v-model="user.email" class="form-control" placeholder="Nhập email của bạn" />
                  <span class="text-danger" v-show="errorFormat.email">{{
                    String(errorFormat.email)
                  }}</span>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-12 user-info">
                  <p>Số điện thoại<span class="required">*</span></p>
                  <input type="text" v-model="user.phone" class="form-control"
                    placeholder="Nhập số điện thoại của bạn" />
                  <span class="text-danger" v-show="errorFormat.phone">{{
                    String(errorFormat.phone)
                  }}</span>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-12 user-info">
                  <p>Địa chỉ<span class="required">*</span></p>
                  <input type="text" v-model="user.address" class="form-control" placeholder="Nhập địa chỉ của bạn" />
                  <span class="text-danger" v-show="errorFormat.address">{{
                    String(errorFormat.address)
                  }}</span>
                </div>
              </div>
              <div class="d-flex">
                <h2 class="icon-payment">
                  <CreditCardOutlined />
                </h2>
                <h2 class="mt-5">Phương thức thanh toán</h2>
              </div>
              <div class="payment-methods">
                <input type="radio" v-model="payment_methods" value="direct_payment" />
                <span>Thanh toán khi nhận hàng</span><br />
                <input type="radio" v-model="payment_methods" value="momo" />
                <span>Momo</span><br />
                <span class="text-danger" v-show="errorFormat.payment_method">{{
                  String(errorFormat.payment_method)
                }}</span>
              </div>
            </div>
          </div>
          <div class="bill col-5">
            <div class="bill-details">
              <h2>Đơn hàng</h2>
              <div class="voucher">
                <h4>Mã khuyễn mãi</h4>
                <div class="row" style="padding: 0 13px">
                  <input v-model="code" @input="isApplyVoucher = false" type="text" placeholder="Nhập mã khuyến mãi"
                    class="form-control" style="width: 250px" />
                  <button @click.prevent="checkVoucher" :disabled="isApplyVoucher" type="submit" class="btn btn-danger"
                    style="width: 106px">
                    ÁP DỤNG
                  </button>
                </div>
                <span class="text-danger" v-show="msg.length > 0">{{
                  msg
                }}</span><br /><br />
                <div class="divider mt-2"></div>
              </div>
              <div class="total-price-cart">
                Tạm tính:
                <span style="color: black">
                  {{ totalPriceInCart.toLocaleString("vi-VN") + " VND" }}</span><br />
                Mã giảm giá:
                <span style="color: black">
                  -{{ reduction.toLocaleString("vi-VN") + " VND" }}</span><br /><br />
                <div class="divider"></div>
                <br />
                Tổng thanh toán:
                <span class="total-price">{{
                  totalPayment.toLocaleString("vi-VN") + " VND"
                }}</span>
                <br /><br />
                <div class="divider"></div>
                <div class="order">
                  <Order :user="user" :voucher_id="voucher_id" :payment_methods="payment_methods"
                    :totalPayment="totalPayment" :quantity="paginatedCarts.length" :errors="errors"
                    @update:error="updateError" @updateCart="updateCart" @checkOrderLoad="checkOrderLoad" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div style="height: 30px"></div>
        <div class="cart-table">
          <div class="d-flex">
            <h2 class="icon-cart">
              <ShoppingCartOutlined />
            </h2>
            <h2>Giỏ hàng</h2>
          </div>
          <table>
            <thead style="background: snow">
              <tr>
                <th>Hình ảnh</th>
                <th width="400px">Sản phẩm</th>
                <th width="220px">Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="cart in paginatedCarts" :key="cart.id">
                <td>
                  <img :src="cart.product_carts[0].product.images[0].url" width="100%" />
                </td>
                <td>
                  <div class="product">
                    <span class="name">{{
                      cart.product_carts[0].product.name
                    }}</span><br />
                    <span>Mã sản phẩm: {{ cart.product_carts[0].product.id }}</span><br />
                    <span>Kích thước:
                      <select v-model="cart.size">
                        <option v-for="(size, index) in JSON.parse(
                          cart.product_carts[0].product.product_details[0]
                            .size
                        ).split(',')" :key="index" :selected="cart.size === size ? true : false" :value="size">
                          {{ size }}
                        </option>
                      </select>
                      | Màu sắc:
                      <select v-model="cart.color">
                        <option v-for="(color, index) in JSON.parse(
                          cart.product_carts[0].product.product_details[0]
                            .color
                        ).split(',')" :key="index" :selected="cart.color === color ? true : false" :value="color">
                          {{ color }}
                        </option>
                      </select>
                    </span>
                    <br />
                    <span class="inventory">Còn lại:
                      {{ cart.product_carts[0].product.inventory }}</span>
                  </div>
                </td>
                <td>
                  <div class="price">
                    <span>{{
                      parseInt(
                        cart.product_carts[0].product.selling_price
                      ).toLocaleString("vi-VN") + " VND"
                    }}</span>
                  </div>
                </td>
                <td>
                  <div class="quantity">
                    <span>
                      <input disabled type="number" min="1" v-model="cart.quantity" style="width: 100px" />
                    </span>
                  </div>
                </td>
                <td>
                  <div class="total">
                    <span>
                      {{
                        parseInt(totalPrice(cart)).toLocaleString("vi-VN") +
                        " VND"
                      }}
                    </span>
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
      </div>
    </form>
    <div class="overlay" v-if="loading">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import Order from "./Order.vue";
import {
  EnvironmentOutlined,
  CreditCardOutlined,
  ShoppingCartOutlined,
} from "@ant-design/icons-vue";
import { ref, onMounted, computed } from "vue";
import auth from "@/axios";
import { useStore } from "vuex";
const store = useStore();
const user_id = computed(() => {
  const user = store.state.user?.user || store.state.user;
  return user?.id || "";
});
const token = localStorage.getItem("token");
auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
const carts = ref([]);
const code = ref("");
const voucher_id = ref(null);
const isApplyVoucher = ref(false);
const msg = ref([]);
const reduction = ref(0);
const totalPayment = computed(() => totalPriceInCart.value - reduction.value);
const currentPage = ref(1);
const pageSize = ref(5);
const pageChanged = (page) => {
  currentPage.value = page;
};
const getCarts = () => {
  auth
    .get(`http://127.0.0.1:8000/carts/${user_id.value}`)
    .then((res) => {
      carts.value = res.data[0];
    })
    .catch((e) => {
      console.log(e);
    });
};
const getUserInfo = () => {
  auth.get(`http://127.0.0.1:8000/user-info/${user_id.value}`).then((res) => {
    user.value.fullname = res.data.tendangnhap;
    user.value.email = res.data.email;
    user.value.phone = res.data.sodienthoai;
  });
};
const paginatedCarts = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return carts.value.slice(start, end);
});
const totalPrice = (cart) => {
  const price = cart.product_carts[0].product.selling_price * cart.quantity;
  return price;
};
let totalPriceInCart = computed(() => {
  return carts.value.reduce((sumCurrent, sumNext) => {
    return sumCurrent + totalPrice(sumNext);
  }, 0);
});
const checkVoucher = () => {
  if (isApplyVoucher.value) return;
  if (code.value) {
    isApplyVoucher.value = true;
    auth
      .get(
        `http://127.0.0.1:8000/check-voucher/${code.value}/${totalPriceInCart.value}`
      )
      .then((res) => {
        reduction.value = res.data[0];
        msg.value = res.data[1];
      })
      .catch((err) => {
        console.log(err);
      });
  }
};
const user = ref({
  fullname: "",
  email: "",
  phone: "",
  address: "",
});
const payment_methods = ref("");
const errors = ref({});
const loading = ref(false);
const updateError = (newError) => {
  errors.value = newError;
};
const errorFormat = computed(() => {
  return errors.value;
});
const updateCart = () => {
  carts.value = [];
};
const checkOrderLoad = () => {
  loading.value = true;
  setTimeout(() => {
    loading.value = false;
  }, 2500);
};

onMounted(() => {
  getCarts();
  getUserInfo();
});
</script>

<style scoped>
.bill-payment {
  background-color: snow;
}

.bill,
.payment {
  padding: 30px;
}

.bill-details,
.payment-details {
  background-color: white;
  padding: 20px;
}

.bill-details {
  width: 400px;
}

.divider {
  border: 2px dashed #c27979;
}

.user-info,
.payment-methods {
  height: 98px;
}

.user-info>p {
  margin: 0;
  text-transform: uppercase;
  font-size: large;
  font-weight: 400;
}

.required {
  color: red;
}

.icon-address {
  margin-top: -10px;
  padding-right: 10px;
}

.icon-payment {
  margin-top: 40px;
  padding-right: 10px;
}

.icon-cart {
  margin-top: -8px;
  padding-left: 54px;
  padding-right: 10px;
}

h2 {
  text-transform: uppercase;
}

.total-price-cart {
  font-weight: bolder;
  font-size: larger;
  margin-top: 16px;
}

.total-price-cart>span {
  color: red;
  float: right;
}

.carts {
  padding: 20px 0;
}

.cart-table {
  padding-top: 20px;
  background-color: snow;
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

.name {
  font-weight: 700;
}

.inventory {
  color: #ccc;
}

.product,
.price,
.quantity,
.total {
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
