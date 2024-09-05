<template>
  <div class="contact">
    <div class="container">
      <span v-if="$route.name=='Contact'" style="color: #ccc">Liên hệ</span>
      <div class="row pt-4" >
        <div class="col-4">
          <div class="fw-bold" style="font-size: x-large">
            Liên hệ
          </div>
          <div class="card-body mt-4">
            <div class="nav">
              <div class="nav-item d-flex">
                <span class="dot">
                  <EnvironmentOutlined class="icon" />
                </span>
                <div class="info">
                  <span class="fw-bold">Địa chỉ</span> <br> <span class="info_detail">Số 298 Đ. Cầu Diễn, Minh Khai, Bắc
                    Từ Liêm, Hà Nội, Việt Nam</span>
                </div>
              </div>
              <div class="nav-item d-flex mt-2">
                <span class="dot">
                  <MailOutlined class="icon" />
                </span>
                <div class="info">
                  <span class="fw-bold">Email</span> <br> <span class="info_detail">z_fashion@gmail.com</span>
                </div>
              </div>
              <div class="nav-item d-flex mt-2">
                <span class="dot">
                  <PhoneOutlined class="icon" />
                </span>
                <div class="info">
                  <span class="fw-bold">Số điện thoại</span> <br> <span class="info_detail">0111 222 334</span>
                </div>
              </div>
              <div class="nav-item d-flex mt-2">
                <span class="dot">
                  <FieldTimeOutlined class="icon" />
                </span>
                <div class="info">
                  <span class="fw-bold">Thời gian làm việc</span> <br> <span class="info_detail">Tất cả các ngày trong
                    tuần, từ 8h đến 22h</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-4">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.473663213007!2d105.73253187408295!3d21.05373598692259!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31345457e292d5bf%3A0x20ac91c94d74439a!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBuZ2hp4buHcCBIw6AgTuG7mWk!5e0!3m2!1svi!2s!4v1721616508258!5m2!1svi!2s"
            width="300" height="376" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="col-4">
          <div class="fw-bold" style="font-size: x-large">Gửi thắc mắc cho chúng tôi</div>
          <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="message_success">
            <strong>{{ message_success }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <div class="card-body mt-4">
            <form @submit.prevent="contact">
              <input type="hidden" v-model="id">
              <textarea class="form-control mt-2" rows="8" v-model="content"></textarea>
              <span class="text-danger" v-if="error != ''">{{ error }}</span>
              <button type="submit" class="btn btn-contact">Gửi cho chúng tôi</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <hr style="margin: 0;" />
  </div>
</template>

<script>
import {
  EnvironmentOutlined, MailOutlined, PhoneOutlined, FieldTimeOutlined
} from "@ant-design/icons-vue";
import auth from "@/axios";
import { mapState } from 'vuex';
export default {
  components: {
    EnvironmentOutlined,
    MailOutlined,
    PhoneOutlined,
    FieldTimeOutlined
  },
  data() {
    return {
      id: '',
      content: '',
      error: '',
    }
  },
  computed: {
    ...mapState(['message_success'])
  },
  mounted() {
    this.id = JSON.parse(JSON.stringify(this.$store.state.user.user.id))
  },
  watch: {
    message_success(newMessage) {
      if(newMessage) {
        setTimeout(()=> {
          this.$store.commit('CLEAR_MESSAGE');
        }, 2000)
      }
    }
  },
  methods: {
    contact() {
      let formData = new FormData();
      formData.append('id', this.id);
      formData.append('content', this.content);
      auth.post('contact', formData)
        .then(res => {
          this.$store.commit('SET_MESSAGE', res.data.message);
          this.error = '';

        })
        .catch(err => {
          this.error = err.response.data.message;
          console.log(this.content);
        })
    }
  },
}
</script>

<style scoped>
.contact {
  background-color: snow;
  padding: 20px 0px;
}

.container {
  padding: 0 21px;
  margin-bottom: 20px;
  color: #77203d;
  font-family: "Playwrite NG Modern", cursive;
}

.btn-contact {
  background: rgb(224, 203, 215);
  margin-top: 16px;
}

.btn:hover {
  background: rgb(224, 203, 215);
}

.dot {
  height: 70px;
  width: 70px;
  border-radius: 50%;
  display: inline-block;
  border: 2px solid #77203d;
}

.icon {
  font-size: xxx-large;
  margin: 10px;
}

.info {
  padding-left: 7px;
}

.info_detail {
  font-size: small
}
</style>
