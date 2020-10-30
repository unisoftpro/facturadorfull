<template>
  <el-dialog
    :title="titleDialog"
    :visible="showDialog"
    @open="create"
    width="30%"
    :close-on-click-modal="false"
    :close-on-press-escape="false"
    :show-close="false"
  >
    <div class="row">
      <div class="col-md-2">
        <div class="form-group" :class="{ 'has-danger': errors.id }">
          <div class="form-group">
            <label class="control-label">Id</label>
            <el-input v-model="typelist.id" :disabled="isDisabled"></el-input>
            <small
              class="form-control-feedback"
              v-if="errors.id"
              v-text="errors.item_id[0]"
            ></small>
          </div>
        </div>
      </div>
      <div class="col-md-10">
        <div class="form-group" :class="{ 'has-danger': errors.description }">
          <div class="form-group">
            <label class="control-label">Descripción</label>
            <el-input v-model="typelist.description"></el-input>
          </div>
          <small
            class="form-control-feedback"
            v-if="errors.id"
            v-text="errors.description[0]"
          ></small>
        </div>
      </div>
    </div>
    <span slot="footer" class="dialog-footer">
      <el-button
        @click="clickSave"
        type="button"
        class="btn waves-effect waves-light btn-xs btn-primary"
        >Guardar</el-button
      >
      <el-button @click="clickClose">Cerrar</el-button>
    </span>
  </el-dialog>
</template>

<script>
import moment from "moment";
import queryString from "query-string";
export default {
  props: ["showDialog", "typeListId", "typeListDescription", "editOrCreate"],
  data() {
    return {
      titleDialog: null,
      typelist: {},
      resource: "typepricelist",
      errors: {},
    };
  },
  computed: {
    isDisabled() {
      return this.editOrCreate;
    },
  },
  async created() {
    this.titleDialog = "Tipo de Lista Precio";
    this.initForm();
  },
  methods: {
    create() {
      if (this.editOrCreate) {
        this.typelist.id = this.typeListId;
      } else {
        this.getTypeList();
      }

      this.typelist.description = this.typeListDescription;
    },
    initForm() {
      this.errors = {};
      this.typelist = {
        id: null,
        description: null,
      };
    },
    clickClose() {
      this.$emit("update:showDialog", false);
      this.initForm();
    },
    async clickSave() {
      await this.$http
        .post(`/${this.resource}/store`, this.typelist)
        .then((response) => {
          if (response.data.success) {
            this.$message.success(response.data.message);
            this.initForm();
            this.$eventHub.$emit("reloadData");
            this.clickClose();
            location.reload();
          } else {
            this.$message.error("Sucedió un error.");
          }
        })
        .catch((error) => {
          this.$message.error("Sucedió un error.");
        })
        .then(() => {});
    },
    getTypeList() {
      this.$http.get(`/${this.resource}/getTypeList`).then((response) => {
        this.typelist.id = response.data.id;
      });
    },
  },
};
</script>
