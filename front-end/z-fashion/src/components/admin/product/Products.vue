<template>
  <div class="col-10 position-absolute" style="top: 10%; left: 120%; width: 1194px">
    <div class="card">
      <div class="card">
        <div class="card-header">
          <div class="card-title text-uppercase">Quản lý sản phẩm</div>
        </div>
        <div class="card-body">
          <a-button type="primary"><router-link to="/admin/product/add">Thêm sản phẩm</router-link></a-button>
          <div class="filter">
            <a-dropdown>
              <a-button style="width: 50px;">
                <FilterOutlined class="filter-icon ant-dropdown-link"></FilterOutlined>
              </a-button>
              <template #overlay>
                <a-menu @click="handleMenuClick">
                  <a-menu-item :key="index" @click="filterCateChildByCateParent(parent.id)"
                    v-for="(parent, index) in cateByParentIdIsNull">
                    {{ parent.name }}
                  </a-menu-item>

                </a-menu>
              </template>
            </a-dropdown>
            <p class="mt-1" style="margin-left: 18px;">Bộ lọc</p>
          </div>
          <a-alert :message="message_success" v-if="message_success !== null" type="success" show-icon
            close-text="Close Now" />
          <table class="table table-success table-striped mt-2">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá tiền</th>
                <th>Số lượng tồn kho</th>
                <th>Hình ảnh</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody v-if="paginatedProducts[0] && paginatedProducts[0][0].length > 0">
              <tr v-for="product in paginatedProducts[0][0]" :key="product.id">
                <td>{{ product.id }}</td>
                <td v-if="product.name">{{ product.name.substring(0, 15) + '...' }}</td>
                <td v-if="product.category.name || product.parent.name">{{ product.category.name || product.parent.name
                  }} </td>
                <td>{{ parseInt(product.price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }) }}</td>
                <td>{{ product.inventory }}</td>
                <td v-for="image in product.images" :key="image.index">
                  <img :alt="product.name + '\n' + product" style="width: 150px; height: 100px"
                    v-if="image['url'] !== undefined" :src="image['url']" />
                </td>
                <td>{{ product.description.substring(0, 15) + '...' }}</td>
                <td>
                  <router-link :to="{ name: 'EditProduct', params: { id: product.id } }">
                    <EditTwoTone two-tone-color="#FA7921" style="font-size: 30px; cursor: pointer" />
                  </router-link>
                  <DeleteTwoTone two-tone-color="red" style="font-size: 30px; cursor: pointer; margin-left: 6px"
                    @click="showDeleteConfirm(product.id)" />

                </td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td colspan="8" class="text-center">Không có dữ liệu</td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá tiền</th>
                <th>Số lượng tồn kho</th>
                <th>Hình ảnh</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="card-footer text-center">
          <a-pagination v-model:current="currentPage"
            :total="10" :pageSize="pageSize" @change="pageChanged" show-less-items />

        </div>
      </div>
    </div>
    <router-view />
  </div>
</template>
<script>
import { EditTwoTone, DeleteTwoTone, FilterOutlined } from "@ant-design/icons-vue";
import auth from "@/axios";
import { mapState } from "vuex";
export default {
  components: {
    EditTwoTone,
    DeleteTwoTone,
    FilterOutlined
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
import { onMounted,  } from "vue";
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
      deleteProduct(id);
    },
    onCancel() {
      console.log("Cancel");
    },
  });
};
const products = ref([]);
const currentPage = ref(1);
const pageSize = ref(5);
const cateByParentIdIsNull = ref([]);
const showAllProducts = () => {
  const token = localStorage.getItem("token");
  auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;

  auth
    .get("products")
    .then((res) => {
      products.value[0] = res.data;
      // console.log(products.value[0]);
    })
    .catch((err) => console.log(err))
};
const deleteProduct = (id) => {
  const token = localStorage.getItem("token");
  auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  auth
    .delete("products/" + id)
    .then((res) => {
      products.value = products.value.filter(
        (product) => product.id !== id
      );
      return res.data.message;
    })
    .then(message => {
      store.commit('SET_MESSAGE', message);
    })
    .catch((err) => console.log(err));
};
const pageChanged = (page) => {
  currentPage.value = page;
};
const paginatedProducts = computed(() => {
  // console.log(products.value);
  if (!products.value) {
    return [];
  }
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return (products.value).slice(start, end);
});
const handleMenuClick = () => {

};
const getCateByParentIdIsNull = () => {
  auth.get('category/parent_id-is-null')
    .then(res => {
      cateByParentIdIsNull.value = res.data;
    })
    .catch(err => {
      console.log(err)
    })
};
const filterCateChildByCateParent = (parent_id) => {
  auth.get(`product/filter/${parent_id}`)
    .then(res => {
      products.value[0] = Array(res.data);
      console.log(products.value[0]);
    })
    .catch(err => {
      console.log(err)
    })
};
onMounted(() => {
  showAllProducts();
  getCateByParentIdIsNull();
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
