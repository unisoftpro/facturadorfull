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

      <div class="right-wrapper pull-right">
        <button
          type="button"
          class="btn btn-custom btn-sm mt-2 mr-2"
          @click.prevent="showDialogAddItem = true"
        >
          + Agregar
        </button>
      </div>
    </div>
    <div class="card mb-0">
      <div class="card-header bg-info">
        <h3 class="my-0">{{ title }}</h3>
      </div>
      <div class="card-body">
        <data-table :resource="resource">
          <tr slot="heading">
            <th>#</th>
            <th>Nombre Producto</th>
            <th>Moneda</th>
            <th>Tipo de Lista</th>
            <th class="text-right">Acciones</th>
          </tr>
          <tr slot-scope="{ index, row }">
            <td>{{ index }}</td>
            <td>{{ row.name }}</td>
            <td>{{ row.currency_type_id }}</td>
            <td>{{ row.description }}</td>
            <td class="text-right">
              <button
                type="button"
                class="btn waves-effect waves-light btn-xs btn-primary"
                @click.prevent="
                  clickEdit(
                    row.id,
                    row.currency_type_id,
                    row.list_type_id,
                    row.description,
                    row.currencydescription
                  )
                "
              >
                Editar
              </button>
            </td>
          </tr>
        </data-table>
      </div>
      <list-price-items
        :showDialog.sync="showDialogOptions"
        :recordId="recordId"
        :currencyTypeId="currency_type_id"
        :listTypeId="list_type_id"
        :currencyTypeDescription="list_type_description"
        :currencyDescription="currencydescription"
        :showClose="true"
      ></list-price-items>
      <item-form
        :showDialog.sync="showDialogAddItem"
        :showClose="true"
      ></item-form>
    </div>
  </div>
</template>
<script>
import DataTable from "../../components/DataTableListPriceItem.vue";
import ListPriceItems from "./partials/items.vue";
import ItemForm from "./partials/item.vue";
export default {
  components: { DataTable, ListPriceItems, ItemForm },
  data() {
    return {
      title: null,
      resource: "precielist",
      showDialogOptions: false,
      recordId: null,
      currency_type_id: null,
      list_type_id: null,
      list_type_description: null,
      currencydescription: null,
      showDialogAddItem: false,
    };
  },
  created() {
    this.title = "listado de precios";
  },
  methods: {
    clickEdit(
      recordId = null,
      currency_type_id = null,
      list_type_id = null,
      list_type_description = null,
      currencydescription = null
    ) {
      this.recordId = recordId;
      this.currency_type_id = currency_type_id;
      this.list_type_id = list_type_id;
      this.showDialogOptions = true;
      this.list_type_description = list_type_description;
      this.currencydescription = currencydescription;
    },
    clickCreate() {
        this.showDialogAddItem=true;
    },
  },
};
</script>
