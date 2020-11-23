<template>
  <div>
    <div class="page-header pr-0">
      <h2>
        <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
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
            <th>Codigo</th>
            <th>Descripcion</th>
            <th class="text-center">Unidad</th>
            <th class="text-center">Moneda</th>
            <th class="text-center">Precio Lista</th>
            <th class="text-center">Stock</th>
          </tr>
          <tr slot-scope="{ index, row }">
            <td>{{ index }}</td>
            <td>{{ row.item_code }}</td>
            <td>{{ row.description }}</td>
            <td>{{ row.unit_type_id }}</td>
            <td class="text-center">{{ row.currency_type_id}}</td>
            <td class="text-center">{{ row.price_list }}</td>
            <td class="text-center">{{ row.stock }}</td>
          </tr>
        </data-table>
      </div>
    </div>
  </div>
</template>
<script>
import DataTable from "../../components/DataTablePrecieList.vue";
export default {
  components: { DataTable },
  data() {
    return {
      title: null,
      resource: "reports/precie-list",
    };
  },
  created() {
    this.title = "Reporte de listado de precios";
  },
  methods: {},
};
</script>
