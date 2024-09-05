<template>
  <DeleteTwoTone two-tone-color="red" style="font-size: 30px; cursor: pointer; margin-left: 6px"
    @click="showDeleteConfirm(props.id)" />
</template>

<script setup>
import auth from "@/axios";
import { defineProps, defineEmits } from "vue";
import { useStore } from "vuex";
import { DeleteTwoTone } from "@ant-design/icons-vue";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";
import { createVNode } from "vue";
import { Modal } from "ant-design-vue";
const props= defineProps({
  id: {
    type: [Number, String],
    required: true,
  },

});
const emits= defineEmits(['deletedSlide']);
const store= useStore();
const showDeleteConfirm = (id) => {
  Modal.confirm({
    title: " Bạn có chắc muốn xóa bản ghi này?",
    icon: createVNode(ExclamationCircleOutlined),
    content: "Note: Sau khi xóa không thể khôi phục",
    okText: "Yes",
    okType: "danger",
    cancelText: "No",
    onOk() {
      deleteSlide(id);
    },
    onCancel() {
      console.log("Cancel");
    },
  });
};
const deleteSlide = (id) => {
  const token = localStorage.getItem("token");
  auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  auth
    .delete("slide/" + id)
    .then((res) => {
      store.commit('SET_MESSAGE', res.data.message);
      emits('deletedSlide', id);
    })
    .catch((err) => console.log(err));
};
</script>
