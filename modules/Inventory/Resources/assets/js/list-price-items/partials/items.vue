<template>
  <el-dialog
    :title="titleDialog"
    :visible="showDialog"
    @open="create"
    width="40%"
    :close-on-click-modal="false"
    :close-on-press-escape="false"
    :show-close="false"
  >
    <div class="row">
      <div class="col-md-5">
        <div class="form-group">
          <label class="control-label">Cantidad</label>
          <el-input v-model="item.item_code" disabled></el-input>
        </div>
      </div>
      <div class="col-md-7">
        <div class="form-group">
          <label class="control-label">Descripción</label>
          <el-input v-model="item.description" disabled></el-input>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label class="control-label">Precio F.O.B</label>
          <el-input v-model="item.description" disabled></el-input>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label class="control-label">Moneda</label>
          <el-input v-model="item.symbol" disabled></el-input>
        </div>
      </div>
    </div>
    <hr>
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
      this.item ={
          id:null,
          internal_id:null,
          item_code:null,
          description:null,
          symbol:null
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


          //this.form = response.data.data
          //let typei = this.type == 'edit' ? 'editada' : 'registrada'
          this.titleDialog = 'Lista de Precios';
        });
      //this.button_text = this.isUpdate ? 'Continuar':'Nueva OC'
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
