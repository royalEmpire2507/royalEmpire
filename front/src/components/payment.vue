<script setup>
import { ref } from 'vue'

const addPaymentInfo = ref(false)
</script>

<template>
  <li class="kt-menu__item" aria-haspopup="true">
    <a target="_blank" @click="addPaymentInfo = true" class="kt-menu__link">
      <i class="kt-menu__link-icon flaticon2-arrow-up"></i>
      <span class="kt-menu__link-text">Deposito</span>
    </a>
  </li>

  <el-dialog title="Agregar información de pago" v-model="addPaymentInfo" :center="true">
    <!-- Título del modal -->
    <div class="modal-header">
      <h5 class="modal-title">Complete la información de pago</h5>
    </div>
    <!-- Contenido del modal -->
    <div class="modal-body">
      <div class="form-group">
        <label for="cardNumber">Número de tarjeta:</label>
        <input type="text" class="form-control" id="cardNumber" v-model="cardNumber" placeholder="Ingrese el número de tarjeta" />
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col">
            <label for="expiryDate">Fecha de vencimiento (MM/YY):</label>
            <input type="text" class="form-control" id="expiryDate" v-model="expiryDate" placeholder="MM/YY" />
          </div>
          <div class="col">
            <label for="CVV">CVV:</label>
            <input type="text" class="form-control" id="cvv" v-model="cvv" placeholder="CVV" />
          </div>
        </div>
      </div>
    </div>
    <!-- Botones de acción del modal -->
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" @click="addPaymentInfo = false">Cancelar</button>
      <button type="button" class="btn btn-primary" @click="submitPaymentInfo">Guardar</button>
    </div>
  </el-dialog>
</template>
<style>
</style>