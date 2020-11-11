<template>
  <div class="card mb-0 pt-2 pt-md-0">
    <div class="card-header bg-info">
      <h3 class="my-0">Generar ingreso desde O. Compra</h3>
    </div>
    <div class="tab-content">
      <form autocomplete="off" @submit.prevent="submit">
        <div class="form-body">
          <div class="row">
            <div class="col-lg-4">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.warehouse_id }"
              >
                <label class="control-label">Almacén</label>
                <el-select v-model="form.warehouse_id" filterable>
                  <el-option
                    v-for="option in warehouses"
                    :key="option.id"
                    :value="option.id"
                    :label="option.description"
                  ></el-option>
                </el-select>
                <small
                  class="form-control-feedback"
                  v-if="errors.warehouse_id"
                  v-text="errors.warehouse_id[0]"
                ></small>
              </div>
            </div>

            <div class="col-lg-2">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.date_of_issue }"
              >
                <label class="control-label">Fec Emisión</label>
                <el-date-picker
                  v-model="form.date_of_issue"
                  type="date"
                  value-format="yyyy-MM-dd"
                  :clearable="false"
                ></el-date-picker>
                <small
                  class="form-control-feedback"
                  v-if="errors.date_of_issue"
                  v-text="errors.date_of_issue[0]"
                ></small>
              </div>
            </div>

            <div class="col-lg-2">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.invoice_description }"
              >
                <label class="control-label">Factura </label>
                <el-input v-model="form.invoice_description"></el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.invoice_description"
                  v-text="errors.invoice_description[0]"
                ></small>
              </div>
            </div>
          </div>
          <div class="row" v-if="purchase_orders.length > 0">
            <div class="col-md-12 mt-4">
              <p><strong>Seleccione una fila (O. Compra)</strong></p>
              <el-table
                ref="singleTable"
                :data="purchase_orders"
                highlight-current-row
                border
                @current-change="handleCurrentChange"
                style="width: 100%"
              >
                <el-table-column property="number" label="Número" width="100">
                </el-table-column>

                <el-table-column
                  property="date_of_issue"
                  label="Fecha emisión"
                  width="120"
                >
                </el-table-column>

                <el-table-column
                  property="purchase_order_state_id"
                  label="Est"
                  width="70"
                >
                </el-table-column>

                <el-table-column
                  property="purchase_order_state_description"
                  label="Estado Orden"
                >
                </el-table-column>

                <el-table-column
                  property="supplier_name"
                  label="Proveedor"
                  width="250"
                >
                </el-table-column>

                <el-table-column
                  property="work_order_number"
                  label="O. Trabajo"
                  width="100"
                >
                </el-table-column>

                <el-table-column property="currency_type_id" label="Moneda">
                </el-table-column>

                <el-table-column property="total" label="Total">
                </el-table-column>

                <el-table-column label="Acciones">
                  <template slot-scope="scope">
                    <el-button
                      type="primary"
                      @click="clickDetail(scope.$index, scope.row)"
                      >Detalle</el-button
                    >
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </div>
        </div>
        <div class="form-actions text-right mt-4">
          <el-button @click.prevent="close()">Cancelar</el-button>
          <el-button
            type="primary"
            native-type="submit"
            :loading="loading_submit"
            v-if="form.purchase_order"
            >Generar</el-button
          >
        </div>
      </form>
    </div>

    <detail-form
      :showDialog.sync="showDialog"
      :items="items"
      :number="number"
    ></detail-form>
  </div>
</template>

<script>
import DetailForm from "./partials/detail.vue";

export default {
  components: { DetailForm },
  data() {
    return {
      resource: "purchase-order-income",
      errors: {},
      form: {},
      form2: {},
      warehouses: [],
      purchase_orders: [],
      company: null,
      items: [],
      number: null,
      currentRow: {},
      showDialog: false,
      loading_submit: false,
    };
  },
  async created() {
    await this.initForm();
    await this.getTables();
  },
  methods: {
    async getTables() {
      await this.$http.get(`/${this.resource}/tables`).then((response) => {
        this.warehouses = response.data.warehouses;
        this.purchase_orders = response.data.purchase_orders;
      });
    },
    clickDetail(index, row) {
      this.items = row.items;
      this.number = row.number;
      this.showDialog = true;
      // console.log(index, row);
    },
    setCurrent(row) {
      this.$refs.singleTable.setCurrentRow(row);
    },
    handleCurrentChange(val) {
      // console.log(val)
      this.currentRow = val;
      this.form.purchase_order = val;
    },
    async validates() {
      if (!this.form.purchase_order) {
        return {
          success: false,
          message: "Debe seleccionar 1 orden de compra",
        };
      }

      return {
        success: true,
        message: null,
      };
    },
    initForm() {
      this.errors = {};

      this.form = {
        warehouse_id: null,
        invoice_description: null,
        date_of_issue: moment().format("YYYY-MM-DD"),
        purchase_order: null,
      };

      this.form2 = {
        warehouse_id: null,
        document_reference: null,
        cat_document_types_id: null,
        warehouse_income_reason_id: null,
        date_of_issue: moment().format("YYYY-MM-DD"),
        supplier_id: null,
        currency_type_id: "PEN",
        observation: null,
        reference_date: moment().format("YYYY-MM-DD"),
        purchase_order_id: null,
        work_order_id: null,
        original_total: 0,
        exchange_rate_sale: 1,
        national_total: 0,
        total_value: 0,
        total: 0,
        items:null,
      };
    },
    resetForm() {
      this.initForm();
      this.getTables();
    },
    async submit() {
      let validate = await this.validates();
      if (!validate.success) {
        return this.$message.error(validate.message);
      }

      this.loading_submit = true;
      await this.$http
        .post(`/${this.resource}`, this.form)
        .then((response) => {
          if (response.data.success) {
            this.$message.success(response.data.message);
            this.resetForm();
            this.getData(response.data.data[0]);
          } else {
            this.$message.error(response.data.message);
          }
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data;
          } else {
            this.$message.error(error.response.data.message);
          }
        })
        .then(() => {
          this.loading_submit = false;
        });
    },
    async getData(data) {

      this.form2.cat_document_types_id = data.cat_document_types_id;
      this.form2.currency_type_id =data.currency_type_id;
      this.form2.date_of_issue = data.date_of_issue;
      this.form2.document_reference = data.document_reference;
      this.form2.exchange_rate_sale = data.exchange_rate_sale;
      this.form2.national_total = data.national_total;
      this.form2.observation = data.observation;
      this.form2.original_total = data.original_total;
      this.form2.purchase_order_id = data.purchase_order_id;
      this.form2.reference_date = data.reference_date;
      this.form2.supplier_id = data.supplier_id;
      this.form2.total = data.total;
      this.form2.total_value = data.total_value;
      this.form2.warehouse_id = data.warehouse_id;
      this.form2.warehouse_income_reason_id =data.warehouse_income_reason_id;
      this.form2.work_order_id = data.work_order_id;
      this.form2.items=data.items;
      var resources = "warehouse-income";
      await this.$http
        .post(`/${resources}`, this.form2)
        .then((response) => {
          if (response.data.success) {

          } else {
            this.$message.error(response.data.message);
          }
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data;
          } else {
            this.$message.error(error.response.data.message);
          }
        })
        .then(() => {
          this.loading_submit = false;
        });
    },
    close() {
      location.href = `/${this.resource}`;
    },
  },
};
</script>
