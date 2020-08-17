<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="close">
        <form autocomplete="off" @submit.prevent="clickAddItem">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.item_id}">
                            <label class="control-label">
                                Producto
                            </label>
                            <el-select v-model="form.item_id" @change="changeItem" filterable>
                                <el-option v-for="option in items" :key="option.id" :value="option.id" :label="option.full_description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.item_id" v-text="errors.item_id[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.quantity}">
                            <label class="control-label">Cantidad</label>
                            <el-input-number v-model="form.quantity" :min="0.01"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.quantity" v-text="errors.quantity[0]"></small>
                        </div>
                    </div> 

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.list_price}">
                            <label class="control-label">Precio Lista</label>
                            <el-input v-model="form.list_price" @input="inputListPrice">
                                <template slot="prepend" v-if="form.item.currency_type_symbol">{{ form.item.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.list_price" v-text="errors.list_price[0]"></small>
                        </div>
                    </div> 
                    
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.discount_one}">
                            <label class="control-label">Descuento % 1</label>
                            <el-input-number v-model="form.discount_one" @change="inputListPrice" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.discount_one" v-text="errors.discount_one[0]"></small>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.discount_two}">
                            <label class="control-label">Descuento % 2</label>
                            <el-input-number v-model="form.discount_two" @change="inputListPrice" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.discount_two" v-text="errors.discount_two[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.discount_three}">
                            <label class="control-label">Descuento % 3</label>
                            <el-input-number v-model="form.discount_three" @change="inputListPrice" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.discount_three" v-text="errors.discount_three[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.discount_four}">
                            <label class="control-label">Descuento % 4</label>
                            <el-input-number v-model="form.discount_four" @change="inputListPrice" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.discount_four" v-text="errors.discount_four[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_value}">
                            <label class="control-label">Costo sin IGV</label>
                            <el-input readonly v-model="form.unit_value">
                                <template slot="prepend" v-if="form.item.currency_type_symbol">{{ form.item.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.unit_value" v-text="errors.unit_value[0]"></small>
                        </div>
                    </div> 
                    
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_price}">
                            <label class="control-label">Costo con IGV</label>
                            <el-input readonly v-model="form.unit_price">
                                <template slot="prepend" v-if="form.item.currency_type_symbol">{{ form.item.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.unit_price" v-text="errors.unit_price[0]"></small>
                        </div>
                    </div>  
                    
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.sale_profit_factor}">
                            <label class="control-label">Factor Venta-Ganancia</label>
                            <el-input v-model="form.sale_profit_factor">
                                <template slot="prepend" v-if="form.item.currency_type_symbol">{{ form.item.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.sale_profit_factor" v-text="errors.sale_profit_factor[0]"></small>
                        </div>
                    </div> 

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.last_purchase_price}">
                            <label class="control-label">Ult. Prec. Compra</label>
                            <el-input v-model="form.last_purchase_price">
                                <template slot="prepend" v-if="form.item.currency_type_symbol">{{ form.item.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.last_purchase_price" v-text="errors.last_purchase_price[0]"></small>
                        </div>
                    </div> 

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.last_factor}">
                            <label class="control-label">Ult. Factor</label>
                            <el-input v-model="form.last_factor">
                                <template slot="prepend" v-if="form.item.currency_type_symbol">{{ form.item.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.last_factor" v-text="errors.last_factor[0]"></small>
                        </div>
                    </div> 

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.num_price}">
                            <label class="control-label">Prec. Vta. Num</label>
                            <el-input v-model="form.num_price">
                                <template slot="prepend" v-if="form.item.currency_type_symbol">{{ form.item.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.num_price" v-text="errors.num_price[0]"></small>
                        </div>
                    </div> 
                     
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.letter_price}">
                            <label class="control-label">Prec. Vta. Letra</label>
                            <el-input v-model="form.letter_price">
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.letter_price" v-text="errors.letter_price[0]"></small>
                        </div>
                    </div> 
                     
  
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button type="primary" native-type="submit" v-if="form.item_id" >Agregar</el-button>
            </div>
        </form> 

    </el-dialog>
</template>
<style>
.el-select-dropdown {
    max-width: 80% !important;
    margin-right: 5% !important;
}
</style>
<script>

    import {calculateRowItem} from '@helpers/functions'


    export default {
        props: ['showDialog', 'currencyTypeIdActive', 'exchangeRateSale', 'purchaseOrderId'],
        data() {
            return {
                titleDialog: 'Agregar Producto',
                resource: 'warehouse-income',
                showDialogNewItem: false,
                errors: {},
                form: {},
                items: [],
                use_price: 1,
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/item/tables`).then(response => {
                this.items = response.data.items
            })
 
        },
        methods: { 
            inputListPrice(){

                let total_discount_percentage = parseFloat(this.form.discount_one) + parseFloat(this.form.discount_two) + parseFloat(this.form.discount_three) + parseFloat(this.form.discount_four)
                let percentage_igv = 18
                let total_discount = parseFloat(this.form.list_price) * (total_discount_percentage / 100) 
                this.form.unit_value = parseFloat(this.form.list_price) - total_discount
                this.form.unit_price = _.round((this.form.list_price * (1 + percentage_igv/100)) - total_discount, 2)

            },
            initForm() {
                this.errors = {}
                this.form = {

                    item_id: null,
                    item: {},
                    item_id: null,
                    quantity: 1,
                    list_price: 0,
                    discount_one: 0,
                    discount_two: 0,
                    discount_three: 0,
                    discount_four: 0,
                    unit_value: 0,
                    unit_price: 0,
                    sale_profit_factor: 0,
                    last_purchase_price: 0,
                    last_factor: 0,
                    num_price: 0,
                    letter_price: null,
                    total_value: 0,
                    total: 0,
                }

                this.item_unit_type = {};
            },
            // initializeFields() {
            //     this.form.affectation_igv_type_id = this.affectation_igv_types[0].id
            // },
            create() {
            //     this.initializeFields()
            }, 
            close() {
                this.initForm()
                this.$emit('update:showDialog', false)
            }, 
            async changeItem() {


                this.form.item = await _.find(this.items, {'id': this.form.item_id})
                await this.getListPrice()



            },
            getListPrice(){
                
                if(this.purchaseOrderId){

                    this.$http.get(`/${this.resource}/item/list-price/${this.form.item_id}/${this.purchaseOrderId}`).then(response => {
                        this.form.list_price = response.data.list_price
                        this.inputListPrice()
                    })
                }

            },
            clickAddItem() {
                this.form.item.unit_price = this.form.unit_price
                this.form.item.presentation = this.item_unit_type;
                this.form.affectation_igv_type = _.find(this.affectation_igv_types, {'id': this.form.affectation_igv_type_id})
                this.row = calculateRowItem(this.form, this.currencyTypeIdActive, this.exchangeRateSale)
                this.row = this.changeWarehouse(this.row)

                this.setAdditionalData()
                this.initForm()
                // this.initializeFields()
                this.$emit('add', this.row)
            },
            setAdditionalData(){

                this.row.previous_cost = this.form.previous_cost
                this.row.attended_quantity = this.form.attended_quantity
                this.row.observation = this.form.observation
                this.row.previous_currency_type_id = this.form.previous_currency_type_id
                this.row.pending_quantity_income = this.form.quantity

            }, 
        }
    }

</script>
