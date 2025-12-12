<template>
  <div v-if="show" class="splash-container">
    <!-- Animated Background Particles -->
    <div class="particles">
      <div
        v-for="i in 20"
        :key="i"
        class="particle"
        :style="{
          left: `${Math.random() * 100}%`,
          top: `${Math.random() * 100}%`,
          animationDelay: `${Math.random() * 3}s`,
          animationDuration: `${3 + Math.random() * 4}s`
        }"
      >
        <Sparkles :size="16" class="sparkle-icon" />
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Logo Container with Animation -->
      <div class="logo-container">
        <!-- Outer Glow Ring -->
        <div class="outer-ring">
          <div class="outer-ring-inner"></div>
        </div>

        <!-- Middle Ring -->
        <div class="middle-ring">
          <div class="middle-ring-inner"></div>
        </div>

        <!-- Logo Circle -->
        <div class="logo-circle">
          <!-- Inner Circle -->
          <div class="inner-circle">
            <!-- Book Icon -->
            <div class="icon-container">
              <Book :size="80" :stroke-width="2.5" class="book-icon" />
              <div class="grad-cap-wrapper">
                <GraduationCap :size="40" class="grad-cap-icon" />
              </div>
            </div>
          </div>
        </div>

        <!-- Ribbon Banner -->
        <div class="ribbon-banner">
          <div class="ribbon-content">
            <p class="ribbon-text">DIRECTORATE</p>
          </div>
        </div>
      </div>

      <!-- Text Content -->
      <div class="text-content">
        <h1 class="main-title">FGEI INDUCTION PORTAL</h1>
        <p class="subtitle">Welcome to Your Journey</p>

        <!-- Loading Animation -->
        <div class="loading-dots">
          <div class="dot dot-1"></div>
          <div class="dot dot-2"></div>
          <div class="dot dot-3"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Fallback Content (shown after splash) -->
  <div v-else class="fallback-content">
    <div class="fallback-inner">
      <h1 class="fallback-title">Welcome!</h1>
      <p class="fallback-text">Educational Portal Loading...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Book, GraduationCap, Sparkles } from 'lucide-vue-next';

const show = ref(true);
const emit = defineEmits(['complete']);

let timer = null;

onMounted(() => {
  timer = setTimeout(() => {
    show.value = false;
    emit('complete');
  }, 4000);
});

onUnmounted(() => {
  if (timer) {
    clearTimeout(timer);
  }
});
</script>

