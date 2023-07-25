<template>
  <div>
    <div ref="chart" style="height: 400px"></div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import ApexCharts from 'vue3-apexcharts'

export default {
  components: {
    ApexCharts,
  },
  setup() {
    const chart = ref(null)

    onMounted(async () => {
      try {
        const response = await axios.get('https://api.tradingview.com/v1/symbols/NASDAQ:AAPL/technicals/indicators', {
          params: {
            indicator: 'RSI',
            interval: '1d',
            access_token: 'your-access-token',
          },
        })

        const data = response.data

        new ApexCharts(chart.value, {
          series: [
            {
              name: 'RSI',
              data: data.s[0].v,
            },
          ],
          chart: {
            type: 'line',
            height: 350,
          },
          xaxis: {
            categories: data.s[0].t,
          },
        }).render()
      } catch (error) {
        console.error(error)
      }
    })

    return {
      chart,
    }
  },
}
</script>

<style scoped>
</style>
