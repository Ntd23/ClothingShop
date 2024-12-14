<template>
  <div class="col-10 px-4 py-4 sub-profile">
    <div class="d-flex justify-content-between align-content-center">
      <h3 class="card-title">Đơn hàng của tôi</h3>
    </div>
    <div class="mt-4 d-flex justify-content-lg-around order">
      <div class="order-0" @click="onSelected(0)"
        :style="selectedTab == 0 ? 'background: #FFA39E;' : 'background: #f3ded1'">
        Tất cả
      </div>
      <div class="order-1" @click="onSelected(1)"
        :style="selectedTab == 1 ? 'background: #FFA39E;' : 'background: #f3ded1'">ĐANG CHỜ XÁC NHẬN</div>
      <div class="order-2" @click="onSelected(2)"
        :style="selectedTab == 2 ? 'background: #FFA39E;' : 'background: #f3ded1'">ĐANG CHỜ GIAO HÀNG</div>
      <div class="order-3" @click="onSelected(3)"
        :style="selectedTab == 3 ? 'background: #FFA39E;' : 'background: #f3ded1'">ĐÃ GIAO</div>
      <div class="order-4" @click="onSelected(4)"
        :style="selectedTab == 4 ? 'background: #FFA39E;' : 'background: #f3ded1'">ĐÃ HỦY</div>
    </div>
    <div class="order-status">
      <table class="table-bordered">
        <thead>
          <tr>
            <th>Mã ĐH</th>
            <th style="width: 170px;">Tên người nhận</th>
            <th style="width: 160px;">Ngày đặt</th>
            <th>Trạng thái</th>
            <th>Tổng tiền</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in paginatedOrder" :key="order.id">
            <td>{{ order.id }}</td>
            <td>{{ order.receive_user }}</td>
            <td>{{ new Date(order.order_date).toISOString().slice(0, 19).replace('T', ' ') }}</td>
            <td v-if="order.status == 0">Chờ xác nhận</td>
            <td v-else-if="order.status == 1">Chờ giao hàng</td>
            <td v-else-if="order.status == 2">Đã giao</td>
            <td v-else-if="order.status == 3">Đã hủy</td>
            <td>{{ parseInt(order.amount).toLocaleString('vi-VN') + ' VND' }}</td>
            <td class="d-flex justify-content-evenly">
              <OrderCancel v-if="order.status == 0" :id="order.id" @cancel:order="orderDeleted" />
              <OrderDetail @click="showOrder(order.id)" :id="orderSelected === order.id ? order.id : null" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="paginate">
      <a-pagination v-model:current="currentPage" :total="orders.length" show-less-items :pageSize="pageSize"
        @change="pageChanged" />
    </div>
  </div>
</template>

<script setup>
import OrderCancel from './OrderCancel.vue';
import OrderDetail from './OrderDetail.vue';
import { computed, onMounted, reactive, ref } from 'vue';
import auth from "@/axios";
import { useStore } from 'vuex';
const currentPage = ref(1);
const pageSize = ref(10);
const pageChanged = (page) => {
  currentPage.value = page;
};
const filteredOrders = computed(() => {
  if (selectedTab.value === 0) return orders;
  return orders.filter(order => order.status === selectedTab.value - 1);
});
const paginatedOrder = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return filteredOrders.value.slice(start, end);
});
const token = localStorage.getItem("token");
auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
const store = useStore();
const user_id = computed(() => {
  const user = store.state.user?.user || store.state.user;
  return user?.id || '';
});

const selectedTab = ref(0);
const onSelected = (index) => {
  selectedTab.value = index;
}
const orders = reactive([]);
const getOrders = () => {
  auth.get(`http://127.0.0.1:8000/profile/${user_id.value}/myorder`)
    .then(res => {
      orders.push(...res.data);
    })
}
const orderDeleted = (id) => {
  const index = orders.findIndex(order => order.id === id);
  if (index !== -1) {
    orders.splice(index, 1); // Xóa đơn hàng khỏi danh sách
  }
};
const orderSelected = ref(null);
const showOrder = (id) => {
  orderSelected.value = id;
}
onMounted(() => {
  getOrders();
})
</script>

<style scoped>
.sub-profile {
  background-color: #FFD8BF;
  height: 600px;
}

.order {
  margin-left: -23px;
}

.order-0,
.order-1,
.order-2,
.order-3,
.order-4 {
  padding: 6px 12px;
  background-color: #f3ded1;
  cursor: pointer;
}

.order-status {
  margin-top: 20px;
}

table {
  background-color: #FFA39E;
  width: 100%;
  color: black;
  text-align: center;
}

tbody>tr:nth-child(odd) {
  background-color: #f8c8c5;
}

tbody>tr:nth-child(even) {
  background-color: #FFA39E;
}
</style>
