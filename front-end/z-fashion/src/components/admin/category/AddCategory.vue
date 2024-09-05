<template>
  <div class="col-10 position-absolute" style="top: 10%; left: 120%;width: 1192px;">
    <div class="card">
      <div class="card">
        <div class="card-header">
          <div class="card-title text-uppercase">Thêm danh mục</div>
        </div>
        <div class="card-body">
          <a-button type="primary" ><router-link to="/admin/categories">Quay lại</router-link></a-button>
          <form @submit.prevent="addCategory" class="mt-3" style="border: 1px solid #ccc" enctype="multipart/form-data">
            <div class="row m-3">
              <div class="col">
                <label for="">Tên danh mục</label>
                <input type="text" class="form-control" v-model="name">
                <span class="text-danger" v-if="errors.name">{{errors.name.toString()}}</span>
              </div>
              <div class="col">
                <label for="">Chọn hình ảnh</label>
                <input type="file" class="form-control" @change="onFileChange($event)">
                <span class="text-danger" v-if="errors.image">{{errors.image.toString()}}</span>
              </div>
              <div class="col">
                <label for="">Chọn danh mục cha (Không bắt buộc)</label>
                <div>
                  <select v-model="parent_id">
                    <option v-for="parent_category in parent_categories" :key="parent_category.id" :value="parent_category.id">
                      {{ '-'.repeat((parent_category.parent_id * 2)/100) }}{{ parent_category.name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row col-sm-2 m-auto pb-3">
              <a-button type="primary" html-type="submit" >Thêm danh mục</a-button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <router-view/>
  </div>
</template>


<script>
import auth from "@/axios";
import router from '@/router';

export default {
  data() {
    return {
      name: '',
      image: null,
      parent_id: '',
      errors: {},
      parent_categories: [],
    }
  },
  methods: {
    onFileChange(event) {
      this.image= event.target.files[0];
    },
    addCategory() {
      let formData = new FormData();
      formData.append('name', this.name);
      formData.append('image', this.image);
      formData.append('parent_id', this.parent_id);
      const token = localStorage.getItem("token");
      auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      auth.post('/categories', formData)
        .then(res=> {
          router.push('/admin/categories')
          if(res.data.message!==undefined) {
            this.$store.commit('SET_MESSAGE', res.data.message)
          }
        })
        .catch(err=>{
          if (err && err.response.status === 422) {
            this.errors = err.response.data.errors;
            if(this.image===null) {
              this.errors.image = 'Vui lòng chọn hình ảnh';
            }
          }
        });
    },
  },
  created() {
    const token = localStorage.getItem("token");
    auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    auth.get('parent-category')
      .then(res=> res.data)
      .then(data=> {
        this.parent_categories= Object.values(data.categories);
      })
      .catch(err=> console.log(err))
  },
}
</script>
