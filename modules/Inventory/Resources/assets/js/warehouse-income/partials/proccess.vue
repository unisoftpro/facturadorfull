<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="40%"
               :close-on-click-modal="false"
               :close-on-press-escape="false"
               :show-close="false">

        <div class="row">

            <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold mt-3">

                <el-popover

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
                </el-popover>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold mt-3">
                <el-popover

                  placement="right"
                  width="350"
                  trigger="click"
                >
                <div style="text-align: left; margin: 0">
                    <label for="">Moneda</label>
                    <el-select
                      v-model="form_process_local.currency_type_id"
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
                    @click="clickProccessLocal"
                      type="primary"
                      plain
                      >Procesar</el-button
                    >
                  </div>
                <el-button
                    type="warning"
                    icon="el-icon-s-tools"
                    slot="reference"
                  >Procesar Precios Compra-Local</el-button
                >
                </el-popover>

            </div>

        </div>
        <span slot="footer" class="dialog-footer">
            <template v-if="showClose">
                <el-button @click="clickClose">Cerrar</el-button>
            </template>
            <template v-else>
                <el-button @click="clickFinalize">Ir al listado</el-button>
                <el-button type="primary" @click="clickNewDocument">Nueva compra</el-button>
            </template>
        </span>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'recordId', 'showClose', 'type','wareHouseIncomeId'],
        data() {
            return {
                titleDialog: 'Procesar Precios',
                loading: false,
                resource: 'warehouse-income',
                errors: {},
                form: {},
                currency_types: [],
                type_list_prices: [],
                form_process_price: {
                    list_type_id: null,
                    factor: null,
                    currency_type_id: null,
                    id:null
                },
                form_process_local :{
                     currency_type_id: null,
                }
            }
        },
        async created() {
            await this.initForm();
            await this.getTables();
        },
        methods: {
            clickDownload(template)
            {
                window.open(`/${this.resource}/download/${this.form.external_id}/${template}`, '_blank');
            },
            clickPrint(format){
                window.open(`/${this.resource}/print/${this.form.external_id}/${format}`, '_blank');
            },
            initForm() {
                this.errors = {}

                this.form = {
                    id: null,
                    external_id: null,
                    number: null,
                    items: [],
                }
            },
            create() {

                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data

                    })
            },

            clickFinalize() {
                location.href = `/${this.resource}`
            },
            clickNewDocument() {
                this.clickClose()
            },
            clickClose() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
            async getTables() {
                await this.$http.get(`/${this.resource}/tables`).then((response) => {
                    this.currency_types = response.data.currency_types;
                    this.type_list_prices = response.data.type_list_prices;
                });
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
            async clickProccessExterior() {

                    if (
                        !this.form_process_price.list_type_id ||
                        !this.form_process_price.factor ||
                        !this.form_process_price.currency_type_id
                    ) {
                    return;
                    }
                    const payload = {
                        items: await this.getItemPurchaseExterior(),
                    };
                    await this.requestProcessPrices(payload);

                    this.form_process_price = {
                        list_type_id: null,
                        factor: null,
                        currency_type_id:null,
                    };

            },
            async clickProccessLocal() {
                if (!this.form_process_local.currency_type_id) {
                    return;
                }
                const list_type_id = "01";
                const currency_type_id = this.form_process_local.currency_type_id;
                const payload = {
                    items: await this.getItemPurchaseLocal(list_type_id,currency_type_id),
                };

                await this.requestProcessPrices(payload);
                 this.form_process_local = {
                        currency_type_id:null,
                    };
            },
            async getItemPurchaseExterior() {
                const list_type_id = this.form_process_price.list_type_id;
                const factor = this.form_process_price.factor;
                const currency_type_id = this.form_process_price.currency_type_id;

                await this.$http.get(`/${this.resource}/items/${this.recordId}`).then((response) => {
                    this.form.items = response.data.record;
                });
                console.log(this.form.items);
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
                    id:null
                    };
                });
            },
            async getItemPurchaseLocal(list_type_id,currency_type_id) {
                await this.$http.get(`/${this.resource}/items/${this.recordId}`).then((response) => {
                    this.form.items = response.data.record;
                });
                return this.form.items.map((row) => {
                    return {
                    item_id: row.item_id,
                    list_type_id: list_type_id,
                    price_fob: this.getPriceFob(row),
                    currency_type_id: currency_type_id,
                    factor: row.sale_profit_factor,
                    price_list: row.retail_price,
                    discount_one: row.discount_one,
                    discount_two: row.discount_two,
                    discount_three: row.discount_three,
                    id:null
                    };
                });
            },
            calculateValuesExterior(row, factor) {
                const type = this.wareHouseIncomeId;

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
            getPriceFob(row) {
                const type = this.wareHouseIncomeId;

                if (type == "104") {
                    return row.unit_value;
                } else if (type == "103") {
                    return row.price_fob_alm;
                }
            },
        }
    }
</script>
