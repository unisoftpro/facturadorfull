<template>
  <div v-loading="loading_submit">
    <div class="row">
      <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="row" v-if="applyFilter">
          <template v-if="noShow">
            <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
              <div class="d-flex">
                <div style="width: 100px">Filtrar por:</div>
                <el-select
                  v-model="search.column"
                  placeholder="Select"
                  @change="changeClearInput"
                >
                  <el-option
                    v-for="(label, key) in columns"
                    :key="key"
                    :value="key"
                    :label="label"
                  ></el-option>
                </el-select>
              </div>
            </div>
          </template>
          <div class="col-lg-3 col-md-4 col-sm-12 pb-2">
            <div class="d-flex">
              <div style="width: 100px">Proveedor:</div>
              <el-select
                v-model="search.supplier_id"
                filterable
                remote
                popper-class="el-select-customers"
                clearable
                placeholder="Nombre o número de documento"
                @change="changeClearInput"
              >
                <el-option
                  v-for="option in suppliers"
                  :key="option.id"
                  :value="option.id"
                  :label="option.description"
                ></el-option>
              </el-select>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-12 pb-2">
            <div class="d-flex">
              <div style="width: 100px">Estados:</div>
              <el-select
                v-model="search.purchase_order_states_id"
                filterable
                remote
                popper-class="el-select-customers"
                clearable
                placeholder="Nombre o número de documento"
                @change="changeClearInput"
              >
                <el-option
                  v-for="option in purchase_order_states"
                  :key="option.id"
                  :value="option.id"
                  :label="option.description"
                ></el-option>
              </el-select>
            </div>
          </div>
          <div class="col-lg-2 col-md-4 col-sm-12 pb-2">
            <template>
              <el-date-picker
                v-model="search.date_of_issue"
                type="date"
                @change="changeClearInput"
                placeholder="Fecha inicio"
                value-format="yyyy-MM-dd"
                format="dd/MM/yyyy"
                :clearable="true"
              ></el-date-picker>
            </template>
          </div>
          <div class="col-lg-2 col-md-4 col-sm-12 pb-2">
            <template>
              <el-date-picker
                v-model="search.date_of_due"
                type="date"
                @change="changeClearInput"
                placeholder="Fecha fin"
                value-format="yyyy-MM-dd"
                :picker-options="pickerOptionsDates"
                format="dd/MM/yyyy"
                :clearable="true"
              ></el-date-picker>
            </template>
          </div>
          <div class="col-lg-2 col-md-4 col-sm-12 pb-2">
            <button class="btn btn-custom btn-sm mt-1 mr-2"  @click="clickDonwloadFilter()" >Exportar</button>
          </div>
        </div>
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
</template>


<script>
import moment from "moment";
import queryString from "query-string";

export default {
  props: {
    resource: String,
    applyFilter: {
      type: Boolean,
      default: true,
      required: false,
    },
  },
  data() {
    return {
      search: {
        column: null,
        value: null,
        supplier_id: null,
        date_of_issue: null,
        date_of_due: null,
        purchase_order_states_id: null,
      },
      noShow: false,
      form: {},
      suppliers: [],
      purchase_order_states: [],
      columns: [],
      records: [],
      pagination: {},
      loading_submit: false,
      pickerOptionsDates: {
        disabledDate: (time) => {
          time = moment(time).format("YYYY-MM-DD");
          return this.form.date_start > time;
        },
      },
    };
  },
  computed: {},
  created() {
    this.$eventHub.$on("reloadData", () => {
      this.getRecords();
      this.initForm();
    });
  },
  async mounted() {
    let column_resource = _.split(this.resource, "/");

    await this.$http
      .get(`/${_.head(column_resource)}/filter`)
      .then((response) => {
        this.suppliers = response.data.suppliers;
        this.purchase_order_states = response.data.purchase_order_states;
        this.search.suppliers = null;
        this.search.purchase_order_states_id = null;
      });
    await this.$http
      .get(`/${_.head(column_resource)}/columns`)
      .then((response) => {
        this.columns = response.data;
        this.search.column = _.head(Object.keys(this.columns));
      });
    await this.getRecords();
  },
  methods: {
    customIndex(index) {
      return (
        this.pagination.per_page * (this.pagination.current_page - 1) +
        index +
        1
      );
    },
    getRecords() {
      this.loading_submit = true;
      return this.$http
        .get(`/${this.resource}/records?${this.getQueryParameters()}`)
        .then((response) => {
          this.records = response.data.data;
          this.pagination = response.data.meta;
          this.pagination.per_page = parseInt(response.data.meta.per_page);
        })
        .catch((error) => {})
        .then(() => {
          this.loading_submit = false;
        });
    },
    getQueryParameters() {
      return queryString.stringify({
        page: this.pagination.current_page,
        limit: this.limit,
        ...this.search,
      });
    },
    changeClearInput() {
      this.search.value = "";
      this.getRecords();
    },
    initForm() {
      this.search = {
        date_of_issue: moment().format("YYYY-MM-DD"),
        date_of_due: moment().format("YYYY-MM-DD"),
      };
    },
    clickDonwloadFilter() {
        window.open(`/${this.resource}/download_filters/${this.search.column}/${this.search.supplier_id}/${this.search.date_of_issue}/${this.search.date_of_due}/${this.search.purchase_order_states_id}`, '_blank');

    },
  },
};
</script>
