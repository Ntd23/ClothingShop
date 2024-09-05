<template>
  <div class="col-10 position-absolute" style="top: 10%; left: 120%;width: 1100px;">
    <div class="card">
      <div class="card">
        <div class="card-header">
          <div class="card-title text-uppercase">Thêm slide</div>
        </div>
        <div class="card-body">
          <a-button type="primary"><router-link to="/admin/slides">Quay lại</router-link></a-button>
          <form @submit.prevent="addSlide" class="mt-3" style="border: 1px solid #ccc" enctype="multipart/form-data">
            <div class="row m-3">
              <div class="col">
                <label for="">Tiêu đề</label>
                <input type="text" class="form-control" v-model="title">
                <span class="text-danger" v-if="errors.title">{{ errors.title.toString() }}</span>
              </div>
              <div class="col">
                <label for="">Chọn hình ảnh</label>
                <input type="file" class="form-control" @change="onFileChange($event)">
                <span class="text-danger" v-if="errors.image">{{ errors.image.toString() }}</span>
              </div>
            </div>
            <div class="row col-sm-2 m-auto pb-3">
              <a-button type="primary" html-type="submit">Thêm slide</a-button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <router-view />
  </div>
</template>


<script setup>
import auth from "@/axios";
import router from '@/router';
import { ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const title = ref('');
const image = ref('');
const errors = ref({});
const onFileChange = (event) => {
  image.value = event.target.files[0];
};
const addSlide = () => {
  const formData = new FormData();
  formData.append('title', title.value);
  formData.append('image', image.value);
  const token = localStorage.getItem("token");
  auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  auth.post('/slide', formData)
    .then(res => {
      router.push('/admin/slides')
      if (res.data.message !== undefined) {
        store.commit('SET_MESSAGE', res.data.message)
      }
    })
    .catch(err => {
      if (err && err.response.status === 422) {
        errors.value = err.response.data.errors;
        console.log(image.value);
        if (image.value === null) {
          errors.value = 'Vui lòng chọn hình ảnh';
        }
      }
    })
};

</script>
