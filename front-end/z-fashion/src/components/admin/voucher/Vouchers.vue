<template>
  <div class="col-10 position-absolute" style="top: 10%; left: 120%; width: 1194px">
    <div class="card">
      <div class="card">
        <div class="card-header">
          <div class="card-title text-uppercase">Quản lý khuyến mãi</div>
        </div>
        <div class="card-body">
          <a-button type="primary"><router-link to="/admin/voucher/add">Thêm khuyến mãi</router-link></a-button>
          <a-alert :message="message_success" v-if="message_success !== null" type="success" show-icon
            close-text="Close Now" />
          <table class="table table-success table-striped mt-2">
            <thead>
              <tr>
                <th>ID</th>
                <th>Mã khuyến mãi</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày hết hạn</th>
                <th>Số lượt dùng tối đa</th>
                <th>Số lượt đã dùng</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody v-if="paginatedVouchers && paginatedVouchers.length > 0">
              <tr v-for="voucher in paginatedVouchers" :key="voucher.id">
                <td>{{ voucher.id }}</td>
                <td>{{ voucher.code }}</td>
                <td>{{ voucher.start_date }}</td>
                <td>{{ voucher.end_date }}</td>
                <td>{{ voucher.use_max }}</td>
                <td>{{ voucher.used }}</td>
                <td>
                  <router-link :to="{ name: 'EditVoucher', params: { id: voucher.id } }">
                    <EditTwoTone two-tone-color="#FA7921" style="font-size: 30px; cursor: pointer" />
                  </router-link>
                  <DeleteTwoTone two-tone-color="red" style="font-size: 30px; cursor: pointer; margin-left: 6px"
                    @click="showDeleteConfirm(voucher.id)" />
                </td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td colspan="7" class="text-center">Không có dữ liệu</td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>Mã khuyến mãi</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày hết hạn</th>
                <th>Số lượt dùng tối đa</th>
                <th>Số lượt đã dùng</th>
                <th>Thao tác</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="card-footer text-center">
          <a-pagination v-model:current="currentPage" :total="vouchers.length" :pageSize="pageSize"
            @change="pageChanged" show-less-items />
        </div>
      </div>
    </div>
    <router-view />
  </div>
</template>
<script>
import { EditTwoTone, DeleteTwoTone } from "@ant-design/icons-vue";
import auth from "@/axios";
import { mapState } from "vuex";
export default {
  components: {
    EditTwoTone,
    DeleteTwoTone,
  },
  data() {
    return {};
  },
  computed: {
    ...mapState(["message_success"]),
  },
  mounted() {
    setTimeout(() => {
      if (this.message_success) {
        this.$store.commit("CLEAR_MESSAGE");
      }
    }, 2000);
  },
};
</script>
<script setup>
import { useStore } from 'vuex'
import { computed, ref } from "vue";
import { onMounted } from "vue";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";
import { createVNode } from "vue";
import { Modal } from "ant-design-vue";
const store = useStore();
const showDeleteConfirm = (id) => {
  Modal.confirm({
    title: " Bạn có chắc muốn xóa bản ghi này?",
    icon: createVNode(ExclamationCircleOutlined),
    content: "Note: Sau khi xóa không thể khôi phục",
    okText: "Yes",
    okType: "danger",
    cancelText: "No",
    onOk() {
      deleteVoucher(id);
    },
    onCancel() {
      console.log("Cancel");
    },
  });
};
const vouchers = ref([]);
const currentPage = ref(1);
const pageSize = ref(5);
const showAllVouchers = () => {
  const token = localStorage.getItem("token");
  auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  auth
    .get("voucher")
    .then((res) => {
      vouchers.value = res.data;
    })
    .catch((err) => console.log(err));
};
const deleteVoucher = (id) => {
  const token = localStorage.getItem("token");
  auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  auth
    .delete("voucher/" + id)
    .then((res) => {
      vouchers.value = vouchers.value.filter(
        (voucher) => voucher.id !== id
      );
      return res.data.message;
    })
    .then(message => {
      store.commit('SET_MESSAGE', message);
      setTimeout(() => {
        store.commit('CLEAR_MESSAGE');
      }, 2500)
    })
    .catch((err) => console.log(err));
};
const pageChanged = (page) => {
  currentPage.value = page;
};
const paginatedVouchers = computed(() => {
  if (!vouchers.value) {
    return [];
  }
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return vouchers.value.slice(start, end);
});

onMounted(() => {
  showAllVouchers();

});
</script>

<style scoped>
.filter {
  float: right;
  width: 200px;
  display: inline-flex;
  margin-right: 200px;
}
</style>>
