
import '../css/app.css';
import './bootstrap';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Vue3Toastify from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { Chart } from 'chart.js';
import Swal from 'sweetalert2'
window.Swal = Swal

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue')
    ),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(Vue3Toastify, { autoClose: 3000 });

    // Expose Chart.js globally
    app.config.globalProperties.$chart = Chart;

    return app.mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
