<template>
  <div class="card mb-0 pt-2 pt-md-0">
    <div class="card-header bg-info">
      <h3 class="my-0">Ingreso a almacén</h3>
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

            <div class="col-lg-4">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.warehouse_income_reason_id }"
              >
                <label class="control-label">Motivo</label>
                <el-select
                  :disabled="form.items.length > 0"
                  v-model="form.warehouse_income_reason_id"
                  filterable
                  @change="changeWarehouseIncomeReason"
                >
                  <el-option
                    v-for="option in warehouse_income_reasons"
                    :key="option.id"
                    :value="option.id"
                    :label="option.description"
                  ></el-option>
                </el-select>
                <small
                  class="form-control-feedback"
                  v-if="errors.warehouse_income_reason_id"
                  v-text="errors.warehouse_income_reason_id[0]"
                ></small>
              </div>
            </div>

            <div class="col-lg-4">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.supplier_id }"
              >
                <label class="control-label">Proveedor</label>
                <el-select
                  v-model="form.supplier_id"
                  filterable
                  @change="changeSupplier"
                >
                  <el-option
                    v-for="option in suppliers"
                    :key="option.id"
                    :value="option.id"
                    :label="option.description"
                  ></el-option>
                </el-select>
                <small
                  class="form-control-feedback"
                  v-if="errors.supplier_id"
                  v-text="errors.supplier_id[0]"
                ></small>
              </div>
            </div>

            <div class="col-lg-2">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.date_of_issue }"
              >
                <label class="control-label">Fec. Emisión</label>
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
                :class="{ 'has-danger': errors.reference_date }"
              >
                <label class="control-label">Fec. Referencia</label>
                <el-date-picker
                  v-model="form.reference_date"
                  type="date"
                  value-format="yyyy-MM-dd"
                  :clearable="false"
                  @change="changeDateReference"
                ></el-date-picker>
                <small
                  class="form-control-feedback"
                  v-if="errors.reference_date"
                  v-text="errors.reference_date[0]"
                ></small>
              </div>
            </div>

            <div class="col-lg-2">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.currency_type_id }"
              >
                <label class="control-label">Moneda</label>
                <el-select
                  v-model="form.currency_type_id"
                  filterable
                  @change="changeCurrencyType"
                >
                  <el-option
                    v-for="option in currency_types"
                    :key="option.id"
                    :value="option.id"
                    :label="option.description"
                  ></el-option>
                </el-select>
                <small
                  class="form-control-feedback"
                  v-if="errors.currency_type_id"
                  v-text="errors.currency_type_id[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-6">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.observation }"
              >
                <label class="control-label">Observación</label>
                <el-input v-model="form.observation"></el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.observation"
                  v-text="errors.observation[0]"
                ></small>
              </div>
            </div>

            <div class="col-lg-2">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.purchase_order_id }"
              >
                <label class="control-label">O. Compra</label>
                <el-select
                  v-model="form.purchase_order_id"
                  filterable
                  @change="changePurcharse"
                >
                  <el-option
                    v-for="option in purchase_orders"
                    :key="option.id"
                    :value="option.id"
                    :label="option.number"
                  ></el-option>
                </el-select>
                <small
                  class="form-control-feedback"
                  v-if="errors.purchase_order_id"
                  v-text="errors.purchase_order_id[0]"
                ></small>
              </div>
            </div>

            <div class="col-lg-2">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.work_order_id }"
              >
                <label class="control-label">O. Trabajo</label>
                <el-select v-model="form.work_order_id" filterable>
                  <el-option
                    v-for="option in work_orders"
                    :key="option.id"
                    :value="option.id"
                    :label="option.number"
                  ></el-option>
                </el-select>
                <small
                  class="form-control-feedback"
                  v-if="errors.work_order_id"
                  v-text="errors.work_order_id[0]"
                ></small>
              </div>
            </div>

            <div class="col-lg-2">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.work_order_id }"
              >
                <label class="control-label">Tipo Documento</label>
                <el-select v-model="form.cat_document_types_id" filterable>
                  <el-option
                    v-for="option in documents_type"
                    :key="option.id"
                    :value="option.id"
                    :label="option.description"
                  ></el-option>
                </el-select>
                <small
                  class="form-control-feedback"
                  v-if="errors.cat_document_types_id"
                  v-text="errors.cat_document_types_id[0]"
                ></small>
              </div>
            </div>
            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.document_reference }"
              >
                <label class="control-label">Doc. Referencia</label>
                <el-input v-model="form.document_reference"></el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.document_reference"
                  v-text="errors.document_reference[0]"
                ></small>
              </div>
            </div>

            <div class="col-lg-2" v-if="form.currency_type_id == 'USD'">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.exchange_rate_sale }"
              >
                <label class="control-label"
                  >Tipo de cambio
                  <el-tooltip
                    class="item"
                    effect="dark"
                    content="Tipo de cambio del día, extraído de SUNAT"
                    placement="top-end"
                  >
                    <i class="fa fa-info-circle"></i>
                  </el-tooltip>
                </label>
                <el-input v-model="form.exchange_rate_sale" readonly></el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.exchange_rate_sale"
                  v-text="errors.exchange_rate_sale[0]"
                ></small>
              </div>
            </div>
            <div class="col-lg-12 col-md-6 d-flex align-items-end mt-4">
              <div class="form-group">
                <button
                  type="button"
                  class="btn waves-effect waves-light btn-primary"
                  @click.prevent="
                    showDialogAddItem = true;
                    editCreate = false;
                  "
                >
                  + Agregar Producto
                </button>
                <!--<el-button
                  v-if="enableButtonProcessPrice"
                  @click.prevent="clickProccessLocal"
                  type="warning"
                  icon="el-icon-s-tools"
                  >Procesar Precios Compra-Local</el-button
                >
                <el-popover
                  v-if="enableButtonProcessPrice"
                  placement="right"
                  width="350"
                  trigger="click"
                >
                  <div style="text-align: left; margin: 0">
                    <label for="">Tipo de lista:</label>
                    <el-select
                      v-model="form_process_price.list_type_id"
                      filterable
                    >
                      <el-option
                        v-for="option in type_list_prices"
                        :key="option.id + 'OP'"
                        :value="option.id"
                        :label="option.description"
                      ></el-option>
                    </el-select>

                    <label for="">Factor:</label>
                    <el-input-number
                      :min="0"
                      v-model="form_process_price.factor"
                    ></el-input-number>
                    <label for="">Moneda</label>
                    <el-select
                      v-model="form_process_price.currency_type_id"
                      filterable

                    >
                      <el-option
                        v-for="option in currency_types"
                        :key="option.id"
                        :value="option.id"
                        :label="option.description"
                      ></el-option>
                    </el-select>
                    <br />
                    <br />
                    <el-button
                      @click="clickProccessExterior"
                      type="primary"
                      plain
                      >Procesar</el-button
                    >
                  </div>

                  <el-button
                    type="warning"
                    icon="el-icon-s-tools"
                    slot="reference"
                    >Procesar Precios Compra</el-button
                  >
                </el-popover>-->
              </div>
            </div>
          </div>
          <div class="row mt-2" v-if="form.items.length > 0">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table">
                  <template v-if="this.editHeadTable">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Descripción</th>
                        <th class="text-center">Unidad</th>
                        <th class="text-right">Cantidad</th>

                        <th class="text-right">Precio lista</th>
                        <th class="text-right">Precio Venta Público</th>
                        <th class="text-right">Total</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(row, index) in form.items" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.item.full_description }}</td>
                        <td class="text-center">{{ row.item.unit_type_id }}</td>
                        <td class="text-right">{{ row.quantity }}</td>
                        <td class="text-right">
                          {{ currency_type.symbol }} {{ row.list_price }}
                        </td>
                        <td class="text-right">
                          {{ currency_type.symbol }} {{ row.retail_price }}
                        </td>
                        <td class="text-right">
                          {{ currency_type.symbol }} {{ row.total }}
                        </td>
                        <td class="text-right">
                          <button
                            type="button"
                            class="btn waves-effect waves-light btn-xs btn-danger"
                            @click.prevent="clickRemoveItem(index)"
                          >
                            x
                          </button>
                          <button
                            type="button"
                            class="btn waves-effect waves-light btn-xs btn-warning"
                            @click.prevent="clickEdit(row)"
                          >
                            Editar
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </template>
                  <template v-else>
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Descripción</th>
                        <th class="text-center">Unidad</th>
                        <th class="text-right">Cantidad</th>
                        <th class="text-right">Cantidad Pendiete</th>
                        <th class="text-right">Cantidad Pendiete</th>
                        <th class="text-right">Precio lista</th>
                        <th class="text-right">Precio Venta Público</th>
                        <th class="text-right">Total</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(row, index) in form.items" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.item.full_description }}</td>
                        <td class="text-center">{{ row.item.unit_type_id }}</td>
                        <td class="text-right">{{ row.quantity2 }}</td>
                        <td class="text-right">
                          {{ row.pending_quantity_income }}
                        </td>
                        <td class="text-right">
                          <input
                            @change="changeQuantityIncome(index, row.item_id)"
                            type="number"
                            name="number"
                            :id="index"
                            value=""
                            :min="0"
                            :max="row.pending_quantity_income"
                            step="1"
                          />
                        </td>
                        <td class="text-right">
                          {{ currency_type.symbol }} {{ row.list_price }}
                        </td>
                        <td class="text-right">
                          {{ currency_type.symbol }} {{ row.retail_price }}
                        </td>
                        <td class="text-right">
                          {{ currency_type.symbol }} {{ row.total }}
                        </td>
                        <td class="text-right">
                          <button
                            type="button"
                            class="btn waves-effect waves-light btn-xs btn-danger"
                            @click.prevent="clickRemoveItem(index)"
                          >
                            x
                          </button>
                          <button
                            type="button"
                            class="btn waves-effect waves-light btn-xs btn-warning"
                            @click.prevent="clickEdit(row)"
                          >
                            Editar
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </template>
                </table>
              </div>
            </div>

            <div class="col-md-12">
              <p class="text-right" v-if="form.total_value > 0">
                Sub Total: {{ currency_type.symbol }} {{ form.total_value }}
              </p>
              <p class="text-right" v-if="form.original_total > 0">
                Total Original: {{ currency_type.symbol }}
                {{ form.original_total }}
              </p>
              <p class="text-right" v-if="form.national_total > 0">
                Total Nacional: S/ {{ form.national_total }}
              </p>
              <p class="text-right" v-if="form.total > 0">
                Total: {{ currency_type.symbol }} {{ form.total }}
              </p>
            </div>
          </div>
        </div>
        <div class="form-actions text-right mt-4">
          <el-button @click.prevent="close()">Cancelar</el-button>
          <el-button
            type="primary"
            native-type="submit"
            :loading="loading_submit"
            v-if="form.items.length > 0"
            >Generar</el-button
          >
        </div>
      </form>
    </div>
    <tenant-item-warehouse-income
      :showDialog.sync="showDialogAddItem"
      :currency-type-id-active="form.currency_type_id"
      :exchange-rate-sale="form.exchange_rate_sale"
      :purchase-order-id="form.purchase_order_id"
      :warehouse-income-reason-id="form.warehouse_income_reason_id"
      :idItems="idItems"
      :quantityEdit="quantity"
      :editCreate="editCreate"
      :listPrice="list_price"
      :descontOne="descuento1"
      :descontTwo="descuento2"
      :descontThree="descuento3"
      :factorAlma="factorAlmacen"
      :factorVenta="factorVenta"
      @add="addRow"
      ref="item_form"
    >
    </tenant-item-warehouse-income>
    <!--<item-form
      :showDialog.sync="showDialogAddItem"
      :currency-type-id-active="form.currency_type_id"
      :exchange-rate-sale="form.exchange_rate_sale"
      :purchase-order-id="form.purchase_order_id"
      :warehouse-income-reason-id="form.warehouse_income_reason_id"
      :idItems="idItems"
      @add="addRow"
      ref="item_form"
    ></item-form>-->

    <warehouse-income-options
      :showDialog.sync="showDialogOptions"
      :recordId="recordId"
      :showClose="false"
    ></warehouse-income-options>
  </div>
