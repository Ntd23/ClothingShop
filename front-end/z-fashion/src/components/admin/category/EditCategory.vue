<template>
  <div class="col-10 position-absolute" style="top: 10%; left: 120%; width: 1192px">
    <div class="card">
      <div class="card">
        <div class="card-header">
          <div class="card-title text-uppercase">Sửa danh mục</div>
        </div>
        <div class="card-body">
          <a-button type="primary"><router-link to="/admin/categories">Quay lại</router-link></a-button>
          <form @submit.prevent="updateCategory" class="mt-3" style="border: 1px solid #ccc" enctype="multipart/form-data">
            <div class="row m-3">
              <div class="col">
                <label for="">Tên danh mục</label>
                <input type="text" class="form-control" v-model="name" />
                <span class="text-danger" v-if="errors.name">{{
                  errors.name.toString()
                  }}</span>
              </div>
              <div class="col">
                <label for="">Chọn hình ảnh</label>
                <input type="file" class="form-control" @change="onFileChange($event)" />
                <span class="text-danger" v-if="errors.image">{{
                  errors.image.toString()
                  }}</span>
                <img v-if="previewImage !== null" :src="previewImage" style="width: 77%; float:right" />
              </div>
              <div class="col">
                <label for="">Chọn danh mục cha (Không bắt buộc)</label>
                <div>
                  <select v-model="parent_id">
                    <option v-for="parent_category in parent_categories" :key="parent_category.id" :value="parent_category.id" :selected="parent_id===parent_category.id ? true : false">
                      {{ '-'.repeat((parent_category.parent_id * 2)/(parent_category.parent_id)) }}{{ parent_category.name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row col-sm-2 m-auto pb-3">
              <a-button type="primary" html-type="submit">Cập nhật danh mục</a-button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <router-view />
  </div>
</template>

<script>
import auth from "@/axios";
import router from "@/router";
export default {
  data() {
    return {
      name: "",
      image: null,
      previewImage: null,
      parent_id: null,
      errors: {},
      parent_categories: [],
    };
  },
  created() {
    const token = localStorage.getItem("token");
    auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    //show category
    auth
      .get("/categories/" + this.$route.params.id)
      .then((res) => res.data.category)
      .then(category=> {
        this.name= category.name;
        this.previewImage= category.image;
        this.parent_id = category.parent_id || '';
      })
      .catch((err) => console.log(err));

    //show parent categories
    auth
      .get("parent-category")
      .then((res) => res.data)
      .then((data) => {
        this.parent_categories = Object.values(data.categories);
      })
      .catch((err) => console.log(err));
  },
  methods: {
    onFileChange(event) {
      const file= event.target.files[0];
      if(file) {
        this.image= file;
        this.previewImage= URL.createObjectURL(file);
      }
    },
    updateCategory() {
      let formData = new FormData();
      if(this.image) {
        formData.append("image", this.image);
      }
      formData.append("parent_id", this.parent_id);
      formData.append("name", this.name);
      const token = localStorage.getItem("token");
      auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      auth
        .post(`/categories/${this.$route.params.id}?_method=PUT`,formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
      })
        .then((res) => {
          router.push("/admin/categories");
          if (res.data.message !== undefined) {
            this.$store.commit("SET_MESSAGE", res.data.message);
          }
        })
        .catch((err) => {
          console.log(err);
          if (err && err.response.status === 422) {
            this.errors = err.response.data.errors;
            if (this.image === null) {
              this.errors.image = "Vui lòng chọn hình ảnh";
            }
          }
        });
    },
  },
};
</script>
