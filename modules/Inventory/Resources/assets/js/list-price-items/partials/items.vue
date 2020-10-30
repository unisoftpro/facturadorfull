<template>
  <el-dialog
    :title="titleDialog"
    :visible="showDialog"
    @open="create"
    width="50%"
    :close-on-click-modal="false"
    :close-on-press-escape="false"
    :show-close="false"
  >
    <div class="row">
      <div class="col-md-6">
        <div class="form-group" :class="{ 'has-danger': errors.item_id }">
          <label class="control-label"> Producto </label>
          <el-select
            v-model="form.item_id"
            @change="changeItem"
            :disabled="isDisabled"
            filterable
          >
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

      <div class="col-md-2">
        <div class="form-group" :class="{ 'has-danger': errors.currency_id }">
          <label class="control-label"> Moneda </label>
          <el-select
            v-model="form.currency_id"
            :disabled="isDisabled"
            filterable
          >
            <el-option
              v-for="option in currencytype"
              :key="option.id"
              :value="option.id"
              :label="option.symbol"
            ></el-option>
          </el-select>
          <small
            class="form-control-feedback"
            v-if="errors.currency_id"
            v-text="errors.currency_id[0]"
          ></small>
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <label class="control-label">Precio F.O.B</label>
          <el-input
            v-model="formListPrice.price_list_fob"
            @change="inputListPrice"
            :disabled="isDisabled"
          ></el-input>
        </div>
      </div>
      <div class="col-md-10">
        <div class="form-group">
          <label class="control-label">Descripción</label>
          <el-input
            v-model="item.description"
            :disabled="isDisabled"
          ></el-input>
        </div>
      </div>
    </div>
    <hr />

    <div class="row">
      <div class="col-md-2">
        <div class="form-group" :class="{ 'has-danger': errors.currency_id }">
          <label class="control-label"> Moneda </label>
          <el-select
            v-model="item.currency_id"
            :disabled="isDisabled"
            filterable
            @change="changeItem"
          >
            <el-option
              v-for="option in currencytype"
              :key="option.id"
              :value="option.id"
              :label="option.symbol"
            ></el-option>
          </el-select>
          <small
            class="form-control-feedback"
            v-if="errors.currency_id"
            v-text="errors.currency_id[0]"
          ></small>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group" :class="{ 'has-danger': errors.list_type_id }">
          <label class="control-label"> Tipo de Lista </label>
          <el-select
            v-model="item.list_type_id"
            :disabled="isDisabled"
            filterable
            @change="changeItem"
          >
            <el-option
              v-for="option in listType"
              :key="option.id"
              :value="option.id"
              :label="option.description"
            ></el-option>
          </el-select>
          <small
            class="form-control-feedback"
            v-if="errors.list_type_id"
            v-text="errors.list_type_id[0]"
          ></small>
        </div>
      </div>
      <template v-if="showCreate">
        <div class="col-md-3">
          <div class="form-group" :class="{ 'has-danger': errors.factor }">
            <label class="control-label">Factor % </label>
            <el-input-number
              v-model="formListPrice.factor"
              @change="inputListPrice"
              :min="0"
            ></el-input-number>
            <small
              class="form-control-feedback"
              v-if="errors.factor"
              v-text="errors.factor[0]"
            ></small>
          </div>
        </div>
      </template>
    </div>
    <template v-if="showCreate">
      <div class="row">
        <div class="col-md-3">
          <div
            class="form-group"
            :class="{ 'has-danger': errors.discount_one }"
          >
            <label class="control-label">Descuento % 1</label>
            <el-input-number
              v-model="formListPrice.discount_one"
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
              v-model="formListPrice.discount_two"
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
              v-model="formListPrice.discount_three"
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
          <div class="form-group" :class="{ 'has-danger': errors.price_list }">
            <label class="control-label">Lista Precio sin IGV</label>
            <el-input readonly v-model="formListPrice.price_list"> </el-input>
            <small
              class="form-control-feedback"
              v-if="errors.price_list"
              v-text="errors.price_list[0]"
            ></small>
          </div>
        </div>
        <div class="col-md-3">
          <div
            class="form-group"
            :class="{ 'has-danger': errors.price_list_igv }"
          >
            <label class="control-label">Lista Precio con IGV</label>
            <el-input readonly v-model="formListPrice.price_list_igv">
            </el-input>
            <small
              class="form-control-feedback"
              v-if="errors.price_list_igv"
              v-text="errors.price_list_igv[0]"
            ></small>
          </div>
        </div>
        <template v-if="idShow">
        <div class="col-md-3">
          <div class="form-group" :class="{ 'has-danger': errors.id_list }">
            <label class="control-label">id</label>
            <el-input readonly  v-model="formListPrice.id_list"> </el-input>
            <small
              class="form-control-feedback"
              v-if="errors.id_list"
              v-text="errors.id_list[0]"
            ></small>
          </div>
        </div>
        </template>

        <template :visible="showBtnGuardarAgregar">
          <div class="col-md-2">
            <div class="right-wrapper pull-right">
              <button
                type="button"
                class="btn btn-custom btn-sm mt-4 mr-2"
                @click="save()"
              >
                {{ button_text }}
              </button>
            </div>
          </div>
        </template>
      </div>
    </template>

    <div class="row mt-2" v-if="listpriceitems.length > 0">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Precio Lista</th>
                <th>Descuento 1</th>
                <th class="text-center">Descuento 2</th>
                <th class="text-right">Descruento 3</th>
                <th class="text-right">Factor</th>
                <template v-if="showCreate">
                  <th class="text-right">Opciones</th>
                </template>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, index) in listpriceitems" :key="index">
                <td>{{ index + 1 }}</td>
                <td class="text-center">
                  {{ row.currency_type_id }} {{ row.price_list }}
                </td>
                <td class="text-right">{{ row.discount_one }}</td>
                <td class="text-right">
                  {{ row.discount_two }}
                </td>
                <td class="text-right">
                  {{ row.discount_three }}
                </td>
                <td class="text-right">
                  {{ row.factor }}
                </td>
                <template v-if="showCreate">
                  <td class="text-right">
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
                </template>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <span slot="footer" class="dialog-footer">
      <template v-if="showClose">
        <el-button @click="clickClose">Cerrar</el-button>
      </template>
      <template v-else>
        <el-button @click="clickFinalize">Ir al listado</el-button>
        <el-button type="primary" @click="clickNewDocument">{{
          button_text
        }}</el-button>
      </template>
    </span>
  </el-dialog>
