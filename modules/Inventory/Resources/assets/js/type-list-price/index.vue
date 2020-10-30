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
          @click.prevent="clickCreate()"
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
            <th>Codigo</th>
            <th>Descripcion</th>
            <th class="text-center">Opciones</th>
          </tr>
          <tr slot-scope="{ index, row }">
            <td>{{ index }}</td>
            <td>{{ row.id }}</td>
            <td>{{ row.description }}</td>
            <td class="text-center">
              <button
                type="button"
                class="btn waves-effect waves-light btn-xs btn-warning"
                @click="handleCurrentChange(row)"
              >
                Editar
              </button>
              <button
                type="button"
                class="btn waves-effect waves-light btn-xs btn-danger"
                @click.prevent="clickDelete(row.id)"
              >
                x
              </button>
            </td>
          </tr>
        </data-table>
        <form-crete-or-update
          :showDialog.sync="showDialogOptions"
          :typeListId="typelistid"
          :typeListDescription="typelistdescription"
          :editOrCreate="edicreate"
        >
        </form-crete-or-update>
      </div>
    </div>
  </div>
</template>
<script>
import DataTable from "../../components/DataTableTypePriceList.vue";
import FormCreteOrUpdate from "./partials/form.vue";
import { deletable } from "@mixins/deletable";
import moment from "moment";
import queryString from "query-string";
export default {
  components: { DataTable, FormCreteOrUpdate },
  mixins: [deletable],
  data() {
    return {
      title: null,
      resource: "typepricelist",
      showDialogOptions: false,
      typelistid: null,
      typelistdescription: null,
      edicreate: false,
    };
  },
  created() {
    this.title = "Tipo de lista precios";
  },
  methods: {
    handleCurrentChange(row) {
      this.showDialogOptions = true;
      this.typelistid = row.id;
      this.typelistdescription = row.description;
      this.edicreate = true;
    },
    clickDelete(id) {
      this.destroy(`/${this.resource}/${id}`).then(() => {
        this.$eventHub.$emit("reloadData");
        location.reload();
      });
    },
    clickCreate() {
      this.showDialogOptions = true;
      this.typelistid = null;
      this.typelistdescription = null;
      this.edicreate = false;
    },
    clickFinalize() {
      location.href = `/${this.resource}`;
    },
  },
};
</script>
