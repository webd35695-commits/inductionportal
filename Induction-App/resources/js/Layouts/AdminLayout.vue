<template>
  <div class="flex flex-col h-screen bg-gray-50">
    <Header />
    <div class="flex flex-1 overflow-hidden">
      <Sidebar />
      <main class="flex-1 p-6 overflow-auto">
        <slot />
      </main>
    </div>
    <Footer />
  </div>
</template>

<script>
import Header from '../Components/Header.vue';
import Sidebar from '../Components/Sidebar.vue';
import Footer from '../Components/Footer.vue';

export default {
  name: 'AdminLayout',
  components: { Header, Sidebar, Footer },
};
</script>
