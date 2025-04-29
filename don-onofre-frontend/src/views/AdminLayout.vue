<template>
  <div class="admin">
    <AdminNav />
    <router-view />
  </div>
</template>

<script setup>
import AdminNav from '../components/AdminNav.vue'
</script>

<style scoped>
.admin {
  max-width: 1000px;
  margin: auto;
  padding: 20px;
}
</style>