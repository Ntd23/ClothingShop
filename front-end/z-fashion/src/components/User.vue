<template>
  <div id="#user">
    <Header/>
    <router-view />
    <Footer/>
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import Header from './Header.vue'
import Footer from './Footer.vue';

export default {
  name: 'User',
  components: {
    Header,
    Footer,
  },
  methods: {
    ...mapActions(['logout']),
  }
}
</script>

<style>
#user {
  background-color: snow;
}
</style>
