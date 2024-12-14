<template>
  <div>
    <div class="col-10 position-absolute" style="top: 10%; left: 12%; width: 1185px">
      <div class="card">
        <div class="card">
          <div class="card-header">
            <div class="card-title text-uppercase">Quản lý đơn hàng</div>
          </div>
          <div class="card-body">
            <a-alert :message="store.state.message_success" v-if="store.state.message_success !== null" type="success"
              show-icon close-text="Close Now" />
            <table class="table table-success table-striped mt-2">
              <thead>
                <tr>
                  <th>Mã đơn hàng</th>
                  <th style="width: 130px">Người đặt</th>
                  <th style="width: 120px">Số điện thoại</th>
                  <th style="width: 150px">Ngày đặt</th>
                  <th style="width: 100px">Khuyến mãi</th>
                  <th>Tổng</th>
                  <th>Lợi nhuận</th>
                  <th>Thanh toán</th>
                  <th>Tình trạng</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in paginatedOrders" :key="order.id">
                  <td>{{ order.id }}</td>
                  <td>
                    {{ order.receive_user }}
                  </td>
                  <td>
                    {{ order.phone }}
                  </td>
                  <td>{{ order.order_date }}</td>
                  <td>{{ order.voucher_id ? order.voucher_id : 'Không' }}</td>
                  <td>
                    {{
                      parseInt(order.amount).toLocaleString("vi-VN", {
                        style: "currency",
                        currency: "VND",
                      })
                    }}
                  </td>
                  <td>
                    {{
                      parseInt(order.amount - order.order_details[0].product.import_price * order.order_details[0].quantity).toLocaleString("vi-VN",
                        {
                          style: 'currency', currency: "VND"
                    }) }}
                  </td>
                  <td>
                    {{ order.payment_method === 'direct_payment' ? 'Trực tiếp' : (order.payment_method.toUpperCase()) }}
                  </td>
                  <td v-if="order.status == 0">Đang chờ xác nhận</td>
                  <td v-else-if="order.status == 1">Đang chờ giao hàng</td>
                  <td v-else-if="order.status == 2">Đã giao</td>
                  <td>
                    <button @click.prevent="openModal(order.id, 'edit')" class="btn p-0">
                      <EditTwoTone two-tone-color="#FA7921" style="font-size: 30px; cursor: pointer" />
                    </button>
                    <ModeOrder v-if="isOpened" :is-opened="isOpened" @close="closeModal" :type-mode="typeMode"
                      :id="idSelected" @updated="getOrders" @deleted="getOrders">
                    </ModeOrder>
                    <button v-if="order.status != 1" @click.prevent="openModal(order.id, 'delete')" class="btn p-0">
                      <DeleteTwoTone two-tone-color="red" style="font-size: 30px; cursor: pointer; " />
                    </button>

                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th>Mã đơn hàng</th>
                  <th style="width: 130px">Người đặt</th>
                  <th style="width: 120px">Số điện thoại</th>
                  <th style="width: 150px">Ngày đặt</th>
                  <th style="width: 100px">Khuyến mãi</th>
                  <th>Tổng</th>
                  <th>Lợi nhuận</th>
                  <th>Thanh toán</th>
                  <th>Tình trạng</th>
                  <th>Thao tác</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="card-footer text-center">
            <a-pagination v-model:current="currentPage" :total="orders.length" :pageSize="pageSize"
              @change="pageChanged" show-less-items />
          </div>
        </div>
      </div>
      <router-view />
    </div>
  </div>
</template>

<script setup>
import ModeOrder from "./ModeOrder.vue";
import {
  EditTwoTone,
  DeleteTwoTone,
} from "@ant-design/icons-vue";
import auth from "@/axios";
import { computed, ref } from 'vue';
import store from "@/store";

const currentPage = ref(1);
const pageSize = ref(5);
const pageChanged = (page) => {
  currentPage.value = page;
};
const orders = ref([]);
const token = localStorage.getItem("token");
auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
function getOrders() {
  auth.get('order')
    .then(res => {
      orders.value = res.data;
      console.log(orders.value);

    })
}
const paginatedOrders = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return orders.value.slice(start, end);
});
getOrders();
const isOpened = ref(false);
const idSelected = ref(null);
const typeMode = ref("");
const openModal = (id, mode) => {
  isOpened.value = true;
  typeMode.value = mode;
  idSelected.value = id;
}
const closeModal = () => {
  isOpened.value = false;
  typeMode.value = '';
  idSelected.value = null;
}
</script>
