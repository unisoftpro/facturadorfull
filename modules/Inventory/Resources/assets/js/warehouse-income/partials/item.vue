<template>
  <el-dialog
    :title="titleDialog"
    :visible="showDialog"
    @open="create"
    @close="close"
  >
    <form autocomplete="off" @submit.prevent="clickAddItem">
      <div class="form-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group" :class="{ 'has-danger': errors.item_id }">
              <label class="control-label"> Producto </label>
              <el-select v-model="form.item_id" @change="changeItem" filterable>
                <el-option
                  v-for="option in items"
                  :key="option.id"
                  :value="option.id"
                  :label="option.full_description"
                ></el-option>
              </el-select>
              <small
                class="form-control-feedback"
                v-if="errors.item_id"
                v-text="errors.item_id[0]"
              ></small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group" :class="{ 'has-danger': errors.quantity }">
              <label class="control-label">Cantidad</label>
              <el-input-number
                v-model="form.quantity"
                :min="0.01"
              ></el-input-number>
              <small
                class="form-control-feedback"
                v-if="errors.quantity"
                v-text="errors.quantity[0]"
              ></small>
            </div>
          </div>
          <template v-if="this.warehouseIncomeReasonId == 104">
            <div class="col-md-3 center-el-checkbox" v-show="show_has_igv">
              <div class="form-group">
                <el-checkbox @change="handleChange($event)"
                  >Incluye Igv </el-checkbox
                ><br />
              </div>
            </div>
          </template>

          <div class="col-md-3">
            <div
              class="form-group"
              :class="{ 'has-danger': errors.list_price }"
            >
              <label class="control-label">Precio Lista</label>
              <el-input v-model="form.list_price" @input="inputListPrice">
                <template
                  slot="prepend"
                  v-if="form.item.currency_type_symbol"
                  >{{ form.item.currency_type_symbol }}</template
                >
              </el-input>
              <small
                class="form-control-feedback"
                v-if="errors.list_price"
                v-text="errors.list_price[0]"
              ></small>
            </div>
          </div>

          <template v-if="enabled_calc_prices">
            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.discount_one }"
              >
                <label class="control-label">Descuento % 1</label>
                <el-input-number
                  v-model="form.discount_one"
                  @change="inputListPrice"
                  :min="0"
                ></el-input-number>
                <small
                  class="form-control-feedback"
                  v-if="errors.discount_one"
                  v-text="errors.discount_one[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.discount_two }"
              >
                <label class="control-label">Descuento % 2</label>
                <el-input-number
                  v-model="form.discount_two"
                  @change="inputListPrice"
                  :min="0"
                ></el-input-number>
                <small
                  class="form-control-feedback"
                  v-if="errors.discount_two"
                  v-text="errors.discount_two[0]"
                ></small>
              </div>
            </div>
            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.discount_three }"
              >
                <label class="control-label">Descuento % 3</label>
                <el-input-number
                  v-model="form.discount_three"
                  @change="inputListPrice"
                  :min="0"
                ></el-input-number>
                <small
                  class="form-control-feedback"
                  v-if="errors.discount_three"
                  v-text="errors.discount_three[0]"
                ></small>
              </div>
            </div>
            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.discount_four }"
              >
                <label class="control-label">Descuento % 4</label>
                <el-input-number
                  v-model="form.discount_four"
                  @change="inputListPrice"
                  :min="0"
                ></el-input-number>
                <small
                  class="form-control-feedback"
                  v-if="errors.discount_four"
                  v-text="errors.discount_four[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.unit_value }"
              >
                <label class="control-label">Costo sin IGV</label>
                <el-input readonly v-model="form.unit_value">
                  <template
                    slot="prepend"
                    v-if="form.item.currency_type_symbol"
                    >{{ form.item.currency_type_symbol }}</template
                  >
                </el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.unit_value"
                  v-text="errors.unit_value[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.unit_price }"
              >
                <label class="control-label">Costo con IGV</label>
                <el-input readonly v-model="form.unit_price">
                  <template
                    slot="prepend"
                    v-if="form.item.currency_type_symbol"
                    >{{ form.item.currency_type_symbol }}</template
                  >
                </el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.unit_price"
                  v-text="errors.unit_price[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <!-- manual -->
              <div
                class="form-group"
                :class="{ 'has-danger': errors.warehouse_factor }"
              >
                <label class="control-label">Factor Almacén</label>
                <el-input-number
                  v-model="form.warehouse_factor"
                  :min="0"
                  @change="changeWarehouseFactor"
                ></el-input-number>
                <small
                  class="form-control-feedback"
                  v-if="errors.warehouse_factor"
                  v-text="errors.warehouse_factor[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <!-- manual -->
              <div
                class="form-group"
                :class="{ 'has-danger': errors.sale_profit_factor }"
              >
                <label class="control-label">Factor Venta-Ganancia</label>
                <el-input-number
                  v-model="form.sale_profit_factor"
                  :min="0"
                  @change="inputSaleProfitFactor"
                ></el-input-number>
                <small
                  class="form-control-feedback"
                  v-if="errors.sale_profit_factor"
                  v-text="errors.sale_profit_factor[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.last_purchase_price }"
              >
                <label class="control-label">Ult. Prec. Compra</label>
                <el-input v-model="form.last_purchase_price" readonly>
                  <template
                    slot="prepend"
                    v-if="form.item.currency_type_symbol"
                    >{{ form.item.currency_type_symbol }}</template
                  >
                </el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.last_purchase_price"
                  v-text="errors.last_purchase_price[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.last_factor }"
              >
                <label class="control-label">Ult. Factor</label>
                <el-input v-model="form.last_factor" readonly> </el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.last_factor"
                  v-text="errors.last_factor[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.price_fob_alm }"
              >
                <label class="control-label">P. FOB/Alm</label>
                <el-input v-model="form.price_fob_alm" readonly>
                  <template
                    slot="prepend"
                    v-if="form.item.currency_type_symbol"
                    >{{ form.item.currency_type_symbol }}</template
                  >
                </el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.price_fob_alm"
                  v-text="errors.price_fob_alm[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.price_fob_alm_igv }"
              >
                <label class="control-label">P. FOB/Alm+IGV</label>
                <el-input v-model="form.price_fob_alm_igv" readonly>
                  <template
                    slot="prepend"
                    v-if="form.item.currency_type_symbol"
                    >{{ form.item.currency_type_symbol }}</template
                  >
                </el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.price_fob_alm_igv"
                  v-text="errors.price_fob_alm_igv[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.retail_price }"
              >
                <label class="control-label">Precio Venta Público</label>
                <el-input v-model="form.retail_price" readonly>
                  <template
                    slot="prepend"
                    v-if="form.item.currency_type_symbol"
                    >{{ form.item.currency_type_symbol }}</template
                  >
                </el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.retail_price"
                  v-text="errors.retail_price[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.num_price }"
              >
                <label class="control-label">Prec. Vta. Num</label>
                <el-input v-model="form.num_price" readonly>
                  <template
                    slot="prepend"
                    v-if="form.item.currency_type_symbol"
                    >{{ form.item.currency_type_symbol }}</template
                  >
                </el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.num_price"
                  v-text="errors.num_price[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-3">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.letter_price }"
              >
                <label class="control-label">Prec. Vta. Letra</label>
                <el-input v-model="form.letter_price" readonly> </el-input>
                <small
                  class="form-control-feedback"
                  v-if="errors.letter_price"
                  v-text="errors.letter_price[0]"
                ></small>
              </div>
            </div>
          </template>
        </div>
      </div>
      <div class="form-actions text-right pt-2">
        <el-button @click.prevent="close()">Cerrar</el-button>
        <el-button
          type="primary"
          native-type="submit"
          v-if="form.item_id && form.unit_value > 0"
          >Agregar</el-button
        >
      </div>
    </form>
  </el-dialog>
