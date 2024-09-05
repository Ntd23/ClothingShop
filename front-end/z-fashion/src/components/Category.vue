<template>
  <div>
    <a-dropdown style="cursor: pointer; font-family: none;">
      <a class="ant-dropdown-link navbar-brand px-2">
        Danh mục
      </a>
      <template #overlay>
        <a-menu>
          <a-sub-menu v-for="(category, index) in categories" :key="index">
            <template #title >
              <router-link :to="`/clothing/${category.name}`">{{category.name}}</router-link>
            </template>
            <a-menu-item v-for="(child, index) in category.children" :key="index">
              <router-link :to="`/clothing/${category.name}/${child.id}`">{{ child.name }}</router-link>
            </a-menu-item>
          </a-sub-menu>
        </a-menu>
      </template>
    </a-dropdown>
  </div>
</template>

<script setup>
import axios from '@/axios';
import { onMounted, ref } from 'vue';
const categories= ref([]);
const getCategories= ()=> {
  axios.get('http://127.0.0.1:8000/categories')
  .then(res=> {
    categories.value= res.data;
    console.log(categories.value);
  })
  .catch(err=> {
    console.log(err);
  })
}
onMounted(()=> {
  getCategories();
})
</script>
