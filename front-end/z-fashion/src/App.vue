<template>
  <div class="p-0 m-0 w-100">
    <router-view/>
  </div>
</template>

<script>
// import Header from './components/Header.vue';
// import AdminLogin from './components/admin/Login.vue';
// import Admin from './components/admin/Admin.vue';
// import router from './router';
export default {
  name: 'App',
  components: {

  },
  data() {
    return {
      // showAdminLogin: true,
      // isAdmin: false
    }
  },
  methods: {

  },
  // mounted() {
  //   router.beforeEach((to, from, next)=> {
  //     if(to.name==='AdminLogin') this.showAdminLogin= true;
  //     else if(to.name==='Login') this.showAdminLogin= false;
  //     next();
  //   })
  //   console.log(this.showAdminLogin);
  // }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>
