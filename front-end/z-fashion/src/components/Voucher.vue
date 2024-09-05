<template>
  <div class="voucher">
    <div class="voucher-item" v-for="(voucher, index) in vouchers" :key="index">
      <h6 class="voucher-code">Mã khuyến mãi: {{voucher.code}} <CopyOutlined v-model="voucher.code" @click="copyCode(voucher.code)" /></h6>
      <br>
      <span>Ngày bắt đầu: {{voucher.start_date}}</span><br>
      <span>Ngày kết thúc: {{voucher.end_date}}</span>
      <p class="voucher-note">Note: Chỉ được sử dụng 1 lần</p>
    </div>
  </div>
</template>

<script setup>
import {CopyOutlined} from '@ant-design/icons-vue';
import Clipboard from 'clipboard';
import axios from '@/axios';
import { onMounted, ref } from 'vue';
import { message } from 'ant-design-vue';

const vouchers= ref([]);
const getVouchers= ()=> {
  axios.get('http://127.0.0.1:8000/vouchers')
    .then(res=> {
      vouchers.value= res.data;
    })
}
const copyCode= (code)=> {
  const clipboard= new Clipboard('.voucher-code', {
    text: ()=> code,
  })
  clipboard.on('success', ()=> {
    message.success('Sao chép thành công');
    clipboard.destroy();
  })
}
onMounted(()=> {
  getVouchers();
})
</script>

<style scoped>
.voucher {
  display: flex;
  justify-content: space-between;
  padding: 20px 0;
}
.voucher-item {
  width: 300px;
  border-radius: 6%;
  border: 1px solid #ccc;
  padding: 10px;
}
.voucher-code {
  display: flex;
  justify-content: space-between;
}
.voucher-note {
  color: #ccc;
  margin-bottom: 0;
  margin-top: 8px;

}
.anticon,.anticon-copy {
  cursor: pointer;
}
</style>
