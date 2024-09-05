<template>
  <div class="col-10 position-absolute" style="top: 10%; left: 120%; width: 1194px">
    <div class="card">
      <div class="card">
        <div class="card-header">
          <div class="card-title text-uppercase">Quản lý danh mục</div>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between col-6">
            <a-button type="primary" style="height: 38px;"><router-link to="/admin/category/add">Thêm danh mục</router-link></a-button>
            <div class="search" style="width: 300px;">
              <form class="d-inline-flex" @submit.prevent="resultsBySearch">
                <input v-model="keywords" type="text" placeholder="Tìm kiếm danh mục" class="form-control mx-2">
                <button type="submit" class="btn btn-primary">Search</button>
              </form>
            </div>
          </div>
          <div  class="d-flex justify-content-between col-6 float-end">
            <div class="result-search" v-show="searched">
              <span style="color: #ccc" class="d-inline-block">Kết quả tìm kiếm: {{categories.length}}</span>
            </div>
            <div class="filter">
              <a-dropdown>
                <a-button style="width: 50px;"><FilterOutlined class="filter-icon ant-dropdown-link"></FilterOutlined></a-button>
                <template #overlay>
                  <a-menu @click="handleMenuClick">
                    <a-menu-item :key="index" @click="filterCateChildByCateParent(parent.id)" v-for="(parent, index) in cateByParentIdIsNull">
                      {{ parent.name }}
                    </a-menu-item>

                  </a-menu>
                </template>
              </a-dropdown>
              <p class="mt-1">Bộ lọc</p>
            </div>
          </div>
          <a-alert :message="message_success" v-if="message_success !== null" type="success" show-icon
            close-text="Close Now" />

          <table class="table table-success table-striped mt-2">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Danh mục cha</th>
                <th>Hình ảnh</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="category in paginatedCategories" :key="category.id">
                <td>{{ category.id }}</td>
                <td style="max-width: 400px;">{{ category.name }}</td>
                <td v-if="category.parent">{{ category.parent.name }}</td>
                <td v-else>NULL</td>
                <td><img style="width: 150px; height: 100px" :src="category.image" /></td>
                <td>
                  <router-link :to="{ name: 'EditCategory', params: { id: category.id } }">
                    <EditTwoTone two-tone-color="#FA7921" style="font-size: 30px; cursor: pointer" />
                  </router-link>
                  <DeleteTwoTone two-tone-color="red" style="font-size: 30px; cursor: pointer; margin-left: 6px"
                    @click="showDeleteConfirm(category.id)" />
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Danh mục cha</th>
                <th>Hình ảnh</th>
                <th>Thao tác</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="card-footer text-center">
          <a-pagination v-model:current="currentPage" :total="categories.length" :pageSize="pageSize"
            @change="pageChanged" show-less-items />
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
import axios from "axios";
import { computed, ref } from "vue";
import { onMounted } from "vue";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";
import { createVNode } from "vue";
import { Modal } from "ant-design-vue";
import { useStore } from 'vuex';
const store= useStore();
const showDeleteConfirm = (id) => {
  Modal.confirm({
    title: " Bạn có chắc muốn xóa bản ghi này?",
    icon: createVNode(ExclamationCircleOutlined),
    content: "Note: Sau khi xóa không thể khôi phục",
    okText: "Yes",
    okType: "danger",
    cancelText: "No",
    onOk() {
      deleteCategory(id);
    },
    onCancel() {
      console.log("Cancel");
    },
  });
};
const categories = ref([]);
const currentPage = ref(1);
const pageSize = ref(5);
const keywords= ref('');
const searched= ref(false);
const cateByParentIdIsNull= ref([]);
const showAllCategories = () => {
  const token = localStorage.getItem("token");
  auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  auth
    .get("categories")
    .then((res) => {
      return res.data;
    })
    .then((data) => {
      categories.value = data.categories;
    })
    .catch((err) => console.log(err));
};
const resultsBySearch= ()=> {
  const token = localStorage.getItem("token");
  auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  auth
    .get(`search/category/${keywords.value}`)
    .then((res)=>{
      categories.value= res.data;
      searched.value= true;
    })
}
const deleteCategory = (id) => {
  const token = localStorage.getItem("token");
  auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  auth
    .delete("categories/" + id)
    .then((res) => {
      categories.value = categories.value.filter(
        (category) => category.id !== id
      );
      store.commit('SET_MESSAGE', res.data.message);
    })
    .catch((err) => console.log(err));
};
const pageChanged = (page) => {
  currentPage.value = page;
};
const paginatedCategories = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return categories.value.slice(start, end);
});
const handleMenuClick= ()=> {

};
const getCateByParentIdIsNull= ()=> {
  axios .get('category/parent_id-is-null')
    .then(res=> {
      cateByParentIdIsNull.value= res.data;
    })
    .catch(err=> {
      console.log(err)
    })
};
const filterCateChildByCateParent= (parent_id)=> {
  axios .get(`category/child_category/${parent_id}`)
    .then(res=> {
      categories.value= res.data;
    })
    .catch(err=> {
      console.log(err)
    })
};
onMounted(() => {
  showAllCategories();
  getCateByParentIdIsNull();
  setTimeout(()=> {
    if(store.state.message_success) {
      store.commit('CLEAR_MESSAGE');
    }
  }, 2500);
});
</script>

<style scoped>
.result-search {
  margin-top: -30px;
  margin-left: 100px;
  width: 200px;
  height: 38px;
  display: inline-block;
}
.filter {
  display: inline-flex;
  justify-content: space-between;
  width: 120px;
  height: 34px;
  margin-top: -34px;
  padding-left: 3px;
  margin-left: 165px;
}
.filter-icon {
  width: 30px;
  margin-left: -5px;
}
</style>
