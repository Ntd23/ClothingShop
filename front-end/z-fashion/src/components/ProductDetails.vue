<template>
  <div class="container">
    <div class="product_details" v-for="item in product_details" :key="item.id">
      <div class="product_details-left">
        <div class="product_details-image">
          <img :src="item.images[0].url" alt="">
        </div>
      </div>
      <div class="product_details-right">
        <div class="details">
          <h4 class="product_name">{{ item.name }}</h4>
          <strong class="product_code">Mã sản phẩm: {{ item.id }}</strong>
          <p class="product_inventory">Còn lại: {{ item.inventory }}</p>
          <p class="product_price">{{ parseInt(item.price).toLocaleString('vi-VN', {
            style: 'currency', currency: 'VND'
            }) }}</p>
          <div class="sizes-colors">
            <div v-for="sizes_colors in item.product_details" :key="sizes_colors.id">
              <form @submit.prevent="addToCart">
                <div class="colors">
                    <p>Màu sắc: {{ selectedColor }}</p>
                    <div class="d-flex">
                      <article class="color" :style="'background: ' + color"
                        v-for="(color, index) in JSON.parse(sizes_colors.color).split(',').map(color => color.trim())"
                        :key="index" @click="selectColor(color)" :class="{selected: color===selectedColor}">
                      </article>
                  </div>
                </div>
                <br>
                <div class="sizes">
                  <p>Kích cỡ: {{selectedSize}}</p>
                  <div class="d-flex">
                    <article class="size"
                      v-for="(size, index) in JSON.parse(sizes_colors.size).split(',').map(size => size.trim())"
                      :key="index" @click="selectSize(size)" >
                      {{ size }}
                    </article>
                  </div>
                </div>
                <br>
                <div class="quantity">
                  <p>Số lượng: {{selectedQuantity}}</p>
                  <div class="d-flex">
                    <button @click="selectedQuantity++" class="btn btn-group-sm btn-danger btn-remote">+</button>
                    <input type="number" v-model="selectedQuantity" min="1" class="enter-number" >
                    <button @click="selectedQuantity--" class="btn btn-group-sm btn-danger btn-remote">-</button>
                  </div>
                </div>
                <br>
                <div class="add-to-cart">
                  <button type="submit" class="btn btn-danger">Thêm vào giỏ hàng</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router';
import axios from '@/axios';
import { onMounted, ref } from 'vue';
const route = useRoute();
const id = route.params.id;
const product_details = ref([]);
const selectedColor = ref('');
const selectedSize= ref('');
const selectedQuantity= ref(1);
const getProductDetails = () => {
  axios.get(`http://127.0.0.1:8000/product/details/${id}`)
    .then(res => {
      product_details.value = res.data;
    })
    .catch(err => {
      console.log(err);
    })
};
const selectColor= (color)=> {
  selectedColor.value= color;
};
const selectSize= (size)=> {
  selectedSize.value= size;
};
const addToCart= ()=> {
  console.log(selectedColor.value);
}
onMounted(() => {
  getProductDetails();
})
</script>

<style scoped>
.product_details {
  display: flex;
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
  font-size: large
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
