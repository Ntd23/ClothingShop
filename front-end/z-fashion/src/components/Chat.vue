<template>
  <div v-if="showChatbot" ref="chatbotContainer"></div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue";
import { useRoute } from "vue-router";

const chatbotContainer = ref(null);
const route = useRoute();

// Chỉ hiển thị chatbot nếu đường dẫn không bắt đầu bằng "/admin"
const showChatbot = computed(() => !route.path.startsWith("/admin"));

onMounted(() => {
  if (showChatbot.value) {
    // Kiểm tra xem script đã tồn tại chưa
    if (
      !document.querySelector(
        'script[src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"]'
      )
    ) {
      const script = document.createElement("script");
      script.src =
        "https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1";
      script.async = true;
      document.body.appendChild(script); // Thêm script vào body
    }

    // Kiểm tra nếu <df-messenger> chưa tồn tại trong chatbotContainer
    if (!chatbotContainer.value.querySelector("df-messenger")) {
      const dfMessenger = document.createElement("df-messenger");
      dfMessenger.setAttribute("intent", "WELCOME");
      dfMessenger.setAttribute("chat-title", "ziara");
      dfMessenger.setAttribute(
        "agent-id",
        "c3422210-6439-4b66-9f29-dae73ade5a1d"
      );
      dfMessenger.setAttribute("language-code", "vi");

      // Thêm <df-messenger> vào chatbotContainer
      chatbotContainer.value.appendChild(dfMessenger);
    }
  }
});
</script>
