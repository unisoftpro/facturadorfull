<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Ingreso a almacén</h3>
        </div>
        <div class="tab-content">
            <form autocomplete="off" @submit.prevent="submit">
                <div class="form-body">

                    <div class="row">
                         <div class="col-lg-4">
                            <div class="form-group" :class="{'has-danger': errors.warehouse_id}">
                                <label class="control-label">Almacén</label>
                                <el-select v-model="form.warehouse_id" filterable>
                                    <el-option v-for="option in warehouses" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.warehouse_id" v-text="errors.warehouse_id[0]"></small>
                            </div>
                        </div> 

                         <div class="col-lg-4">
                            <div class="form-group" :class="{'has-danger': errors.warehouse_income_reason_id}">
                                <label class="control-label">Motivo</label>
                                <el-select v-model="form.warehouse_income_reason_id" filterable @change="changeWarehouseIncomeReason">
                                    <el-option v-for="option in warehouse_income_reasons" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.warehouse_income_reason_id" v-text="errors.warehouse_income_reason_id[0]"></small>
                            </div>
                        </div> 
                        
                         <div class="col-lg-4">
                            <div class="form-group" :class="{'has-danger': errors.supplier_id}">
                                <label class="control-label">Proveedor</label>
                                <el-select v-model="form.supplier_id" filterable @change="changeSupplier">
                                    <el-option v-for="option in suppliers" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.supplier_id" v-text="errors.supplier_id[0]"></small>
                            </div>
                        </div> 

                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                <label class="control-label">Fec. Emisión</label>
                                <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                            </div>
                        </div>


                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.reference_date}">
                                <label class="control-label">Fec. Referencia</label>
                                <el-date-picker v-model="form.reference_date" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateReference"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.reference_date" v-text="errors.reference_date[0]"></small>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                                <label class="control-label">Moneda</label>
                                <el-select v-model="form.currency_type_id" filterable @change="changeCurrencyType">
                                    <el-option v-for="option in currency_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.observation}">
                                <label class="control-label">Observación</label>
                                <el-input v-model="form.observation"></el-input>
                                <small class="form-control-feedback" v-if="errors.observation" v-text="errors.observation[0]"></small>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.purchase_order_id}">
                                <label class="control-label">O. Compra</label>
                                <el-select v-model="form.purchase_order_id" filterable>
                                    <el-option v-for="option in purchase_orders" :key="option.id" :value="option.id" :label="option.number"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.purchase_order_id" v-text="errors.purchase_order_id[0]"></small>
                            </div>
                        </div>
 
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.work_order_id}">
                                <label class="control-label">O. Trabajo</label>
                                <el-select v-model="form.work_order_id" filterable>
                                    <el-option v-for="option in work_orders" :key="option.id" :value="option.id" :label="option.number"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.work_order_id" v-text="errors.work_order_id[0]"></small>
                            </div>
                        </div>

                        <div class="col-lg-2" v-if="form.currency_type_id == 'USD'">
                            <div class="form-group" :class="{'has-danger': errors.exchange_rate_sale}">
                                <label class="control-label">Tipo de cambio
                                    <el-tooltip class="item" effect="dark" content="Tipo de cambio del día, extraído de SUNAT" placement="top-end">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </label>
                                <el-input v-model="form.exchange_rate_sale" readonly></el-input>
                                <small class="form-control-feedback" v-if="errors.exchange_rate_sale" v-text="errors.exchange_rate_sale[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 d-flex align-items-end mt-4">
                            <div class="form-group">
                                <button type="button" class="btn waves-effect waves-light btn-primary" @click.prevent="showDialogAddItem = true">+ Agregar Producto</button>
                            </div>
                        </div>
                        
                    </div>  
                    <div class="row mt-2" v-if="form.items.length > 0">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th class="text-center">Unidad</th>
                                        <th class="text-right">Cantidad</th>
                                        <th class="text-right">Precio lista</th>
                                        <th class="text-right">Precio Venta Público</th>
                                        <th class="text-right">Total</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in form.items" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ row.item.description }}</td>
                                        <td class="text-center">{{ row.item.unit_type_id }}</td>
                                        <td class="text-right">{{ row.quantity }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.list_price }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.retail_price }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total }}</td>
                                        <td class="text-right">
                                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveItem(index)">x</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <p class="text-right" v-if="form.total_value > 0">Sub Total: {{ currency_type.symbol }} {{ form.total_value }}</p>
                            <p class="text-right" v-if="form.original_total > 0">Total Original: {{ currency_type.symbol }} {{ form.original_total }}</p>
                            <p class="text-right" v-if="form.national_total > 0">Total Nacional: S/ {{ form.national_total }}</p>
                            <p class="text-right" v-if="form.total > 0">Total: {{ currency_type.symbol }} {{ form.total }}</p>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <el-button type="primary" native-type="submit" :loading="loading_submit" v-if="form.items.length > 0">Generar</el-button>
                </div>
            </form>
        </div>
 
        <item-form :showDialog.sync="showDialogAddItem"
                           :currency-type-id-active="form.currency_type_id"
                           :exchange-rate-sale="form.exchange_rate_sale"
                           :purchase-order-id="form.purchase_order_id"
                           :warehouse-income-reason-id="form.warehouse_income_reason_id"
                           @add="addRow"
                           ref="item_form"></item-form>
 

    </div>
