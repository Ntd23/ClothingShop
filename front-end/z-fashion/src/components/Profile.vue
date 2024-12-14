<template>
  <div class="container d-flex py-4 px-0 profile">
    <div class="col-2">
      <div class="menu">
        <ul>
          <li style="padding-top: 16px;padding-left: 24px;">
            <div class="menu-1">
              <h4>
                <UserOutlined />
              </h4>
              <h4 class="menu-title">Hồ sơ</h4>
            </div>
            <ul class="submenu">
              <div class="submenu-1">
                <li>
                  <RouterLink :to="`/profile/${user_id}`" style="text-decoration: none; color: black">
                    <div>Địa chỉ</div>
                  </RouterLink>
                </li>
              </div>
              <div class="submenu-2">
                <li>
                  <RouterLink :to="`/profile/${user_id}/change-password`" style="text-decoration: none;  color: black">
                    <div>Đổi mật khẩu</div>
                  </RouterLink>
                </li>
              </div>
            </ul>
          </li>
          <li style="padding-top: 16px;padding-left: 24px;">
            <div class="menu-2">
              <ShoppingCartOutlined />
              <RouterLink :to="`/profile/${user_id}/myorder`"
                style="text-decoration: none; color: black; margin-left: 14px;margin-top: -5px;">
                <div>Đơn hàng</div>
              </RouterLink>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <RouterView />
  </div>
</template>

<script setup>
import {
  UserOutlined,
  ShoppingCartOutlined,
} from '@ant-design/icons-vue';
import { computed } from 'vue';
import { useStore } from 'vuex';
const store = useStore();
const user_id = computed(() => {
  const user = store.state.user?.user || store.state.user;
  return user?.id || '';
});
</script>

<style scoped>
ul {
  padding-left: 0;
}

.menu {
  background-color: #ffa39e;
  color: black;
  height: 100%;
  font-size: larger;
}

.submenu {
  margin-top: 16px;
}

.menu-title {
  margin-top: 7px;
  margin-left: 10px;
}

.menu-1,
.menu-2 {
  display: flex;
  height: 30px;
  font-size: x-large;
}

.menu-2 {
  margin-top: 4px;
}

.submenu-1,
.submenu-2 {
  height: 30px;
  width: 207px;
  padding-left: 40px;
  border-radius: 25px;
}

.submenu>.submenu-1:hover,
.submenu>.submenu-2:hover {
  background-color: #fff1f0;

}

li {
  list-style: none;
}
</style>
