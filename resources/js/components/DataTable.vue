<template>
    <div v-loading="loading_submit">
        <div class="row ">

            <div class="col-md-12 col-lg-12 col-xl-12 ">
                <div class="row" v-if="applyFilter">
                    <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
                        <div class="d-flex">
                            <div style="width:100px">
                                Filtrar por:
                            </div>
                            <el-select v-model="search.column"  placeholder="Select" @change="changeClearInput">
                                <el-option v-for="(label, key) in columns" :key="key" :value="key" :label="label"></el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12 pb-2">
                        <template>
                                <el-date-picker v-model="search.value" type="date" @change="changeClearInput"
                                placeholder="Buscar" value-format="yyyy-MM-dd" format="dd/MM/yyyy" :clearable="true"></el-date-picker>
                        </template>
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
                        <slot v-for="(row, index) in records" :row="row" :index="customIndex(index)"></slot>
                        </tbody>
                    </table>
                    <div>
                        <el-pagination
                                @current-change="getRecords"
                                layout="total, prev, pager, next"
                                :total="pagination.total"
                                :current-page.sync="pagination.current_page"
                                :page-size="pagination.per_page">
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>


<script>

    import moment from 'moment'
    import queryString from 'query-string'

    export default {
        props: {
            resource: String,
            applyFilter:{
                type: Boolean,
                default: true,
                required: false
            }
        },
        data () {
            return {
                search: {
                    column: null,
                    value: null,
                    supplier_id:null,
                    date_of_issue:null,
                    date_of_due:null
                },
                form :{},
                suppliers:[],
                columns: [],
                records: [],
                pagination: {},
                loading_submit: false,
                pickerOptionsDates: {
                    disabledDate: (time) => {
                        time = moment(time).format('YYYY-MM-DD')
                        return this.form.date_start > time
                    }
                },
            }
        },
        computed: {
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getRecords();
                this.initForm();
            })
        },
        async mounted () {
            let column_resource = _.split(this.resource, '/')
            await this.$http.get(`/${_.head(column_resource)}/columns`).then((response) => {
                this.columns = response.data
                this.search.column = _.head(Object.keys(this.columns))
            });
            await this.getRecords()

        },
        methods: {
            customIndex(index) {
                return (this.pagination.per_page * (this.pagination.current_page - 1)) + index + 1
            },
            getRecords() {
                this.loading_submit = true
                return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`).then((response) => {
                    this.records = response.data.data
                    this.pagination = response.data.meta
                    this.pagination.per_page = parseInt(response.data.meta.per_page)
                }).catch(error => {
                }).then(() => {
                    this.loading_submit = false
                });
            },
            getQueryParameters() {
                return queryString.stringify({
                    page: this.pagination.current_page,
                    limit: this.limit,
                    ...this.search
                })
            },
            changeClearInput(){
                this.search.value = ''
                this.getRecords()
            },
            initForm(){
                this.search = {
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    date_of_due: moment().format('YYYY-MM-DD'),
                }

            }
        }
    }
</script>