</template>

<script>
import ItemForm from "./partials/item.vue";
import WarehouseIncomeOptions from "./partials/options.vue";

export default {
  props: ["id"],
  components: { ItemForm, WarehouseIncomeOptions },
  data() {
    return {
      showDialogOptions: false,
      resource: "warehouse-income",
      errors: {},
      form: {},
      quantity: null,
      purcharse_id: null,
      editCreate: null,
      additems: {},
      warehouses: [],
      purchase_orders: [],
      warehouse_income_reasons: [],
      suppliers: [],
      currency_types: [],
      work_orders: [],
      documents_type: [],
      company: null,
      number: null,
      recordId: null,
      currentRow: {},
      showDialogAddItem: false,
      loading_submit: false,
      currency_type: {},
      type_list_prices: [],
      form_process_price: {
        list_type_id: null,
        factor: null,
        currency_type_id: null,
      },
      idItems: null,
      list_price: null,
      descuento1: null,
      descuento2: null,
      descuento3: null,
      factorAlmacen: null,
      factorVenta: null,
      editItemstable: false,
      editHeadTable: false,
    };
  },
  async created() {
    await this.initForm();
    await this.getTables();
    await this.isUpdate();
  },
  computed: {
    enableButtonProcessPrice() {
      return ["103", "104"].includes(this.form.warehouse_income_reason_id);
    },
  },
  methods: {
    async isUpdate() {
      if (this.id) {
        this.form.id = this.id;

        await this.$http
          .get(`/${this.resource}/record_warehouse/${this.id}`)
          .then((response) => {
            this.form.warehouse_id = response.data.data.warehouse_id;
            this.form.warehouse_income_reason_id =
              response.data.data.warehouse_income_reason_id;
            this.form.supplier_id = response.data.data.supplier_id;
            this.form.date_of_issue = response.data.data.date_of_issue;
            this.form.reference_date = response.data.data.reference_date;
            this.form.currency_type_id = response.data.data.currency_type_id;
            this.form.items = response.data.data.items;
            this.editItemstable = true;
            this.editHeadTable = true;

            this.form.cat_document_types_id =
              response.data.data.cat_document_types_id;
            this.form.document_reference =
              response.data.data.document_reference;
            this.form.warehouse_id = response.data.data.warehouse_id;
            this.form.observation = response.data.data.observation;
            this.calculateTotal();

            this.purcharse_id= response.data.data.purchase_order_id;

            this.changeSupplier();
            //this.getPurchaseId(this.purcharse_id);

            this.form.purchase_order_id = response.data.data.purchase_order_id;

            this.changePurcharse();
            this.form.work_order_id= response.data.data.work_order_id;
            // this.form.suppliers = Object.values(response.data.data.purchase_quotation.suppliers);
          });
      } else {
      }
    },
    changeSupplier() {
      this.getExchangeRatePurchaseOrder();
      this.getPurchase();

      this.getPurchaseId(this.purcharse_id);
    },
    changeDateReference() {
      this.getExchangeRatePurchaseOrder();
    },
    changeWarehouseIncomeReason() {
      // this.renewCalculate()
    },
    async changeCurrencyType() {
      if (this.form.currency_type_id == "USD") {
        if (!this.form.supplier_id) {
          this.form.currency_type_id = "PEN";
          return this.$message.error(
            "Debe seleccionar un proveedor para buscar el tipo de cambio"
          );
        }

        await this.getExchangeRatePurchaseOrder();
      }

      this.currency_type = await _.find(this.currency_types, {
        id: this.form.currency_type_id,
      });
      await this.renewCalculate();
    },
    async renewCalculate() {
      let items = [];
      await this.form.items.forEach((row) => {
        items.push(
          this.$refs.item_form.calculateRowItem(
            row,
            this.form.currency_type_id,
            this.form.exchange_rate_sale
          )
        );
      });

      this.form.items = items;
      this.calculateTotal();
    },
    async getExchangeRatePurchaseOrder() {
      if (this.form.currency_type_id == "USD") {
        await this.$http
          .get(
            `/${this.resource}/exchange-rate/${this.form.reference_date}/${this.form.supplier_id}`
          )
          .then((response) => {
            this.form.exchange_rate_sale = response.data.exchange_rate_sale;

            if (!response.data.success) {
              this.$message.warning(response.data.message);
            }
          });
      }
    },
    changeQuantityIncome(index, id) {
      var x = $("#" + index).val();
      this.form.items.map(function (dato) {
        if (id == dato.item_id) {
          dato.attended_quantity = parseInt(x);
          dato.quantity = parseInt(x);
        }
      });
    },
    async getPurchase() {
      if (this.form.supplier_id) {
        await this.$http
          .get(`/${this.resource}/purcharse/${this.form.supplier_id}`)
          .then((response) => {
            this.purchase_orders = response.data.record;
            //this.form.exchange_rate_sale = response.data.exchange_rate_sale;

            /*if (!response.data.success) {
              this.$message.warning(response.data.message);
            }*/
          });
      }
    },
    async getPurchaseId(id_p) {
      if (this.form.supplier_id & this.form.id) {

        await this.$http
          .get(`/${this.resource}/purcharse2/${this.form.supplier_id}/${id_p}`)
          .then((response) => {
            this.purchase_orders.push(response.data.record[0]);

          });
      }
    },
    async changePurcharse() {
      if (this.form.purchase_order_id) {
          if(this.form.id){

          }else{
            this.form.items = [];
          }

        await this.$http
          .get(`/${this.resource}/workorder/${this.form.purchase_order_id}`)
          .then((response) => {
            this.work_orders = response.data.work_order;
          });
          if(this.form.id){

          }else{
            this.addItems("a");
          }

        /*if (this.form.id) {
        } else {

        }*/
      }
    },
    async addItems(data) {
      for (let i = 0; i < this.purchase_orders.length; i++) {
        if (this.purchase_orders[i]["id"] == this.form.purchase_order_id) {
          this.form.currency_type_id = "";
          this.form.work_order_id = "";
          this.form.date_of_issue = "";
          this.form.warehouse_income_reason_id = "";
          this.form.currency_type_id = this.purchase_orders[i][
            "currency_type_id"
          ];
          this.form.work_order_id = this.purchase_orders[i]["work_order_id"];
          this.form.date_of_issue = this.purchase_orders[i]["date_of_issue"];

          if (this.purchase_orders[i]["purchase_order_type_id"] == "01") {
            this.form.warehouse_income_reason_id = "104";
          } else {
            this.form.warehouse_income_reason_id = "103";
          }
          for (
            let index = 0;
            index < this.purchase_orders[i]["items"].length;
            index++
          ) {
            if (
              parseInt(
                this.purchase_orders[i]["items"][index][
                  "pending_quantity_income"
                ]
              ) > 0
            ) {
              let datos = {
                item_id: this.purchase_orders[i]["items"][index]["item_id"],
                category_id: this.purchase_orders[i]["items"][index]["itemss"][
                  "category_id"
                ],
                family_id: this.purchase_orders[i]["items"][index]["itemss"][
                  "family_id"
                ],
                quantity2: this.purchase_orders[i]["items"][index]["quantity"],
                quantity: this.purchase_orders[i]["items"][index]["quantity"],
                pending_quantity_income: parseInt(
                  this.purchase_orders[i]["items"][index][
                    "pending_quantity_income"
                  ]
                ),
                list_price: this.purchase_orders[i]["items"][index][
                  "unit_price"
                ],
                total: this.purchase_orders[i]["items"][index]["total"],
                total_value: this.purchase_orders[i]["items"][index][
                  "total_value"
                ],
                attended_quantity: 0,
                unit_value: this.purchase_orders[i]["items"][index][
                  "unit_value"
                ],
                unit_price: this.purchase_orders[i]["items"][index][
                  "unit_price"
                ],
                item: this.purchase_orders[i]["items"][index]["item"],
                unit_type_id: this.purchase_orders[i]["items"][index]["itemss"][
                  "unit_type_id"
                ],
                discount_four: 0,
                discount_one: 0,
                discount_three: 0,
                discount_two: 0,
                warehouse_factor: 0,
                last_factor: 0,
                num_price: 0,
                last_factor: "0.00",
              };
              this.addRow(datos);
            }
          }
        }
      }
    },
    async getTables() {
      await this.$http.get(`/${this.resource}/tables`).then((response) => {
        this.warehouses = response.data.warehouses;
        //this.purchase_orders = response.data.purchase_orders;
        this.warehouse_income_reasons = response.data.warehouse_income_reasons;
        this.suppliers = response.data.suppliers;
        this.currency_types = response.data.currency_types;
        //this.work_orders = response.data.work_orders;
        this.documents_type = response.data.document_types_invoice;
        this.currency_type = _.find(this.currency_types, {
          id: this.form.currency_type_id,
        });
        this.form.warehouse_income_reason_id =
          this.warehouse_income_reasons.length > 0
            ? this.warehouse_income_reasons[0].id
            : null;
        this.type_list_prices = response.data.type_list_prices;
      });
    },
    async validates() {
      // if(!this.form.purchase_order){
      //     return  {
      //         success : false,
      //         message : 'Debe seleccionar 1 orden de compra'
      //     }
      // }

      return {
        success: true,
        message: null,
      };
    },
    clickRemoveItem(index) {
      this.form.items.splice(index, 1);
      this.calculateTotal();
    },
    addRow(row) {
      if ((this.form.items.length > 0) & this.id & this.showDialogAddItem) {
        this.form.items.map(function (dato) {
          if (row.item_id === dato.item_id) {
            dato.discount_four = row.discount_four;
            dato.discount_one = row.discount_one;
            dato.discount_three = row.discount_three;
            dato.discount_two = row.discount_two;
            dato.last_factor = row.last_factor;
            dato.last_purchase_price = row.last_purchase_price;
            dato.letter_price = row.letter_price;
            dato.price_fob_alm = row.price_fob_alm;
            dato.price_fob_alm_igv = row.price_fob_alm_igv;
            dato.retail_price = row.retail_price;
            dato.sale_profit_factor = row.sale_profit_factor;
            dato.last_factor = row.last_factor;
            dato.num_price = row.num_price;
            dato.warehouse_factor = row.warehouse_factor;
          }
        });
        this.calculateTotal();
      } else if ((this.form.items.length > 0) & this.showDialogAddItem) {
        this.form.items.map(function (dato) {
          if (row.item_id === dato.item_id) {
            dato.discount_four = row.discount_four;
            dato.discount_one = row.discount_one;
            dato.discount_three = row.discount_three;
            dato.discount_two = row.discount_two;
            dato.last_factor = row.last_factor;
            dato.last_purchase_price = row.last_purchase_price;
            dato.letter_price = row.letter_price;
            dato.price_fob_alm = row.price_fob_alm;
            dato.price_fob_alm_igv = row.price_fob_alm_igv;
            dato.retail_price = row.retail_price;
            dato.sale_profit_factor = row.sale_profit_factor;
            dato.last_factor = row.last_factor;
            dato.num_price = row.num_price;
            dato.warehouse_factor = row.warehouse_factor;
          }
        });
        this.calculateTotal();
      } else {
        this.form.items.push(row);
        this.calculateTotal();
      }
    },
    calculateTotal() {
      let total_value = 0;
      let total = 0;

      this.form.items.forEach((row) => {
        total_value += parseFloat(row.total_value);
        total += parseFloat(row.total);
      });

      this.form.total_value = _.round(total_value, 2);
      this.form.total = _.round(total, 2);
      this.form.original_total = this.form.total;
      this.form.national_total =
        this.form.currency_type_id == "USD"
          ? _.round(this.form.total * this.form.exchange_rate_sale, 2)
          : this.form.total;
    },
    initForm() {
      this.errors = {};
      this.purcharse_id=null;
      this.form = {
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
        id: null,
        items: [],
      };
    },
    resetForm() {
      this.initForm();
      // this.getTables()
    },
    async submit() {
      // let validate = await this.validates()
      // if(!validate.success) {
      //     return this.$message.error(validate.message);
      // }

      this.loading_submit = true;
      await this.$http
        .post(`/${this.resource}`, this.form)
        .then((response) => {
          if (response.data.success) {
            // this.$message.success(response.data.message)
            this.resetForm();
            this.recordId = response.data.data.id;
            this.showDialogOptions = true;
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

    async requestProcessPrices(payload) {
      await this.$http
        .post(`/price-list`, payload)
        .then((response) => {
          if (response.data.success) {
            this.$message.success(response.data.message);
          } else {
            this.$message.error("Sucedió un error.");
          }
        })
        .catch((error) => {
          this.$message.error("Sucedió un error.");
        })
        .then(() => {});
    },
    clickProccessLocal() {
      const list_type_id = "01";

      const payload = {
        items: this.getItemPurchaseLocal(list_type_id),
      };

      this.requestProcessPrices(payload);
    },
    async clickProccessExterior() {
      if (this.form.items.length > 0) {
        if (
          !this.form_process_price.list_type_id ||
          !this.form_process_price.factor ||
          !this.form_process_price.currency_type_id
        ) {
          return;
        }

        const payload = {
          items: this.getItemPurchaseExterior(),
        };
        await this.requestProcessPrices(payload);

        this.form_process_price = {
          list_type_id: null,
          factor: null,
          currency_type_id: null,
        };
      } else {
        this.$message.error("Debe agregar productos o servicios.");
        this.form_process_price = {
          list_type_id: null,
          factor: null,
          currency_type_id: null,
        };
      }
    },
    getItemPurchaseLocal(list_type_id) {
      return this.form.items.map((row) => {
        return {
          item_id: row.item_id,
          list_type_id: list_type_id,
          price_fob: this.getPriceFob(row),
          factor: row.sale_profit_factor,
          price_list: row.retail_price,
          discount_one: row.discount_one,
          discount_two: row.discount_two,
          discount_three: row.discount_three,
        };
      });
    },
    getPriceFob(row) {
      const type = this.form.warehouse_income_reason_id;

      if (type == "104") {
        return row.unit_value;
      } else if (type == "103") {
        return row.price_fob_alm;
      }
    },
    getItemPurchaseExterior() {
      const list_type_id = this.form_process_price.list_type_id;
      const factor = this.form_process_price.factor;
      const currency_type_id = this.form_process_price.currency_type_id;

      return this.form.items.map((row) => {
        const { price_fob, price_list } = this.calculateValuesExterior(
          row,
          factor
        );

        return {
          item_id: row.item_id,
          list_type_id: list_type_id,
          price_fob: price_fob,
          factor: factor,
          price_list: price_list,
          currency_type_id: currency_type_id,
          discount_one: row.discount_one,
          discount_two: row.discount_two,
          discount_three: row.discount_three,
        };
      });
    },
    calculateValuesExterior(row, factor) {
      const type = this.form.warehouse_income_reason_id;

      if (type == "104") {
        //local
        const price_fob = row.unit_value;
        //const price_fob_alm_igv = price_fob * 1.18
        const price_list = row.unit_value * factor;

        return { price_fob, price_list };
      } else if (type == "103") {
        //exterior
        const price_fob = row.unit_value * row.warehouse_factor;
        //const price_fob_alm_igv = price_fob * 1.18
        const price_list = price_fob * factor;

        return { price_fob, price_list };
      }
    },
    clickEdit(data) {
      this.idItems = data.item_id;
      this.editCreate = true;
      this.showDialogAddItem = true;
      if (this.id) {
        this.quantity = data.quantity;
      } else {
        this.quantity = data.pending_quantity_income;
      }
      this.descuento1 = data.discount_one;
      this.descuento2 = data.discount_two;
      this.descuento3 = data.discount_three;
      this.factorAlmacen = data.warehouse_factor;
      this.factorVenta = data.sale_profit_factor;

      this.list_price = data.list_price;
    },
  },
};
</script>
