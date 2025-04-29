<template>
  <div class="admin">
    <AdminNav />

    <div v-if="mostrarDashboard">
      <h2 class="titulo">🔄 Dashboard</h2>

      <div class="stats">
        <div class="card" @click="seleccionarFiltro('todas')">Total Órdenes: {{ resumen.total_ordenes || 0 }}</div>
        <div class="card" @click="seleccionarFiltro('pagadas')">Pagadas: {{ resumen.pagadas || 0 }}</div>
        <div class="card" @click="seleccionarFiltro('pendientes')">Pendientes: {{ resumen.pendientes || 0 }}</div>
        <div class="card" @click="seleccionarFiltro('canceladas')">Canceladas: {{ resumen.canceladas || 0 }}</div>
        <div class="card">Recaudado: Gs. {{ resumen.recaudado?.toLocaleString() || 0 }}</div>
      </div>

      <div v-if="ordenesFiltradas.length">
        <h3>📝 Órdenes {{ filtroTexto }}</h3>
        <ul class="lista">
          <li v-for="orden in ordenesFiltradas" :key="orden.id">
            #{{ orden.id }} - {{ orden.estado }} - {{ orden.cliente?.nombre_completo || 'Sin Cliente' }} - Gs. {{ orden.total?.toLocaleString() || 0 }}
          </li>
        </ul>
      </div>

      <div v-else>
        <h3>Últimos pedidos:</h3>
        <ul class="lista">
          <li v-for="orden in resumen.ultimos_pedidos || []" :key="orden.id">
            #{{ orden.id }} - {{ orden.estado }} - {{ orden.cliente?.nombre_completo || 'Sin Cliente' }} - Gs. {{ orden.total?.toLocaleString() || 0 }}
          </li>
        </ul>
      </div>
    </div>

    <router-view v-else />
  </div>
</template>