</template>

<script>
 
    import ItemForm from './partials/item.vue'

    export default {
        components: {ItemForm},
        data() {
            return {
                resource: 'warehouse-income',
                errors: {},
                form: {},
                warehouses: [],
                purchase_orders: [],
                warehouse_income_reasons: [],
                suppliers: [],
                currency_types: [],
                work_orders: [],
                company: null,
                number: null,
                currentRow: {},
                showDialogAddItem: false,
                loading_submit: false,
                currency_type: {},
            }
        },
        async created() {
            await this.initForm()
            await this.getTables()
 
        },
        methods: { 
            changeSupplier(){
                this.getExchangeRatePurchaseOrder()
            },
            changeDateReference(){
                this.getExchangeRatePurchaseOrder()
            },
            changeWarehouseIncomeReason(){
                // this.renewCalculate()
            },
            async changeCurrencyType(){

                if(this.form.currency_type_id == 'USD'){

                    if(!this.form.supplier_id){
                        this.form.currency_type_id = 'PEN'
                        return this.$message.error('Debe seleccionar un proveedor para buscar el tipo de cambio')
                    }
    
                    await this.getExchangeRatePurchaseOrder()
                }

                this.currency_type = await _.find(this.currency_types, {'id': this.form.currency_type_id})
                await this.renewCalculate()

            },
            async renewCalculate(){

                let items = []
                await this.form.items.forEach((row) => {
                    items.push(this.$refs.item_form.calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
                })

                this.form.items = items
                this.calculateTotal()

            },
            async getExchangeRatePurchaseOrder(){
                
                if(this.form.currency_type_id == 'USD'){

                    await this.$http.get(`/${this.resource}/exchange-rate/${this.form.reference_date}/${this.form.supplier_id}`)
                        .then(response => {

                            this.form.exchange_rate_sale = response.data.exchange_rate_sale

                            if(!response.data.success){
                                this.$message.warning(response.data.message)
                            }
                            
                        })

                }
            },
            async getTables(){
                
                await this.$http.get(`/${this.resource}/tables`)
                    .then(response => {
                        this.warehouses = response.data.warehouses 
                        this.purchase_orders = response.data.purchase_orders 
                        this.warehouse_income_reasons = response.data.warehouse_income_reasons 
                        this.suppliers = response.data.suppliers 
                        this.currency_types = response.data.currency_types 
                        this.work_orders = response.data.work_orders 
                        this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
                        this.form.warehouse_income_reason_id = this.warehouse_income_reasons.length > 0 ? this.warehouse_income_reasons[0].id : null
                    })

            }, 
            async validates(){
 
                // if(!this.form.purchase_order){
                //     return  {
                //         success : false,
                //         message : 'Debe seleccionar 1 orden de compra'
                //     }
                // }

                return  {
                    success : true,
                    message : null
                }
            }, 
            clickRemoveItem(index) {
                this.form.items.splice(index, 1)
                this.calculateTotal()
            },
            addRow(row) {
                this.form.items.push(row)
                this.calculateTotal()
            },
            calculateTotal() { 
                let total_value = 0
                let total = 0

                this.form.items.forEach((row) => {
                    total_value += parseFloat(row.total_value)
                    total += parseFloat(row.total)
                });
 
                this.form.total_value = _.round(total_value, 2)
                this.form.total = _.round(total, 2)
                this.form.original_total = this.form.total
                this.form.national_total = (this.form.currency_type_id == 'USD') ? _.round(this.form.total * this.form.exchange_rate_sale, 2) : this.form.total

            },
            initForm() {

                this.errors = {}

                this.form = {
                    warehouse_id: null,
                    warehouse_income_reason_id: null,
                    date_of_issue: moment().format('YYYY-MM-DD'), 
                    supplier_id: null,
                    currency_type_id:  'PEN',
                    observation: null,
                    reference_date: moment().format('YYYY-MM-DD'),
                    purchase_order_id: null,
                    work_order_id: null,
                    original_total: 0,
                    exchange_rate_sale: 1,
                    national_total: 0,
                    total_value: 0,
                    total: 0,
                    items: [],

                }

            },
            resetForm() {
                this.initForm()
                this.getTables()
            }, 
            async submit() { 

                // let validate = await this.validates()
                // if(!validate.success) {
                //     return this.$message.error(validate.message);
                // }

                // this.loading_submit = true
                await this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {

                        if (response.data.success) {

                            this.$message.success(response.data.message)
                            this.resetForm()
 
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            this.$message.error(error.response.data.message)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                location.href = `/${this.resource}`
            }, 

        }
    }
</script>
