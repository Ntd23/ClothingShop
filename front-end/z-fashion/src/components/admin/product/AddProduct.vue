<template>
  <div class="col-10 position-absolute" style="top: 10%; left: 120%;width: 1192px;">
    <div class="card">
      <div class="card">
        <div class="card-header">
          <div class="card-title text-uppercase">Thêm sản phẩm</div>
        </div>
        <div class="card-body">
          <a-button type="primary"><router-link to="/admin/products">Quay lại</router-link></a-button>
          <form @submit.prevent="addProduct" class="mt-3" style="border: 1px solid #ccc" enctype="multipart/form-data">
            <div class="row m-3">
              <div class="col-6">
                <label for="">Tên sản phẩm</label>
                <input type="text" class="form-control" v-model="name">
                <span class="text-danger" v-if="errors.name">{{ errors.name.toString() }}</span>
              </div>
              <div class="col-6">
                <label for="">Giá tiền</label>
                <input type="number" class="form-control" v-model="price">
                <span class="text-danger" v-if="errors.price">{{ errors.price.toString() }}</span>
              </div>
              <div class="col-6">
                <label for="">Số lượng</label>
                <input type="number" class="form-control" v-model="quantity">
                <span class="text-danger" v-if="errors.quantity">{{ errors.quantity.toString() }}</span>
              </div>
              <div class="col-6">
                <label for="">Màu sắc</label>
                <div>
                  <select v-model="colors" multiple>
                    <option value="#ffffff" style="background: #FFFFFF; color: #000000">white</option>
                    <option value="#000000" style="background: #000000; color: #FFFFFF">black</option>
                    <option :value="color[0].primary" :style="'background: '+color[0].primary">red</option>
                    <option :value="color[7].primary" :style="'background: '+color[7].primary">blue</option>
                    <option :value="color[3].primary" :style="'background: '+color[3].primary">yellow</option>
                    <option :value="color[11].primary" :style="'background: '+color[11].primary">grey</option>
                    <option :value="color[9].primary" :style="'background: '+color[9].primary">purple</option>
                    <option :value="color[1].primary" :style="'background: '+color[1].primary">volcano</option>
                    <option :value="color[2].primary" :style="'background: '+color[2].primary">gold</option>
                    <option :value="color[4].primary" :style="'background: '+color[4].primary">lime</option>
                    <option :value="color[5].primary" :style="'background: '+color[5].primary">green</option>
                    <option :value="color[6].primary" :style="'background: '+color[6].primary">cyan</option>
                    <option :value="color[8].primary" :style="'background: '+color[8].primary">geekblue</option>
                    <option :value="color[10].primary" :style="'background: '+color[10].primary">magenta</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <label for="">Size</label>
                <div>
                  <select v-model="size" multiple>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                  </select>
                </div>
                <span class="text-danger" v-if="errors.size">{{ errors.size.toString() }}</span>
              </div>
              <div class="col-6">
                <label for="">Chọn hình ảnh</label>
                <input type="file" class="form-control" @change="onFileChange($event)" multiple>
                <span class="text-danger" v-if="errors.image">{{ errors.image.toString() }}</span>
              </div>
              <div class="col-6">
                <label for="">Chọn danh mục</label>
                <div>
                  <select v-model="cate_id">
                    <option :value="category.id" v-for="category in categories" :key="category.id"
                      :disabled="category.parent_id === null ? true : false">
                      {{ category.parent_id !== null ? category.name + '--' + category.ancestor_name : '' }}
                    </option>
                  </select><br>
                  <span class="text-danger" v-if="errors.cate_id">{{ errors.cate_id.toString() }}</span>
                </div>
              </div>
              <div class="col-6">
                <label for="">Mô tả</label>
                <textarea cols="30" rows="10" class="form-control" v-model="description"></textarea>
                <span class="text-danger" v-if="errors.description">{{ errors.description.toString() }}</span>
              </div>
            </div>
            <div class="row col-sm-2 m-auto pb-3">
              <a-button type="primary" html-type="submit">Thêm sản phẩm</a-button>
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
import router from '@/router';
import {
  red,
  volcano,
  gold,
  yellow,
  lime,
  green,
  cyan,
  blue,
  geekblue,
  purple,
  magenta,
  grey,
} from '@ant-design/colors';
export default {
  data() {
    return {
      name: '',
      image: null,
      price: '',
      cate_id: '',
      quantity: '',
      size: [],
      description: '',
      errors: {},
      categories: [],
      colors: [red.primary],
      color: [red, volcano, gold, yellow, lime, green, cyan, blue, geekblue, purple, magenta, grey]
    }
  },

  methods: {
    showAllCategories() {
      const token = localStorage.getItem("token");
      auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      auth
        .get("child-category")
        .then((res) => {
          return res.data;
        })
        .then((data) => {
          this.categories = (data.categories);
        })
        .catch((err) => console.log(err));
    },
    onFileChange(event) {
      this.image = event.target.files[0];
    },
    addProduct() {
      console.log(this.colors);
      console.log(this.size);
      let formData = new FormData();
      formData.append('name', this.name);
      formData.append('image', this.image);
      formData.append('cate_id', this.cate_id);
      formData.append('price', this.price);
      formData.append('description', this.description);
      formData.append('quantity', this.quantity);
      formData.append('size', this.size);
      formData.append('color', this.colors);
      const token = localStorage.getItem("token");
      auth.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      auth.post('/products', formData)
        .then(res => {
          router.push('/admin/products')
          if (res.data.message !== undefined) {
            this.$store.commit('SET_MESSAGE', res.data.message)
          }
        })
        .catch(err => {
          console.log(err);
          if (err && err.response.status === 422) {
            this.errors = err.response.data.errors;
            if (this.image === null) {
              this.errors.image = 'Vui lòng chọn hình ảnh';
            }
          }
        });
    },
  },
  created() {
    this.showAllCategories();
  },
}
</script>
