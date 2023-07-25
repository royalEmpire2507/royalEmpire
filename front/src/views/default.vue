<script setup>
import AppLayout from '../layouts/AppLayout.vue'
import { ForexHeatMap, ForexCrossRates, CryptoMarket, Chart, TechnicalAnalysis, MarketOverview, MarketData, StockMarket, EconomicCalendar, Ticker, TickerTape, SingleTicker, MiniChart, SymbolOverview, SymbolInfo, Screener, FundamentalData, CompanyProfile, Snaps } from 'vue-tradingview-widgets'
</script>

<template>
  <AppLayout>
    <div>
      <el-container style="height: 100vh">
        <el-header class="mb-5">
          <h1>
            Bienvenido a
            <b>Royal Empire</b>
          </h1>
        </el-header>
        <el-main>
          <!-- <ForexHeatMap />
            <ForexCrossRates />
            <CryptoMarket /> -->
          <Chart
            :options="{
              theme: 'dark',
            }" />
          <!-- <TechnicalAnalysis />
          <MarketOverview />
          <MarketData />
          <StockMarket />
          <EconomicCalendar />
          <Ticker />
          <TickerTape />
          <SingleTicker />
          <MiniChart />
          <SymbolOverview />
          <SymbolInfo />
          <Screener />
          <FundamentalData />
          <CompanyProfile />
          <Snaps /> -->
        </el-main>
        <el-footer>
          <p>© 2023 Todos los derechos reservados</p>
        </el-footer>
      </el-container>
    </div>
  </AppLayout>
</template>
<style>
h1,
h2 {
  text-align: center;
  margin: 20px 0;
}

p {
  text-align: center;
  margin-bottom: 10px;
}
</style>
