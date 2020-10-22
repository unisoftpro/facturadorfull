<template>
  <div>
    <div class="row">
      <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="row mt-2">
          <div class="col-md-4">
            <label class="control-label">Almacenes</label>
            <el-select v-model="form.warehouse_id" filterable>
              <el-option
                v-for="option in warehouse"
                :key="option.id"
                :value="option.id"
                :label="option.description"
              ></el-option>
            </el-select>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label">Familia</label>
              <el-select v-model="form.families_id" filterable>
                <el-option
                  v-for="option in families"
                  :key="option.id"
                  :value="option.id"
                  :label="option.name"
                ></el-option>
              </el-select>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label">Linea</label>
              <el-select v-model="form.lines_id" filterable>
                <el-option
                  v-for="option in lines"
                  :key="option.id"
                  :value="option.id"
                  :label="option.name"
                ></el-option>
              </el-select>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label">Moneda</label>
              <el-select v-model="form.currencytype_id" filterable>
                <el-option
                  v-for="option in currencytype"
                  :key="option.id"
                  :value="option.id"
                  :label="option.description"
                ></el-option>
              </el-select>
            </div>
          </div>
          <div
            class="col-lg-7 col-md-7 col-md-7 col-sm-12"
            style="margin-top: 29px"
          >
            <el-button
              class="submit"
              type="primary"
              @click.prevent="getRecordsByFilter"
              :loading="loading_submit"
              icon="el-icon-search"
              >Buscar</el-button
            >
            <template v-if="records.length > 0">
              <el-button
                class="submit"
                type="danger"
                @click.prevent="clickDownload('pdf')"
                ><i class="fa fa-file-pdf"></i> Exporta Pdf</el-button
              >
            </template>
          </div>
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <slot name="heading"></slot>
                </thead>
                <tbody>
                  <slot
                    v-for="(row, index) in records"
                    :row="row"
                    :index="customIndex(index)"
                  ></slot>
                </tbody>
              </table>
              <div>
                <el-pagination
                  @current-change="getRecords"
                  layout="total, prev, pager, next"
                  :total="pagination.total"
                  :current-page.sync="pagination.current_page"
                  :page-size="pagination.per_page"
                >
                </el-pagination>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
import queryString from "query-string";
export default {
  props: {
    resource: String,
  },
  data() {
    return {
      title: null,
      type_list_prices: [],
      families: [],
      brands: [],
      lines: [],
      records: [],
      pagination: {},
      warehouse: [],
      currencytype: [],
      form: {},
      loading_submit: false,
    };
  },
  created() {
    this.initForm();
    this.getTables();
  },
  methods: {
    async getTables() {
      await this.$http.get(`/${this.resource}/tables`).then((response) => {
        this.families = response.data.families;
        this.lines = response.data.lines;
        this.warehouse = response.data.warehouse;
        this.currencytype = response.data.cat_currency_types;
      });
    },
    initForm() {
      this.errors = {};

      this.form = {
        families_id: null,
        lines_id: null,
        warehouse_id: null,
        currencytype_id: null,
      };
    },

    async getRecordsByFilter() {
      if (!this.form.warehouse_id) {
        return this.$message.error("El tipo de lista de precio es obligatorio");
      }

      this.loading_submit = await true;
      this.$eventHub.$emit("emitItemID", this.form.list_type_id);
      await this.getRecords();
      //this.loading_submit = await false;
    },
    customIndex(index) {
      return (
        this.pagination.per_page * (this.pagination.current_page - 1) +
        index +
        1
      );
    },
    getRecords() {
      return this.$http
        .get(`/${this.resource}/records?${this.getQueryParameters()}`)
        .then((response) => {
          this.records = response.data.data;
          this.pagination = response.data.meta;
          this.pagination.per_page = parseInt(response.data.meta.per_page);
          this.loading_submit = false;
          // this.initTotals()
        });
    },
    getQueryParameters() {
      return queryString.stringify({
        page: this.pagination.current_page,
        limit: this.limit,
        ...this.form,
      });
    },
    clickDownload(type) {
      let query = queryString.stringify({
        ...this.form,
      });
      window.open(`/${this.resource}/${type}/?${query}`, "_blank");
    },
  },
};
</script>
