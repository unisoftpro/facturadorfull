<template>
  <div>
    <div class="page-header pr-0">
      <h2>
        <a href="/dashboard">
          <i class="fas fa-tachometer-alt"></i>
        </a>
      </h2>
      <ol class="breadcrumbs">
        <li class="active">
          <span>{{ title }}</span>
        </li>
      </ol>
      <div class="right-wrapper pull-right"></div>
    </div>
    <div class="card mb-0">
      <div class="card-header bg-info">
        <h3 class="my-0">{{ title }}</h3>
      </div>
      <div class="card-body">
        <data-table :resource="resource">
          <tr slot="heading">
            <th>#</th>
            <th>Codigo Articulo</th>
            <th>Descripcion</th>
            <th class="text-center">Codigo Equivalente</th>
            <th class="text-center">Moneda</th>
            <th class="text-center">Saldo</th>
            <th class="text-center">Precio FOB</th>
            <th class="text-center">Total Art.x Mon</th>
            <th class="text-center">Total Art.En Soles</th>
          </tr>
          <tr slot-scope="{ index, row }">
            <td>{{ index }}</td>
            <td>{{ row.item_code }}</td>
            <td>{{ row.description }}</td>
            <td class="text-center">{{ row.internal_id }}</td>
            <td class="text-center">{{ row.currency_type_id }}</td>
            <td class="text-center">{{ row.saldos }}</td>
            <td class="text-center">{{ row.price_fob }}</td>
            <td class="text-center">{{ row.Totalart }}</td>
            <td class="text-center">{{ row.Totalart }}</td>
          </tr>
        </data-table>
      </div>
    </div>
  </div>
</template>
<script>
import DataTable from "../../components/DataTableValutedBalances.vue";

export default {
  components: { DataTable },
  data() {
    return {
      title: null,
      resource: "reports/valuted-balances",
    };
  },
  async created() {
    this.title = "Reporte de saldos cantidades valorizados";
  },
  methods: {},
};
</script>
