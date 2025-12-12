<template>
  <div class="relative h-full">
    <div class="relative h-64">
      <canvas ref="chartRef"></canvas>
    </div>
    <div v-if="hoverData" class="p-3 mt-4 rounded-lg bg-gray-50">
      <div class="grid grid-cols-2 gap-2">
        <div v-for="(value, key) in hoverData" :key="key" class="text-sm">
          <span class="font-medium">{{ key }}:</span> {{ value }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

export default {
  name: 'UserOverviewChart',
  setup() {
    const chartRef = ref(null);
    const hoverData = ref(null);
    let chartInstance = null;

    onMounted(() => {
      if (chartRef.value) {
        const ctx = chartRef.value.getContext('2d');

        chartInstance = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ['Total Registered', 'Approved', 'Pending', 'Total Income'],
            datasets: [{
              label: 'Percentage',
              data: [11.1, 33.3, 25.9, 29.6],
              backgroundColor: ['rgb(11, 67, 27)', '#042954', 'rgb(11, 67, 27)', '#042954'],
              borderColor: ['#75C9C9', '#36A2EB', '#FFC107', '#9966FF'],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                  callback: function(value) {
                    return value + '%';
                  }
                }
              }
            },
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    return context.parsed.y + '%';
                  },
                  afterBody: () => {
                    hoverData.value = {
                      'Total Registered': 11.1,
                      'Approved': 33.3,
                      'Pending': 25.9,
                      'Total Income': 29.6
                    };
                  }
                }
              }
            },
            onClick: (evt, elements) => {
              if (elements.length > 0) {
                const index = elements[0].index;
                const label = chartInstance.data.labels[index];
                const value = chartInstance.data.datasets[0].data[index];
                hoverData.value = { [label]: value };
              }
            }
          }
        });
      }
    });

    onBeforeUnmount(() => {
      if (chartInstance) {
        chartInstance.destroy();
      }
    });

    return { chartRef, hoverData };
  }
};
</script>
