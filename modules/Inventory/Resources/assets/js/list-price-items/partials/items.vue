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
      <div class="col-md-2">
        <div class="form-group">
          <label class="control-label">Codigo</label>
          <el-input v-model="item.item_code" disabled></el-input>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="control-label">Descripción</label>
          <el-input v-model="item.description" disabled></el-input>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label class="control-label">Precio F.O.B</label>
          <el-input v-model="item.sale_unit_price" disabled></el-input>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label class="control-label">Moneda</label>
          <el-input v-model="item.symbol" disabled></el-input>
        </div>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-md-5">
        <div class="form-group">
          <label class="control-label">Tipo de Lista</label>
          <el-input v-model="this.currencyTypeDescription" disabled></el-input>
        </div>
      </div>
      <div class="col-md-5">
        <div class="form-group">
          <label class="control-label">Moneda</label>
          <el-input v-model="this.currencyDescription" disabled></el-input>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label class="control-label">Factor de Lista</label>
          <el-input v-model="this.formListPrice.factor" disabled></el-input>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label class="control-label">Pecio de Lista</label>
          <el-input v-model="this.formListPrice.price_list" disabled></el-input>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label class="control-label">1re Descuento</label>
          <el-input v-model="this.formListPrice.discount_one" disabled></el-input>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label class="control-label">2do Descuento</label>
          <el-input v-model="this.formListPrice.discount_two" disabled></el-input>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label class="control-label">3re Descuento</label>
          <el-input v-model="this.formListPrice.discount_three" disabled></el-input>
        </div>
      </div>
    </div>
    <div class="row" v-if="listpriceitems.length > 0">
      <div class="col-md-12 mt-4">
        <p><strong>Seleccione una fila (Lista de Precio)</strong></p>
        <el-table
          ref="singleTable"
          :data="listpriceitems"
          highlight-current-row
          border
          @current-change="handleCurrentChange"
          style="width: 100%"
        >
          <el-table-column
            property="price_list"
            label="Precio Lista"
            width="120"
          >
          </el-table-column>
          <el-table-column
            property="discount_one"
            label="Descuento 1"
            width="120"
          >
          </el-table-column>

          <el-table-column
            property="discount_two"
            label="Descuento 2"
            width="120"
          >
          </el-table-column>

          <el-table-column
            property="discount_three"
            label="Descuento 3"
            width="120"
          >
          </el-table-column>

          <el-table-column property="factor" label="Factor" width="100">
          </el-table-column>
        </el-table>
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
  ],
  data() {
    return {
      titleDialog: null,
      loading: false,
      resource: "precielist",
      button_text: "Nueva OC",
      errors: {},
      form: {},
      item: [],
      listpriceitems: [],
      factorlist:null,
      formListPrice:{},
    };
  },
  created() {
    this.initForm();
  },
  methods: {
    clickPrint(format) {
      window.open(
        `/${this.resource}/print/${this.form.external_id}/${format}`,
        "_blank"
      );
    },
    clickDownload() {
      window.open(
        `/${this.resource}/download-attached/${this.form.external_id}`,
        "_blank"
      );
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
      };
      this.item = {
        id: null,
        internal_id: null,
        item_code: null,
        description: null,
        symbol: null,
        sale_unit_price: null,
      };
      this.formListPrice ={
          factor:null,
          price_list:null,
          discount_one:null,
          discount_two:null,
          discount_three:null
      }
    },
    create() {
      this.$http
        .get(
          `/${this.resource}/record/${this.recordId}/${this.currencyTypeId}/${this.listTypeId}`
        )
        .then((response) => {
          this.item.item_code = response.data.items[0].item_code;
          this.item.symbol = response.data.items[0].currency_type.symbol;
          this.item.description = response.data.items[0].description;
          this.listpriceitems = response.data.listpriceitems;
          this.item.sale_unit_price = response.data.items[0].sale_unit_price;

          //this.form = response.data.data
          //let typei = this.type == 'edit' ? 'editada' : 'registrada'
          this.titleDialog = "Lista de Precios";
        });
      //this.button_text = this.isUpdate ? 'Continuar':'Nueva OC'
    },
    handleCurrentChange(val) {

      this.formListPrice.factor = val.factor;
      this.formListPrice.price_list=val.price_list;
      this.formListPrice.discount_one = val.discount_one;
      this.formListPrice.discount_two = val.discount_two;
      this.formListPrice.discount_three = val.discount_three;

      //this.currentRow = val;
      //this.form.purchase_order = val;
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
  },
};
</script>
