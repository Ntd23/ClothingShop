<template>
  <div class="container">
    <a-breadcrumb>
      <a-breadcrumb-item><router-link :to="{ path: '/' }">Trang chủ</router-link></a-breadcrumb-item>
      <a-breadcrumb-item><router-link :to="`/clothing/${parent_name}`">{{
        parent_name
          }}</router-link></a-breadcrumb-item>
      <a-breadcrumb-item>{{ child_name }}</a-breadcrumb-item>
    </a-breadcrumb>
    <div class="product_details" v-for="item in product_details" :key="item.id">
      <div class="product_details-left">
        <div class="product_details-image">
          <img :src="item.images[0].url" style="max-width: 500px" />
        </div>
      </div>
      <div class="product_details-right">
        <div class="details">
          <h4 class="product_name">{{ item.name }}</h4>
          <strong class="product_code">Mã sản phẩm: {{ item.id }}</strong>
          <p class="product_inventory">Còn lại: {{ item.inventory }}</p>
          <p class="product_price">
            {{
              parseInt(item.selling_price).toLocaleString("vi-VN", {
                style: "currency",
                currency: "VND",
              })
            }}
          </p>
          <div class="sizes-colors">
            <div v-for="sizes_colors in item.product_details" :key="sizes_colors.id">
              <form @submit.prevent="addToCart('success')">
                <input type="hidden" v-model="user_id" />
                <input type="hidden" v-model="product_id" />
                <div class="colors">
                  <p>
                    Màu sắc: {{ selectedColor }}
                    <span class="text-danger" v-show="errors.color">{{
                      String(errors.color)
                    }}</span>
                  </p>
                  <div class="row">
                    <article class="color col-6 col-md-2" :style="'background: ' + color" v-for="(color, index) in JSON.parse(sizes_colors.color)
                      .split(',')
                      .map((color) => color.trim())" :key="index" @click="selectColor(color)"
                      :class="{ selected: color === selectedColor }"></article>
                  </div>
                </div>
                <br />
                <div class="sizes">
                  <p>
                    Kích cỡ: {{ selectedSize
                    }}<span class="text-danger" v-show="errors.size">{{
                      String(errors.size)
                    }}</span>
                  </p>
                  <div class="d-flex">
                    <article class="size" v-for="(size, index) in JSON.parse(sizes_colors.size)
                      .split(',')
                      .map((size) => size.trim())" :key="index" @click="selectSize(size)">
                      {{ size }}
                    </article>
                  </div>
                </div>
                <br />
                <div class="quantity">
                  <p>Số lượng: {{ selectedQuantity }}</p>
                  <div class="d-flex">
                    <button @click="selectedQuantity++" type="button" class="btn btn-group-sm btn-danger btn-remote">
                      +
                    </button>
                    <input type="number" v-model="selectedQuantity" min="1" class="enter-number" />
                    <button @click="selectedQuantity--" type="button" class="btn btn-group-sm btn-danger btn-remote">
                      -
                    </button>
                  </div>
                </div>
                <br />
                <div class="add-to-cart">
                  <button type="submit" class="btn btn-danger">
                    Thêm vào giỏ hàng
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="overlay" v-if="loading">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div v-if="child_name">
      <h2 style="margin-top: 50px;text-align: center;">Sản phẩm tương tự</h2>
      <ProductsRelated :name="child_name" :id="product_id" :key="product_id"></ProductsRelated>
    </div>

  </div>
</template>

<script setup>
import ProductsRelated from "./ProductsRelated.vue";
import { notification } from "ant-design-vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import axios from "@/axios";
import auth from "@/axios";
import { onMounted, ref, computed, watchEffect, watch } from "vue";
const route = useRoute();
const product_id = computed(() => route.params.id);
const store = useStore();
const user_id = computed(() => {
  const user = store.state.user?.user || store.state.user;
  return user?.id || "";
});
const product_details = ref([]);
const selectedColor = ref("");
const selectedSize = ref("");
const selectedQuantity = ref(1);
const loading = ref(false);
const getProductDetails = () => {
  axios
    .get(`http://127.0.0.1:8000/product/details/${product_id.value}`)
    .then((res) => {
      product_details.value = res.data;
    })
    .catch((err) => {
      console.log(err);
    });
};
const parent_name = ref('');
const child_name = ref('');
watch(parent_name, (name) => {
  console.log(name);

})
watch(child_name, (name) => {
  console.log(name);
})
watch(product_id, (id) => {
  console.log(id);
  getProductDetails();
  getCategoryByProductId();
})
const getCategoryByProductId = () => {
  axios.get(`http://127.0.0.1:8000/category/product/${product_id.value}`)
    .then(res => {
      child_name.value = res.data[0].name;
      parent_name.value = res.data[0].parent.name;
    })
}

const selectColor = (color) => {
  selectedColor.value = color;
};
const selectSize = (size) => {
  selectedSize.value = size;
};
const errors = ref([]);
watchEffect(() => {
  if (selectedColor.value !== "") errors.value.color = [];
  if (selectedSize.value !== "") errors.value.size = [];
});
const addToCart = (type) => {
  const token = localStorage.getItem("token");
  auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  let formData = new FormData();
  formData.append("user_id", user_id.value);
  formData.append("product_id", product_id.value);
  formData.append("quantity", selectedQuantity.value);
  formData.append("color", selectedColor.value);
  formData.append("size", selectedSize.value);
  auth
    .post(`http://127.0.0.1:8000/cart`, formData)
    .then((res) => {
      loading.value = true;
      setTimeout(() => {
        notification[type]({
          message: res.data[2],
        });
        loading.value = false;
      }, 2000);

      getQuantityCarts();
    })
    .catch((err) => {
      if (err.status == 422) {
        errors.value = err.response.data.errors;
      }
    });
};
const getQuantityCarts = () => {
  auth.get(`http://127.0.0.1:8000/carts/${user_id.value}`).then((res) => {
    store.commit("SET_QUANTITY_CART", res.data[1]);
  });
};
getCategoryByProductId();
onMounted(() => {
  getProductDetails();
});
</script>

<style scoped>
.product_details {
  display: flex;
  margin-top: 25px;
}

.product_details-right {
  margin-left: 100px;
}

.product_name {
  font-size: xx-large;
}

.product_code {
  font-size: x-large;
}

.product_inventory {
  color: #ccc;
  font-size: large;
}

.product_price {
  font-size: xx-large;
  font-weight: bolder;
}

.colors {
  display: flex;
  flex-direction: column;
}

.color {
  width: 42px;
  height: 42px;
  border: 1px solid palegreen;
  border-radius: 50%;
  margin-left: 10px;
  margin-right: 10px;
  cursor: pointer;
}

.sizes {
  display: flex;
  flex-direction: column;
}

.size {
  width: 42px;
  height: 42px;
  border: 2px solid #ccc;
  margin-left: 10px;
  margin-right: 10px;
  text-align: center;
  line-height: 40px;
  cursor: pointer;
}

.selected {
  border: 1px solid rgb(51, 27, 40);
}

.enter-number {
  width: 50px;
  text-align: center;
  margin: 0 10px;
}

.btn-remote {
  margin: 0 10px;
}
</style>

<style>
/* Lớp phủ toàn màn hình */
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(134, 95, 95, 0.5);
  /* Nền mờ */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  /* Đảm bảo lớp phủ luôn nằm trên cùng */
}
</style>
