<script setup>
import { onMounted, ref, onBeforeMount } from 'vue'
import { usersInformationSession } from '../store/usersStore.js'
import Drawals from '../components/drawals.vue'
import Payment from '../components/payment.vue'
import userBar from '../layouts/userBar.vue'
import logo from '../assets/media/logos/logo-light.png'
import { TickerTape } from 'vue-tradingview-widgets'

const storeSession = usersInformationSession()

onBeforeMount(async () => {
  await storeSession.userLogged()
})

onMounted(async () => {
  // var p = document.getElementById('kt_content')
  // var stylep = p.currentStyle || window.getComputedStyle(p)
  // let newheigth = parseInt(window.screen.availHeight) - (parseInt(stylep.paddingTop) + parseInt(stylep.paddingBottom) + parseInt(document.getElementById('kt_footer').offsetHeight) + parseInt(document.getElementById('kt_header').offsetHeight) + parseInt(document.getElementById('kt_subheader').offsetHeight))
  // document.getElementById('kt_content').style.height = newheigth + 'px'
  // eslint-disable-next-line no-undef
  KTLayout.init()
})
</script>
<template>
  <div>
    <!-- begin:: Page -->

    <!-- begin:: Header Mobile -->
    <div id="kt_header_mobile" class="kt-header-mobile kt-header-mobile--fixed">
      <div class="kt-header-mobile__logo">
        <a href="demo1/index.html">
          <img alt="Logo" :src="logo" style="width: 180px" />
        </a>
      </div>
      <div class="kt-header-mobile__toolbar">
        <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
        <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
      </div>
    </div>

    <!-- end:: Header Mobile -->
    <div class="kt-grid kt-grid--hor kt-grid--root">
      <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
        <!-- begin:: Aside -->
        <button class="kt-aside-close" id="kt_aside_close_btn"><i class="la la-close"></i></button>
        <div class="kt-aside kt-aside--fixed kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
          <!-- begin:: Aside -->
          <div class="kt-aside__brand kt-grid__item" id="kt_aside_brand">
            <div class="kt-aside__brand-logo">
              <a href="demo1/index.html">
                <img alt="Logo" :src="logo" style="width: 180px" />
              </a>
            </div>
            <div class="kt-aside__brand-tools">
              <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                      <path
                        d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                        id="Path-94"
                        fill="#000000"
                        fill-rule="nonzero"
                        transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
                      <path
                        d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                        id="Path-94"
                        fill="#000000"
                        fill-rule="nonzero"
                        opacity="0.3"
                        transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
                    </g>
                  </svg>
                </span>
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                      <path
                        d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                        id="Path-94"
                        fill="#000000"
                        fill-rule="nonzero" />
                      <path
                        d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                        id="Path-94"
                        fill="#000000"
                        fill-rule="nonzero"
                        opacity="0.3"
                        transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                    </g>
                  </svg>
                </span>
              </button>

              <!--
			<button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
			-->
            </div>
          </div>

          <!-- end:: Aside -->

          <!-- begin:: Aside Menu -->
          <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
            <div id="kt_aside_menu" class="kt-aside-menu" data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
              <ul class="kt-menu__nav">
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="http://localhost:3000/#/" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon-home"></i>
                    <span class="kt-menu__link-text">Inicio</span>
                  </a>
                </li>
                <li v-if="storeSession.loggedUser.rol == 'admin'" class="kt-menu__item" aria-haspopup="true">
                  <a href="http://localhost:3000/#/clients" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon-user"></i>
                    <span class="kt-menu__link-text">Clientes</span>
                  </a>
                </li>
                <li v-if="storeSession.loggedUser.rol == 'client'" class="kt-menu__section">
                  <h4 class="kt-menu__section-text">Movimientos</h4>
                  <i class="kt-menu__section-icon flaticon-more-v2"></i>
                </li>
                <Drawals v-if="storeSession.loggedUser.rol == 'client'" />
                <Payment v-if="storeSession.loggedUser.rol == 'client'" />
                <li v-if="storeSession.loggedUser.rol == 'client'" class="kt-menu__section">
                  <h4 class="kt-menu__section-text">Operaciones</h4>
                  <i class="kt-menu__section-icon flaticon-more-v2"></i>
                </li>
                <li v-if="storeSession.loggedUser.rol == 'client'" class="kt-menu__item" aria-haspopup="true">
                  <a target="_blank" href="https://keenthemes.com/metronic/preview/demo1/builder.html" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon-plus"></i>
                    <span class="kt-menu__link-text">Abiertas</span>
                  </a>
                </li>
                <li v-if="storeSession.loggedUser.rol == 'client'" class="kt-menu__item" aria-haspopup="true">
                  <a target="_blank" href="https://keenthemes.com/metronic/preview/demo1/builder.html" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon-close"></i>
                    <span class="kt-menu__link-text">Cerradas</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <!-- end:: Aside Menu -->
        </div>

        <!-- end:: Aside -->
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
          <!-- begin:: Header -->
          <div id="kt_header" class="kt-header kt-grid__item kt-header--fixed">
            <!-- begin:: Header Menu -->
            <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
            <div class="kt-header-menu-wrapper"></div>

            <!-- end:: Header Menu -->

            <!-- begin:: Header Topbar -->
            <div class="kt-header__topbar">
              <div class="kt-header__topbar-item kt-header__topbar-item--user">
                <div class="kt-header__topbar-wrapper" data-offset="0px,0px">
                  <div class="kt-header__topbar-user">
                    <span class="kt-header__topbar-username kt-hidden-mobile">
                      <b>$USD:</b>
                      &nbsp;
                      {{ storeSession.loggedUser.amount }}
                    </span>
                  </div>
                </div>
              </div>
              <!--begin: Search -->
              <div class="kt-header__topbar-item kt-header__topbar-item--search dropdown" id="kt_quick_search_toggle">
                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                  <span class="kt-header__topbar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect id="bound" x="0" y="0" width="24" height="24" />
                        <path
                          d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                          id="Path-2"
                          fill="#000000"
                          fill-rule="nonzero"
                          opacity="0.3" />
                        <path
                          d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                          id="Path"
                          fill="#000000"
                          fill-rule="nonzero" />
                      </g>
                    </svg>
                  </span>
                </div>
                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-lg">
                  <div class="kt-quick-search kt-quick-search--inline" id="kt_quick_search_inline">
                    <form method="get" class="kt-quick-search__form">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="flaticon2-search-1"></i></span>
                        </div>
                        <input type="text" class="form-control kt-quick-search__input" placeholder="Search..." />
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="la la-close kt-quick-search__close"></i></span>
                        </div>
                      </div>
                    </form>
                    <div class="kt-quick-search__wrapper kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200"></div>
                  </div>
                </div>
              </div>
              <!--end: Search -->

              <!--end: Search -->

              <!--begin: User Bar -->
              <userBar></userBar>
              <!--end: User Bar -->
            </div>

            <!-- end:: Header Topbar -->
          </div>

          <!-- end:: Header -->
          <div class="kt-subheader kt-griditem" id="kt_subheader">
            <div class="kt-container kt-container--fluid" style="width: 100%">
              <div class="kt-subheadertoolbar" style="width: 100%">
                <div class="kt-subheader__wrapper mt-1" style="width: 100%">
                  <TickerTape />
                </div>
              </div>
            </div>
          </div>
          <div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            <!-- begin:: Content -->
            <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
              <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                  <slot></slot>
                </div>
              </div>
            </div>
            <!-- end:: Content -->
          </div>

          <!-- begin:: Footer -->
          <div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
            <div class="kt-container kt-container--fluid">
              <div class="kt-footer__copyright">
                2019&nbsp;&copy;&nbsp;
                <a href="http://keenthemes.com/metronic" target="_blank" class="kt-link">Keenthemes</a>
              </div>
              <div class="kt-footer__menu">
                <a href="http://keenthemes.com/metronic" target="_blank" class="kt-footer__menu-link kt-link">About</a>
                <a href="http://keenthemes.com/metronic" target="_blank" class="kt-footer__menu-link kt-link">Team</a>
                <a href="http://keenthemes.com/metronic" target="_blank" class="kt-footer__menu-link kt-link">Contact</a>
              </div>
            </div>
          </div>

          <!-- end:: Footer -->
        </div>
      </div>
    </div>

    <!-- end:: Page -->

    <!-- begin::Scrolltop -->
    <div id="kt_scrolltop" class="kt-scrolltop">
      <i class="fa fa-arrow-up"></i>
    </div>

    <!-- end::Scrolltop -->
  </div>
</template>
<style scoped>
.kt-content {
  padding: 0 0 25px 0 !important;
}

@media (min-width: 1025px) {
  .kt-header--fixed.kt-subheader--fixed.kt-subheader--enabled .kt-wrapper {
    padding-top: 100px;
  }
}

#kt_footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: #f8f8f8;
}
</style>
