<template>
  <div>
    <div class="row">
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
      form: {},
      loading_submit: false,
    };
  },
  created() {
    this.getRecords();
  },
  methods: {
    async getRecords() {
      await this.$http.get(`/${this.resource}/records`).then((response) => {
          this.records = response.data.data;

          this.pagination = response.data.meta;
          this.pagination.per_page = parseInt(response.data.meta.per_page);
          this.loading_submit = false;
      });
    },
    customIndex(index) {
      return (
        this.pagination.per_page * (this.pagination.current_page - 1) +
        index +
        1
      );
    },
  },
};
</script>