</template>
<style>
.el-select-dropdown {
  max-width: 80% !important;
  margin-right: 5% !important;
}
</style>
<script>
export default {
  props: [
    "showDialog",
    "currencyTypeIdActive",
    "exchangeRateSale",
    "purchaseOrderId",
    "warehouseIncomeReasonId",
    "idItems",
    "editCreate",
    "quantityEdit",
    "listPrice"

  ],
  data() {
    return {
      titleDialog: "Agregar Producto",
      resource: "warehouse-income",
      showDialogNewItem: false,
      errors: {},
      form: {},
      items: [],
      use_price: 1,
      letters: {},
      row: {},
      withIgv: false,
      show_has_igv: true,
      enabled_calc_prices: false,
    };
  },
  created() {
    this.initForm();
    this.initLetters();
    this.getTables();
  },
  methods: {
    getTables() {
      this.$http.get(`/${this.resource}/item/tables`).then((response) => {
        this.items = response.data.items;
      });
    },
    handleChange(event) {
      if (event) {
        this.withIgv = event;
        this.inputListPrice();
      } else {
        this.withIgv = event;
        this.inputListPrice();
      }
    },
    changeWarehouseFactor() {
      if (this.form.warehouse_factor > 0) {
        this.form.price_fob_alm = _.round(
          parseFloat(this.form.warehouse_factor) *
            parseFloat(this.form.unit_value),
          2
        );
        this.form.price_fob_alm_igv = _.round(
          this.form.price_fob_alm * 1.18,
          2
        );
      }
    },
    inputSaleProfitFactor() {
      if (this.warehouseIncomeReasonId == "104") {
        this.form.retail_price = _.round(
          parseFloat(this.form.unit_price) *
            parseFloat(this.form.sale_profit_factor),
          2
        );
        this.form.num_price = this.form.retail_price;
        let letter_price = this.form.num_price.toString();

        this.form.letter_price = "";

        for (let i = 0; i < letter_price.length; i++) {
          this.form.letter_price += this.letters[letter_price.charAt(i)];
        }
      } else if (this.form.sale_profit_factor > 0) {
        this.form.retail_price = _.round(
          parseFloat(this.form.sale_profit_factor) *
            parseFloat(this.form.price_fob_alm_igv),
          2
        );
        this.form.num_price = this.form.retail_price;

        let letter_price = this.form.num_price.toString();

        this.form.letter_price = "";

        for (let i = 0; i < letter_price.length; i++) {
          this.form.letter_price += this.letters[letter_price.charAt(i)];
        }
      }
    },
    inputListPrice() {
      if (this.warehouseIncomeReasonId == "104" && this.withIgv == false) {
        let variableconifv = 0;
        let variableunit = 0;
        variableconifv = _.round(
          parseFloat(this.form.list_price) -
            parseFloat(this.form.list_price) *
              (parseFloat(this.form.discount_one) / 100),
          6
        );
        variableconifv = _.round(
          variableconifv -
            variableconifv * (parseFloat(this.form.discount_two) / 100),
          6
        );
        variableconifv = _.round(
          variableconifv -
            variableconifv * (parseFloat(this.form.discount_three) / 100),
          6
        );
        variableconifv = _.round(
          variableconifv -
            variableconifv * (parseFloat(this.form.discount_four) / 100),
          6
        );
        let percentage_igv = 18;
        this.form.unit_price = _.round(variableconifv, 6);
        variableunit = _.round(
          this.form.unit_price * (percentage_igv / 100),
          6
        );

        this.form.unit_value = _.round(this.form.unit_price - variableunit, 6);
      } else {
        this.form.unit_value = _.round(
          parseFloat(this.form.list_price) -
            parseFloat(this.form.list_price) *
              (parseFloat(this.form.discount_one) / 100),
          6
        );

        this.form.unit_value = _.round(
          this.form.unit_value -
            this.form.unit_value * (parseFloat(this.form.discount_two) / 100),
          6
        );

        this.form.unit_value = _.round(
          this.form.unit_value -
            this.form.unit_value * (parseFloat(this.form.discount_three) / 100),
          6
        );

        this.form.unit_value = _.round(
          this.form.unit_value -
            this.form.unit_value * (parseFloat(this.form.discount_four) / 100),
          6
        );

        let percentage_igv = 18;
        this.form.unit_price = _.round(
          this.form.unit_value * (1 + percentage_igv / 100),
          6
        );

        this.inputSaleProfitFactor();
        this.changeWarehouseFactor();
      }
    },
    initForm() {
      this.errors = {};

      this.form = {
        item_id: null,
        item: {},
        category_id: null,
        family_id: null,
        quantity: 1,
        quantity2: 1,
        list_price: 0,
        discount_one: 0,
        discount_two: 0,
        discount_three: 0,
        discount_four: 0,
        unit_value: 0,
        unit_price: 0,
        sale_profit_factor: 0,
        retail_price: 0,
        price_fob_alm_igv: 0,
        price_fob_alm: 0,
        last_purchase_price: 0,
        warehouse_factor: 0,
        last_factor: 0,
        num_price: 0,
        letter_price: null,
        total_value: 0,
        total: 0,
      };
      this.show_has_igv = true;

      this.row = {};
    },
    initLetters() {
      this.letters = {
        1: "B",
        2: "X",
        3: "C",
        4: "Y",
        5: "Z",
        6: "Q",
        7: "E",
        8: "H",
        9: "G",
        0: "W",
        ".": ".",
      };
    },
    create() {
      this.initForm();
      this.verifyCalcPrices();
      this.editItem();

    },
    verifyCalcPrices() {
      this.enabled_calc_prices = ["103", "104"].includes(
        this.warehouseIncomeReasonId
      )
        ? true
        : false;
    },
    close() {
      this.initForm();
      this.$emit("update:showDialog", false);
    },
    async changeItem() {
      this.form.item = await _.find(this.items, { id: this.form.item_id });
      this.form.category_id = this.form.item.category_id;
      this.form.family_id = this.form.item.family_id;
      await this.getListPrice();
      await this.getLastPricePurchaseFactor();
    },
    getLastPricePurchaseFactor() {
      this.$http
        .get(`/${this.resource}/item/additional-values/${this.form.item_id}`)
        .then((response) => {
          this.form.last_purchase_price = response.data.last_purchase_price;
          this.form.last_factor = response.data.last_factor;
        });
    },
    getListPrice() {
      if (this.purchaseOrderId) {
        this.$http
          .get(
            `/${this.resource}/item/list-price/${this.form.item_id}/${this.purchaseOrderId}`
          )
          .then((response) => {
            this.form.list_price = response.data.list_price;
            this.inputListPrice();
          });
      }
    },
    async clickAddItem() {
      await this.calculateRowItem(
        null,
        this.currencyTypeIdActive,
        this.exchangeRateSale
      );

      this.form.quantity2 = this.form.quantity;
      this.form.pending_quantity_income = this.form.quantity;
      console.log(this.form);
      await this.$emit("add", this.form);
      await this.initForm();
    },
    calculateRowItem(row = null, currency_type_id_new, exchange_rate_sale) {
      if (row) this.form = row;

      let currency_type_id_old = this.form.item.currency_type_id;

      if (
        currency_type_id_old === "PEN" &&
        currency_type_id_old !== currency_type_id_new
      ) {
        this.form.list_price = _.round(
          parseFloat(this.form.list_price) / exchange_rate_sale,
          2
        );
      } else {
        this.form.list_price = _.round(
          parseFloat(this.form.list_price) * exchange_rate_sale,
          2
        );
      }

      this.inputListPrice();

      this.form.total_value = _.round(
        this.form.unit_value * this.form.quantity,
        2
      );
      this.form.total = _.round(this.form.unit_price * this.form.quantity, 2);

      return this.form;
    },
    async editItem() {
      if (this.editCreate) {

          this.form.item_id=this.idItems;
          this.form.list_price= this.listPrice;
          this.form.quantity = this.quantityEdit;
          await this.changeItem();
          this.inputListPrice();
      }
    },
  },
};
</script>