</template>

<script>
import { deletable } from "@mixins/deletable";
import moment from "moment";
import queryString from "query-string";
export default {
  props: [
    "showDialog",
    "recordId",
    "showClose",
    "type",
    "isUpdate",
    "currencyTypeId",
    "listTypeId",
    "currencyTypeDescription",
    "currencyDescription",
    "showCreate",
    "disableCombo",
  ],
  mixins: [deletable],
  data() {
    return {
      titleDialog: null,
      loading: false,
      resource: "precielist",
      button_text: "Guardar",
      errors: {},
      itemss: {},
      form: {},
      item: [],
      items: [],
      listpriceitems: [],
      factorlist: null,
      formListPrice: {},
      currencytype: [],
      listType: [],
      showBtnGuardarAgregar: true,
      idShow:false
    };
  },
  computed: {
    isDisabled() {
      return this.disableCombo;
    },
  },
  async created() {
    this.titleDialog = "Lista de Precios";
    this.initForm();
    await this.$http.get(`/${this.resource}/combo`).then((response) => {
      this.items = response.data.items2;
      this.currencytype = response.data.currency;
      this.listType = response.data.listType;
    });
    //await this.getRecord();
  },
  methods: {
    create() {
      this.initForm();
      this.form.item_id = this.recordId;
      this.item.currency_id = this.currencyTypeId;
      this.item.list_type_id = this.listTypeId;
      this.changeItem();
    },
    initForm() {
      this.errors = {};
      this.form = {
        id: null,
        external_id: null,
        number: null,
        customer_email: null,
        upload_filename: null,
        download_pdf: null,
        item_id: null,
        currency_id: null,
        previous_cost: null,
        item: [],
      };
      this.item = {
        id: null,
        internal_id: null,
        item_code: null,
        description: null,
        symbol: null,
        sale_unit_price: null,
        currency_id: null,
        list_type_id: null,
      };
      this.formListPrice = {
        factor: null,
        price_list: null,
        discount_one: null,
        discount_two: null,
        discount_three: null,
        price_list_fob: null,
        price_list_igv: null,
        id_list: null,
      };
      this.itemss = {
        id: null,
        item_id: null,
        list_type_id: null,
        price_fob: null,
        factor: null,
        price_list: null,
        discount_one: null,
        discount_two: null,
        discount_three: null,
        currency_type_id: null,
      };
    },
    save() {
      this.itemss.id = this.formListPrice.id_list;
      this.itemss.item_id = this.form.item_id;
      this.itemss.list_type_id = this.item.list_type_id;
      this.itemss.price_fob = this.formListPrice.price_list_fob;
      this.itemss.factor = this.formListPrice.factor;
      this.itemss.price_list = this.formListPrice.price_list;
      this.itemss.discount_one = this.formListPrice.discount_one;
      this.itemss.discount_two = this.formListPrice.discount_two;
      this.itemss.discount_three = this.formListPrice.discount_three;
      this.itemss.currency_type_id = this.item.currency_id;
      this.form.item.push(this.itemss);
      const records = {
        items: this.form.item,
      };

      this.requestProcessPrices(records);
      this.getRecord();
      //this.initForm();
    },
    async requestProcessPrices(payload) {
      await this.$http
        .post(`/price-list`, payload)
        .then((response) => {
          if (response.data.success) {
            this.$message.success(response.data.message);
            this.formListPrice.factor = null;
            this.formListPrice.discount_one=null;
            this.formListPrice.discount_two = null;
            this.formListPrice.discount_three = null;
            this.formListPrice.id_list = null;
            this.formListPrice.price_list_igv = null;
            this.formListPrice.price_list = null;
          } else {
            this.$message.error("Sucedió un error.");
          }
        })
        .catch((error) => {
          this.$message.error("Sucedió un error.");
        })
        .then(() => {});
    },
    changeItem() {
      this.listpriceitems = [];
      if (this.form.item_id) {
        var items = _.find(this.items, { id: this.form.item_id });
        this.item.description = items.description;
        this.getRecord();
      }
    },

    getRecord() {
      this.$eventHub.$emit("emitItemID", this.form.item_id);
      this.$http
        .get(
          `/${this.resource}/record/${this.form.item_id}/${this.item.currency_id}/${this.item.list_type_id}`
        )
        .then((response) => {
          this.listpriceitems = response.data.listpriceitems;
          this.form.currency_id =
            response.data.previous_cost.previous_currency_type_id;
          this.formListPrice.price_list_fob =
            response.data.previous_cost.previous_cost;
        });
    },
    inputListPrice() {
      let variableunit = 0;

      this.formListPrice.price_list = _.round(
        this.formListPrice.price_list_fob *
          parseFloat(this.formListPrice.factor),
        6
      );/*
      this.formListPrice.price_list = _.round(
        parseFloat(this.formListPrice.price_list) -
          parseFloat(this.formListPrice.price_list) *
            (parseFloat(this.formListPrice.discount_one) / 100),
        6
      );
      this.formListPrice.price_list = _.round(
        parseFloat(this.formListPrice.price_list) -
          parseFloat(this.formListPrice.price_list) *
            (parseFloat(this.formListPrice.discount_two) / 100),
        6
      );
      this.formListPrice.price_list = _.round(
        parseFloat(this.formListPrice.price_list) -
          parseFloat(this.formListPrice.price_list) *
            (parseFloat(this.formListPrice.discount_three) / 100),
        6
      );*/
      let percentage_igv = 18;
      this.formListPrice.price_list_igv = _.round(
        this.formListPrice.price_list * (1 + percentage_igv / 100),
        6
      );
    },
    handleCurrentChange(val) {
      this.formListPrice.id_list = val.id;
      this.formListPrice.factor = val.factor;
      this.formListPrice.price_list = val.price_list;
      this.formListPrice.discount_one = val.discount_one;
      this.formListPrice.discount_two = val.discount_two;
      this.formListPrice.discount_three = val.discount_three;
      let percentage_igv = 18;
      this.formListPrice.price_list_igv = _.round(
        this.formListPrice.price_list * (1 + percentage_igv / 100),
        6
      );
    },

    clickFinalize() {
      location.href = `/${this.resource}`;
    },
    clickNewDocument() {
      this.clickClose();
    },
    clickClose() {
      this.$emit("update:showDialog", false);
      this.initForm();
    },
    clickDelete(id) {
      this.destroy(`/${this.resource}/${id}`).then(() =>
        this.$eventHub.$emit("reloadData")
      );
    },
  },
};
</script>
