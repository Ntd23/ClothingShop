<template>
  <div class="col-10 position-absolute" style="top: 10%; left: 120%; width: 1194px">
    <div class="card">
      <div class="card">
        <div class="card-header">
          <div class="card-title text-uppercase">Quản lý slide</div>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between col-6">
            <a-button type="primary" style="height: 38px;"><router-link to="/admin/slide/add">Thêm
                slide</router-link></a-button>
          </div>

          <a-alert :message="message_success" v-if="message_success !== null" type="success" show-icon
            close-text="Close Now" />

          <table class="table table-success table-striped mt-2">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Hình ảnh</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody v-if="slides.length>0">
              <tr v-for="slide in paginatedSlides" :key="slide.id">
                <td>{{ slide.id }}</td>
                <td style="max-width: 400px;">{{ slide.title }}</td>
                <td style="width: 40%;"><img style="width: 60%; height: auto" :src="slide.image" /></td>
                <td>
                  <router-link :to="{ name: 'EditSlide', params: { id: slide.id } }">
                    <EditTwoTone two-tone-color="#FA7921" style="font-size: 30px; cursor: pointer" />
                  </router-link>
                  <delete-slide :id="slide.id" @deletedSlide="handleDeletedSlide" />
                </td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td colspan="4" class="text-center">Không có dữ liệu</td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Hình ảnh</th>
                <th>Thao tác</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="card-footer text-center">
          <a-pagination v-model:current="currentPage" :total="slides.length" :pageSize="pageSize" @change="pageChanged"
            show-less-items />
        </div>
      </div>
    </div>
    <router-view />
  </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { onMounted } from "vue";
import { EditTwoTone, } from "@ant-design/icons-vue";
import auth from "@/axios";
import { useStore } from "vuex";
import DeleteSlide from "./DeleteSlide.vue";

const store = useStore();
const slides = ref([]);
const currentPage = ref(1);
const pageSize = ref(5);
const showAllSlides = () => {
  const token = localStorage.getItem("token");
  auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  auth
    .get("slide")
    .then((res) => {
      slides.value = res.data;
    })
    .catch((err) => console.log(err));
};
const pageChanged = (page) => {
  currentPage.value = page;
};
const paginatedSlides = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return slides.value.slice(start, end);
});
const handleDeletedSlide = (id) => {
  slides.value = slides.value.filter(slide => slide.id !== id);
  showAllSlides();
};
const message_success = computed(() => {
  return store.state.message_success;
});
watch(message_success, (newMessage) => {
  if (newMessage) {
    setTimeout(() => {
      store.commit("CLEAR_MESSAGE");
    }, 2000);
  }
});
onMounted(() => {
  showAllSlides();
  setTimeout(() => {
    if (message_success.value) {
      store.commit("CLEAR_MESSAGE");
    }
  }, 2000);
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