<style scoped>
/* Main Container */
.splash-container {
  min-height: 100vh;
  background: linear-gradient(to bottom right, #059669, #0d9488, #16a34a);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  position: relative;
}

/* Animated Background Particles */
.particles {
  position: absolute;
  inset: 0;
  overflow: hidden;
}

.particle {
  position: absolute;
  animation: float linear infinite;
}

.sparkle-icon {
  color: rgba(255, 255, 255, 0.2);
}

/* Main Content */
.main-content {
  position: relative;
  z-index: 10;
  text-align: center;
}

/* Logo Container */
.logo-container {
  margin-bottom: 2rem;
  position: relative;
}

/* Outer Glow Ring */
.outer-ring {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.outer-ring-inner {
  width: 18rem;
  height: 18rem;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.1);
  animation: ping-slow 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}

/* Middle Ring */
.middle-ring {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.middle-ring-inner {
  width: 16rem;
  height: 16rem;
  border-radius: 50%;
  border: 4px solid rgba(255, 255, 255, 0.3);
  animation: spin-slow 8s linear infinite;
}

/* Logo Circle */
.logo-circle {
  position: relative;
  margin: 0 auto;
  width: 14rem;
  height: 14rem;
  background: linear-gradient(to bottom right, #ffffff, #d1fae5);
  border-radius: 50%;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  display: flex;
  align-items: center;
  justify-content: center;
  animation: scale-in 1.5s ease-out;
  border: 8px solid #f59e0b;
}

/* Inner Circle */
.inner-circle {
  width: 12rem;
  height: 12rem;
  background: linear-gradient(to bottom right, #ef4444, #dc2626);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
  animation: pulse-slow 3s ease-in-out infinite;
}

/* Icon Container */
.icon-container {
  position: relative;
  animation: float-gentle 3s ease-in-out infinite;
}

.book-icon {
  color: white;
}

.grad-cap-wrapper {
  position: absolute;
  top: -0.5rem;
  right: -0.5rem;
  animation: bounce-slow 2s ease-in-out infinite;
}

.grad-cap-icon {
  color: #fcd34d;
}

/* Ribbon Banner */
.ribbon-banner {
  position: absolute;
  bottom: -1.5rem;
  left: 50%;
  transform: translateX(-50%);
  width: 16rem;
  animation: slide-up 1s ease-out 0.5s both;
}

.ribbon-content {
  background: linear-gradient(to right, #d97706, #f59e0b, #d97706);
  padding: 0.5rem 1.5rem;
  border-radius: 0.5rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.ribbon-text {
  color: white;
  font-weight: bold;
  font-size: 0.875rem;
  letter-spacing: 0.1em;
  margin: 0;
}

/* Text Content */
.text-content {
  margin-top: 4rem;
  animation: fade-in-up 1s ease-out 1s both;
}

.main-title {
  font-size: 2.25rem;
  font-weight: bold;
  color: white;
  letter-spacing: 0.025em;
  animation: slide-down 1s ease-out 0.8s both;
  margin: 0 0 1rem 0;
}

.subtitle {
  font-size: 1.25rem;
  color: #a7f3d0;
  font-weight: 600;
  animation: fade-in-delayed 1s ease-out 1.5s both;
  margin: 0 0 2rem 0;
}

/* Loading Animation */
.loading-dots {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  animation: fade-in-delayed-2 1s ease-out 2s both;
}

.dot {
  width: 0.75rem;
  height: 0.75rem;
  background-color: white;
  border-radius: 50%;
}

.dot-1 {
  animation: bounce-slow 1s ease-in-out 0s infinite;
}

.dot-2 {
  animation: bounce-slow 1s ease-in-out 0.2s infinite;
}

.dot-3 {
  animation: bounce-slow 1s ease-in-out 0.4s infinite;
}

/* Fallback Content */
.fallback-content {
  min-height: 100vh;
  background: linear-gradient(to bottom right, #ecfdf5, #ccfbf1);
  display: flex;
  align-items: center;
  justify-content: center;
}

.fallback-inner {
  text-align: center;
}

.fallback-title {
  font-size: 2.25rem;
  font-weight: bold;
  color: #065f46;
  margin-bottom: 1rem;
}

.fallback-text {
  color: #4b5563;
}

/* Animations */
@keyframes float {
  0%, 100% {
    transform: translateY(0px) translateX(0px);
    opacity: 0;
  }
  50% {
    transform: translateY(-20px) translateX(10px);
    opacity: 1;
  }
}

@keyframes float-gentle {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
}

@keyframes scale-in {
  0% {
    transform: scale(0) rotate(0deg);
    opacity: 0;
  }
  50% {
    transform: scale(1.1) rotate(180deg);
  }
  100% {
    transform: scale(1) rotate(360deg);
    opacity: 1;
  }
}

@keyframes ping-slow {
  0% {
    transform: scale(1);
    opacity: 0.8;
  }
  100% {
    transform: scale(1.5);
    opacity: 0;
  }
}

@keyframes spin-slow {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

@keyframes pulse-slow {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

@keyframes bounce-slow {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

@keyframes slide-up {
  0% {
    transform: translateX(-50%) translateY(40px);
    opacity: 0;
  }
  100% {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
  }
}

@keyframes slide-down {
  0% {
    transform: translateY(-30px);
    opacity: 0;
  }
  100% {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes fade-in-up {
  0% {
    transform: translateY(20px);
    opacity: 0;
  }
  100% {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes fade-in-delayed {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

.fade-in-delayed-2 {
  animation: fade-in-delayed 1s ease-out 2s both;
}

@keyframes fade-in-delayed-2 {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
</style>
