<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <!-- <div class="card-header bg-info">
            <h3 class="my-0">Nuevo Comprobante</h3>
        </div> -->

        <div class="tab-content"  v-if="company && establishment">
            <div class="invoice">
                <header class="clearfix">
                    <div class="row"> 
                        <div class="col-sm-10 text-left mt-3 mb-0">
                            <address class="ib mr-2" >
                                <h3 class="font-weight-bold d-block">ORDEN DE TRABAJO</h3>
                                <span class="font-weight-bold d-block">OT-XXX</span>
                                <span class="font-weight-bold">{{company.name}}</span>
                                <div v-if="establishment.address != '-'">{{ establishment.address }}, </div> {{ establishment.district.description }}, {{ establishment.province.description }}, {{ establishment.department.description }} - {{ establishment.country.description }}
                                <!-- {{establishment.email}} - <span v-if="establishment.telephone != '-'">{{establishment.telephone}}</span> -->
                            </address>
                        </div>
                    </div>
                </header>
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row mt-1">
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.establishment_id}">
                                    <label class="control-label">Establecimiento</label>
                                    <el-select v-model="form.establishment_id" @change="changeEstablishment">
                                        <el-option v-for="option in establishments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.establishment_id" v-text="errors.establishment_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group" :class="{'has-danger': errors.process_id}">
                                    <label class="control-label">Proceso</label>
                                    <el-select v-model="form.process_id" filterable>
                                        <el-option v-for="option in processes" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.process_id" v-text="errors.process_id[0]"></small>
                                </div>
                            </div> 
                             <div class="col-lg-5 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.customer_id}">
                                    <label class="control-label font-weight-bold text-info">
                                        Cliente
                                        <a href="#" @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                                    </label>
                                    <el-select v-model="form.customer_id" filterable remote class="border-left rounded-left border-info" popper-class="el-select-customers"
                                        dusk="customer_id"
                                        placeholder="Escriba el nombre o número de documento del cliente"
                                        :remote-method="searchRemoteCustomers"
                                        :loading="loading_search">

                                        <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.description"></el-option>

                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.customer_id" v-text="errors.customer_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.opening_date}">
                                    <label class="control-label">Fec. Apertura</label>
                                    <el-date-picker v-model="form.opening_date" type="date" value-format="yyyy-MM-dd" :clearable="false" ></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.opening_date" v-text="errors.opening_date[0]"></small>
                                </div>
                            </div> 

                            <div class="col-lg-4 col-md-4">
                                <div class="form-group" :class="{'has-danger': errors.final_item_warehouse_id}">
                                    <label class="control-label">
                                        Almacén para producto final
                                    </label>
                                    <el-select v-model="form.final_item_warehouse_id" filterable>
                                        <el-option v-for="option in warehouses" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.final_item_warehouse_id" v-text="errors.final_item_warehouse_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="form-group" :class="{'has-danger': errors.process_warehouse_id}">
                                    <label class="control-label">
                                        Almacén para proceso
                                    </label>
                                    <el-select v-model="form.process_warehouse_id" filterable>
                                        <el-option v-for="option in warehouses" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.process_warehouse_id" v-text="errors.process_warehouse_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group" :class="{'has-danger': errors.origin_warehouse_id}">
                                    <label class="control-label">
                                       Origen de la materia prima
                                    </label>
                                    <el-select v-model="form.origin_warehouse_id" filterable>
                                        <el-option v-for="option in warehouses" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.origin_warehouse_id" v-text="errors.origin_warehouse_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group" :class="{'has-danger': errors.incoming_items}">
                                    <label class="control-label">Prod. Entrantes</label>
                                    <el-input v-model="form.incoming_items" :min="0"></el-input>
                                    <small class="form-control-feedback" v-if="errors.incoming_items" v-text="errors.incoming_items[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group" :class="{'has-danger': errors.result_items}">
                                    <label class="control-label">Resultados</label>
                                    <el-input v-model="form.result_items" :min="0"></el-input>
                                    <small class="form-control-feedback" v-if="errors.result_items" v-text="errors.result_items[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group" :class="{'has-danger': errors.difference}">
                                    <label class="control-label">Diferencia</label>
                                    <el-input v-model="form.difference" ></el-input>
                                    <small class="form-control-feedback" v-if="errors.difference" v-text="errors.difference[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group" :class="{'has-danger': errors.lot_number}">
                                    <label class="control-label">Lote</label>
                                    <el-input v-model="form.lot_number" ></el-input>
                                    <small class="form-control-feedback" v-if="errors.lot_number" v-text="errors.lot_number[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group" :class="{'has-danger': errors.start_date}">
                                    <label class="control-label">Fec. Inicio</label>
                                    <el-date-picker v-model="form.start_date" type="date" value-format="yyyy-MM-dd" :clearable="false" ></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.start_date" v-text="errors.start_date[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group" :class="{'has-danger': errors.start_time}">
                                    <label class="control-label">Hora inicio</label>
                                    <el-time-picker v-model="form.start_time" format="HH:mm" value-format="HH:mm" :clearable="false" ></el-time-picker>
                                    <small class="form-control-feedback" v-if="errors.start_time" v-text="errors.start_time[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group" :class="{'has-danger': errors.end_date}">
                                    <label class="control-label">Fec. Término</label>
                                    <el-date-picker v-model="form.end_date" type="date" value-format="yyyy-MM-dd" :clearable="false" ></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.end_date" v-text="errors.end_date[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group" :class="{'has-danger': errors.end_time}">
                                    <label class="control-label">Hora Término</label>
                                    <el-time-picker v-model="form.end_time" format="HH:mm" value-format="HH:mm" :clearable="false" ></el-time-picker>
                                    <small class="form-control-feedback" v-if="errors.end_time" v-text="errors.end_time[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group" :class="{'has-danger': errors.hours}">
                                    <label class="control-label">Horas</label>
                                    <el-input v-model="form.hours"  ></el-input>
                                    <small class="form-control-feedback" v-if="errors.hours" v-text="errors.hours[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group" :class="{'has-danger': errors.license_plate}">
                                    <label class="control-label">Placa</label>
                                    <el-input v-model="form.license_plate" type="texarea"  ></el-input>
                                    <small class="form-control-feedback" v-if="errors.license_plate" v-text="errors.license_plate[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group" :class="{'has-danger': errors.mileage}">
                                    <label class="control-label">Kilometraje</label>
                                    <el-input v-model="form.mileage"  ></el-input>
                                    <small class="form-control-feedback" v-if="errors.mileage" v-text="errors.mileage[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group">
                                    <label class="control-label">Responsable</label>
                                    <el-input v-model="user.name" readonly></el-input>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.activity_type_id}">
                                    <label class="control-label">
                                       Tipo de actividad
                                    </label>
                                    <el-select v-model="form.activity_type_id" filterable>
                                        <el-option v-for="option in activity_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.activity_type_id" v-text="errors.activity_type_id[0]"></small>
                                </div>
                            </div>
                            

                            <div class="col-lg-4 col-md-4">
                                <div class="form-group" :class="{'has-danger': errors.detail}">
                                    <label class="control-label">Detalle</label>
                                    <el-input v-model="form.detail"  type="textarea" autosize></el-input>
                                    <small class="form-control-feedback" v-if="errors.detail" v-text="errors.detail[0]"></small>
                                </div>
                            </div>
                        </div>

 

                    </div>


                    <div class="form-actions text-right mt-4">
                        <el-button @click.prevent="close()">Cancelar</el-button>
                        <el-button class="submit" type="primary" native-type="submit" :loading="loading_submit" >Generar</el-button>
                    </div>
                </form>
            </div>
        </div>

        <person-form :showDialog.sync="showDialogNewPerson"
                       type="customers"
                       :external="true"
                       :document_type_id = form.document_type_id></person-form>

        <work-order-options :showDialog.sync="showDialogOptions"
                          :recordId="recordId"
                          :showClose="false"></work-order-options>
    </div>
</template>

<script>
    import PersonForm from '@views/persons/form.vue'
    import WorkOrderOptions from './partials/options.vue'

    export default {
        props: ['id', 'user'],
        components: {PersonForm, WorkOrderOptions},
        data() {
            return {
                resource: 'transport/work-orders',
                showDialogNewPerson: false,
                showDialogOptions: false,
                loading_submit: false,
                loading_form: false,
                errors: {},
                form: {},
                processes: [],
                warehouses: [],
                activity_types: [],
                all_customers: [],
                customers: [],
                company: null,
                establishments: [],
                establishment: null,
                currency_type: {},
                recordId: null,
                loading_search:false,

            }
        },
        async created() {
            await this.initForm()

            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.company = response.data.company
                    this.processes = response.data.processes
                    this.establishments = response.data.establishments
                    this.all_customers = response.data.customers
                    this.warehouses = response.data.warehouses
                    this.activity_types = response.data.activity_types
                    this.form.establishment_id = (this.establishments.length > 0)?this.establishments[0].id:null
                    this.changeEstablishment()
                    this.allCustomers()
                })

            this.loading_form = true
            this.$eventHub.$on('reloadDataPersons', (customer_id) => {
                this.reloadDataCustomers(customer_id)
            })

            this.isUpdate()

        },
        methods: {  
            async isUpdate(){

                if (this.id) {
                    await this.$http.get(`/${this.resource}/record/${this.id}`)
                        .then(response => {
                            this.form = response.data.data;
                        })
                }

            }, 
            searchRemoteCustomers(input) {

                if (input.length > 0) {
                    this.loading_search = true
                    let parameters = `input=${input}`

                    this.$http.get(`/${this.resource}/search/customers?${parameters}`)
                            .then(response => {
                                this.customers = response.data.customers
                                this.loading_search = false
                                if(this.customers.length == 0){this.allCustomers()}
                            })
                } else {
                    this.allCustomers()
                }

            },
            initForm() {

                this.errors = {}

                this.form = {
                    prefix: 'OT',
                    establishment_id: null,
                    customer_id: null,
                    process_id: null,
                    opening_date: moment().format('YYYY-MM-DD'),
                    work_order_state_id: null,
                    final_item_warehouse_id: null,
                    process_warehouse_id: null,
                    origin_warehouse_id: null,
                    detail: null, 
                    incoming_items: 0,
                    result_items: 0,
                    difference: 0, 
                    lot_number: null,
                    start_date: moment().format('YYYY-MM-DD'),
                    start_time: moment().format('HH:mm:ss'),
                    end_date: moment().format('YYYY-MM-DD'),
                    end_time: moment().format('HH:mm:ss'),
                    hours: 0,
                    license_plate: null,
                    mileage: 0,
                    activity_type_id: null,
                }

            },
            resetForm() {
                this.initForm()
                this.form.establishment_id = (this.establishments.length > 0)?this.establishments[0].id:null
                this.changeEstablishment()
                this.allCustomers()
            },
            changeEstablishment() {
                this.establishment = _.find(this.establishments, {'id': this.form.establishment_id})
            },
            cleanCustomer(){
                this.form.customer_id = null
            },
            allCustomers() {
                this.customers = this.all_customers
            },
            async submit() {

                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form).then(response => {
                    if (response.data.success) {

                        this.form_payment.sale_note_id = response.data.data.id;
                        this.resetForm();
                        this.recordId = response.data.data.id;
                        this.showDialogOptions = true;
                        this.isUpdate()

                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    }
                    else {
                        this.$message.error(error.response.data.message);
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            },
            close() {
                location.href = '/sale-notes'
            },
            reloadDataCustomers(customer_id) {
                this.$http.get(`/${this.resource}/search/customer/${customer_id}`).then((response) => {
                    this.customers = response.data.customers
                    this.form.customer_id = customer_id
                })
            },
        }
    }
</script>
