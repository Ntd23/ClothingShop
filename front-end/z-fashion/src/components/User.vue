<template>
  <div id="#user">
    <Header />
    <router-view />
    <Chat />
    <Footer />
  </div>
</template>

<script>
import { mapActions } from "vuex";
import Header from "./Header.vue";
import Footer from "./Footer.vue";
import Chat from "./Chat.vue";
export default {
  name: "User",
  components: {
    Header,
    Footer,
    Chat,
  },
  methods: {
    ...mapActions(["logout"]),
  },
};
</script>

<style>
#user {
  background-color: snow;
}
</style>
