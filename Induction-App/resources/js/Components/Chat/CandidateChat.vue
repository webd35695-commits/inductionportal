<template>
  <div class="chat-container">
    <div class="messages" ref="messages">
      <div v-for="(message, index) in messages" :key="index"
           :class="['message', message.sender === 'candidate' ? 'candidate-message' : 'admin-message']">
        <p>{{ message.text }}</p>
        <small>{{ message.time }}</small>
      </div>
    </div>
    <div class="input-area">
      <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type your message...">
      <button @click="sendMessage">Send</button>
    </div>
  </div>
</template>

<script>
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

export default {
  props: ['adminId'],
  data() {
    return {
      messages: [],
      newMessage: '',
      echo: null
    };
  },
  mounted() {
    this.setupPusher();
    this.loadPreviousMessages();
  },
  beforeDestroy() {
    if (this.echo) {
      this.echo.leave(`chat.candidate.${this.$page.props.auth.user.id}`);
    }
  },
  methods: {
    setupPusher() {
      this.echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        forceTLS: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
          }
        }
      });

      this.echo.private(`chat.candidate.${this.$page.props.auth.user.id}`)
        .listen('.admin.message', (data) => {
          this.messages.push({
            text: data.message,
            sender: 'admin',
            time: new Date().toLocaleTimeString()
          });
          this.scrollToBottom();
        });
    },
    async loadPreviousMessages() {
      try {
        const response = await axios.get(`/api/chat/messages?admin_id=${this.adminId}`);
        this.messages = response.data;
        this.scrollToBottom();
      } catch (error) {
        console.error('Error loading messages:', error);
      }
    },
    async sendMessage() {
      if (!this.newMessage.trim()) return;

      try {
        await axios.post('/api/send-candidate-message', {
          message: this.newMessage,
          admin_id: this.adminId
        });

        this.messages.push({
          text: this.newMessage,
          sender: 'candidate',
          time: new Date().toLocaleTimeString()
        });

        this.newMessage = '';
        this.scrollToBottom();
      } catch (error) {
        console.error('Error sending message:', error);
      }
    },
    scrollToBottom() {
      this.$nextTick(() => {
        const container = this.$refs.messages;
        container.scrollTop = container.scrollHeight;
      });
    }
  }
};
</script>

<style scoped>
/* Add your styles here */
</style>
