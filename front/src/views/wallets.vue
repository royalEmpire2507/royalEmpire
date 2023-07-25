<script setup>
  import { ref } from 'vue'
  import card1 from '../assets/media/bg/card1.jpg';
  import bannerImage from '../assets/media/bg/banner1.jpg';
  import bannerImage3 from '../assets/media/bg/banner3.jpg';
  import bannerImage4 from '../assets/media/bg/banner4.jpg';
  const activeTab = ref('inicio')
</script>

<template>
  <header class="navbar">
    <div class="navbar-logo">Logo</div>
    <nav class="navbar-items">
      <el-menu :default-active="activeTab" mode="horizontal" @select="handleMenuClick" background-color="#1e1e2d" text-color="#fff" active-text-color="#fff">
        <el-menu-item index="inicio">Inicio</el-menu-item>
        <el-menu-item index="acerca-de">Acerca de</el-menu-item>
        <el-menu-item index="nosotros">Nosotros</el-menu-item>
        <el-submenu index="ingresa" popper-class="submenu-right">
          <template #title>
            <span>Ingresa</span>
          </template>
          <el-menu-item index="registrarse">Registrarse</el-menu-item>
          <el-menu-item index="ingresar">Ingresar</el-menu-item>
        </el-submenu>
      </el-menu>
    </nav>
  </header>
  <main class="main-content">
    <div class="banner" :style="`background-image: url(${bannerImage})`">
      <!-- Contenido adicional que desees agregar sobre el banner -->
      <h1>Bienvenido a mi página con Element Plus</h1>
      <p>Este es un ejemplo básico de cómo construir una página usando Element Plus.</p>
    </div>
    <div class="banner-card">
      <el-col v-for="(o, index) in 3" :key="o" :span="8" :offset="index > 0 ? 2 : 0">
        <el-card :body-style="{ padding: '2px' }">
          <img :src="card1" class="image"/>
          <div style="padding: 14px">
            <span>Yummy hamburger</span>
          </div>
        </el-card>
      </el-col>
    </div>
    <div class="banner" :style="`background-image: url(${bannerImage3})`">
      <!-- Contenido adicional que desees agregar sobre el banner -->
      <h1>Bienvenido a mi página con Element Plus</h1>
      <p>Este es un ejemplo básico de cómo construir una página usando Element Plus.</p>
    </div>
    <div class="banner-card">
      <el-col v-for="(o, index) in 3" :key="o" :span="8" :offset="index > 0 ? 2 : 0">
        <el-card :body-style="{ padding: '2px' }">
          <img :src="card1" class="image"/>
          <div style="padding: 14px">
            <span>Yummy hamburger</span>
            <div class="bottom">
              <time class="time">{{ currentDate }}</time>
              <el-button text class="button">Operating</el-button>
            </div>
          </div>
        </el-card>
      </el-col>
    </div>
    <div class="banner" :style="`background-image: url(${bannerImage4})`">
      <!-- Contenido adicional que desees agregar sobre el banner -->
      <h1>Bienvenido a mi página con Element Plus</h1>
      <p>Este es un ejemplo básico de cómo construir una página usando Element Plus.</p>
    </div>
  </main>
  <footer class="footer">
    <p>© 2023 Todos los derechos reservados</p>
  </footer>
</template>

<style>
.navbar {
  background-color: #1e1e2d;
  color: #fff;
  padding: 10px;
  display: flex;
  justify-content: space-between;
}

.navbar-logo {
  font-size: 20px;
}

.navbar-items {
  display: flex;
  align-items: center;
}

.submenu-right .el-submenu__popover {
  right: 0;
}

.main-content {
  /* padding: 20px; */
  margin-bottom: 50px;
}

.banner {
  height: 500px; /* Ajusta la altura según tus necesidades */
  background-size: cover;
  background-position: center;
  color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  font-size: 24px;
  font-weight: bold;
}

.banner-card {
  height: 500px;
  padding: 20px 0px 20px 0px;
  display: flex;
}

.footer {
  background-color: #f8f8f8;
  color: #333;
  margin-top: 50px;
  padding: 10px;
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
}

.time {
  font-size: 12px;
  color: #999;
}

.bottom {
  margin-top: 13px;
  line-height: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.button {
  padding: 0;
  min-height: auto;
}

.image {
  width: 100%;
  display: block;
}
</style>