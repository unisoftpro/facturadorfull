<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" width="65%" :close-on-click-modal="false">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.license_plate}">
                            <label class="control-label">N° Placa</label>
                            <el-input v-model="form.license_plate"></el-input>
                            <small class="form-control-feedback" v-if="errors.license_plate" v-text="errors.license_plate[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.old_license_plate}">
                            <label class="control-label">N° Placa Anterior</label>
                            <el-input v-model="form.old_license_plate"></el-input>
                            <small class="form-control-feedback" v-if="errors.old_license_plate" v-text="errors.old_license_plate[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.registration_starting}">
                            <label class="control-label">Partida registral</label>
                            <el-input v-model="form.registration_starting"></el-input>
                            <small class="form-control-feedback" v-if="errors.registration_starting" v-text="errors.registration_starting[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.title}">
                            <label class="control-label">Titulo</label>
                            <el-input v-model="form.title"></el-input>
                            <small class="form-control-feedback" v-if="errors.title" v-text="errors.title[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.date}">
                            <label class="control-label">Fecha</label>
                            <el-date-picker v-model="form.date" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date" v-text="errors.date[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.condition}">
                            <label class="control-label">Condición</label>
                            <el-input v-model="form.condition"></el-input>
                            <small class="form-control-feedback" v-if="errors.condition" v-text="errors.condition[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.owner_type}">
                            <label class="control-label">Tipo de propietario</label>
                            <el-input v-model="form.owner_type"></el-input>
                            <small class="form-control-feedback" v-if="errors.owner_type" v-text="errors.owner_type[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.denomination}">
                            <label class="control-label">Denominación</label>
                            <el-input v-model="form.denomination"></el-input>
                            <small class="form-control-feedback" v-if="errors.denomination" v-text="errors.denomination[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.lastname}">
                            <label class="control-label">A. Paterno</label>
                            <el-input v-model="form.lastname"></el-input>
                            <small class="form-control-feedback" v-if="errors.lastname" v-text="errors.lastname[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.mother_lastname}">
                            <label class="control-label">A. Materno</label>
                            <el-input v-model="form.mother_lastname"></el-input>
                            <small class="form-control-feedback" v-if="errors.mother_lastname" v-text="errors.mother_lastname[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.names}">
                            <label class="control-label">Nombres</label>
                            <el-input v-model="form.names"></el-input>
                            <small class="form-control-feedback" v-if="errors.names" v-text="errors.names[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.acquisition_date}">
                            <label class="control-label">Fecha Adq.</label>
                            <el-date-picker v-model="form.acquisition_date" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.acquisition_date" v-text="errors.acquisition_date[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.expedition_date}">
                            <label class="control-label">Exp. Tarj.</label>
                            <el-date-picker v-model="form.expedition_date" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.expedition_date" v-text="errors.expedition_date[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.temporary_validity}">
                            <label class="control-label">Vig. Temp.</label>
                            <el-input v-model="form.temporary_validity"></el-input>
                            <small class="form-control-feedback" v-if="errors.temporary_validity" v-text="errors.temporary_validity[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.address}">
                            <label class="control-label">Domicilio</label>
                            <el-input v-model="form.address"></el-input>
                            <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                        </div>
                    </div> 

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.insurance_id}">
                            <label class="control-label">Seguro</label>
                            <el-select v-model="form.insurance_id" filterable >
                                <el-option v-for="option in insurances" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.insurance_id" v-text="errors.insurance_id[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.insurance_date_of_due}">
                            <label class="control-label">Fec. Vencimiento seguro</label>
                            <el-date-picker v-model="form.insurance_date_of_due" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.insurance_date_of_due" v-text="errors.insurance_date_of_due[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.customer_id}">
                            <label class="control-label ">
                                Cliente
                            </label>
                            <el-select v-model="form.customer_id" filterable remote 
                                dusk="customer_id"                                    
                                placeholder="Escriba el nombre o número de documento del cliente"
                                :remote-method="searchRemoteCustomers"
                                :loading="loading_search">
                                <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.customer_id" v-text="errors.customer_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.vehicle_type_id}">
                            <label class="control-label">Tipo de vehículo</label>
                            <el-select v-model="form.vehicle_type_id" filterable >
                                <el-option v-for="option in vehicle_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.vehicle_type_id" v-text="errors.vehicle_type_id[0]"></small>
                        </div>
                    </div> 
                    

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.category}">
                            <label class="control-label">Categoría</label>
                            <el-input v-model="form.category"></el-input>
                            <small class="form-control-feedback" v-if="errors.category" v-text="errors.category[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.vehicle_brand_id}">
                            <label class="control-label">Marca</label>
                            <el-select v-model="form.vehicle_brand_id" filterable >
                                <el-option v-for="option in vehicle_brands" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.vehicle_brand_id" v-text="errors.vehicle_brand_id[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.model}">
                            <label class="control-label">Modelo</label>
                            <el-input v-model="form.model"></el-input>
                            <small class="form-control-feedback" v-if="errors.model" v-text="errors.model[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.color_id}">
                            <label class="control-label">Color</label>
                            <el-select v-model="form.color_id" filterable >
                                <el-option v-for="option in colors" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.color_id" v-text="errors.color_id[0]"></small>
                        </div>
                    </div>  
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.motor}">
                            <label class="control-label">Motor</label>
                            <el-input v-model="form.motor"></el-input>
                            <small class="form-control-feedback" v-if="errors.motor" v-text="errors.motor[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.fuel_id}">
                            <label class="control-label">Combustible</label>
                            <el-select v-model="form.fuel_id" filterable >
                                <el-option v-for="option in fuels" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.fuel_id" v-text="errors.fuel_id[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.rolling_form}">
                            <label class="control-label">Form. Rodante</label>
                            <el-input v-model="form.rolling_form"></el-input>
                            <small class="form-control-feedback" v-if="errors.rolling_form" v-text="errors.rolling_form[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.vin}">
                            <label class="control-label">VIN</label>
                            <el-input v-model="form.vin"></el-input>
                            <small class="form-control-feedback" v-if="errors.vin" v-text="errors.vin[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.serie}">
                            <label class="control-label">Serie/Chasis</label>
                            <el-input v-model="form.serie"></el-input>
                            <small class="form-control-feedback" v-if="errors.serie" v-text="errors.serie[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.manufacturing_year}">
                            <label class="control-label">Año de Fab</label>
                            <el-input v-model="form.manufacturing_year"></el-input>
                            <small class="form-control-feedback" v-if="errors.manufacturing_year" v-text="errors.manufacturing_year[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.model_year}">
                            <label class="control-label">Año Modelo</label>
                            <el-input v-model="form.model_year"></el-input>
                            <small class="form-control-feedback" v-if="errors.model_year" v-text="errors.model_year[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.version}">
                            <label class="control-label">Versión</label>
                            <el-input v-model="form.version"></el-input>
                            <small class="form-control-feedback" v-if="errors.version" v-text="errors.version[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.axes}">
                            <label class="control-label">Ejes</label>
                            <el-input-number v-model="form.axes" :precision="0" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.axes" v-text="errors.axes[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.seating}">
                            <label class="control-label">Asientos</label>
                            <el-input-number v-model="form.seating" :precision="0" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.seating" v-text="errors.seating[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.passengers}">
                            <label class="control-label">Pasajeros</label>
                            <el-input-number v-model="form.passengers" :precision="0" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.passengers" v-text="errors.passengers[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.wheel}">
                            <label class="control-label">Ruedas</label>
                            <el-input-number v-model="form.wheel" :precision="0" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.wheel" v-text="errors.wheel[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.bodywork}">
                            <label class="control-label">Carrocería</label>
                            <el-input v-model="form.bodywork"></el-input>
                            <small class="form-control-feedback" v-if="errors.bodywork" v-text="errors.bodywork[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.power}">
                            <label class="control-label">Potencia</label>
                            <el-input v-model="form.power"></el-input>
                            <small class="form-control-feedback" v-if="errors.power" v-text="errors.power[0]"></small>
                        </div>
                    </div> 

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.cylinders}">
                            <label class="control-label">Cilindros</label>
                            <el-input-number v-model="form.cylinders" :precision="0" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.cylinders" v-text="errors.cylinders[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.displacement}">
                            <label class="control-label">Cilindrada</label>
                            <el-input-number v-model="form.displacement" :precision="3" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.displacement" v-text="errors.displacement[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.gross_weight}">
                            <label class="control-label">Peso bruto</label>
                            <el-input-number v-model="form.gross_weight" :precision="3" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.gross_weight" v-text="errors.gross_weight[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.net_weight}">
                            <label class="control-label">Peso neto</label>
                            <el-input-number v-model="form.net_weight" :precision="3" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.net_weight" v-text="errors.net_weight[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.useful_load}">
                            <label class="control-label">Carga útil</label>
                            <el-input-number v-model="form.useful_load" :precision="3" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.useful_load" v-text="errors.useful_load[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.length}">
                            <label class="control-label">Longitud</label>
                            <el-input-number v-model="form.length" :precision="3" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.length" v-text="errors.length[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.height}">
                            <label class="control-label">Altura</label>
                            <el-input-number v-model="form.height" :precision="3" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.height" v-text="errors.height[0]"></small>
                        </div>
                    </div>  
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.width}">
                            <label class="control-label">Ancho</label>
                            <el-input-number v-model="form.width" :precision="3" :min="0"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.width" v-text="errors.width[0]"></small>
                        </div>
                    </div>  
                </div> 
            </div>
            <div class="form-actions text-right pt-3">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>
 
<script>
 

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'transport/vehicles', 
                errors: {}, 
                form: {}, 
                all_customers: [], 
                customers: [], 
                vehicle_types: [], 
                insurances: [], 
                vehicle_brands: [], 
                colors: [], 
                fuels: [], 
                loading_search:false,
            }
        },
        async created() {
            await this.getTables()
            await this.initForm() 
        },
        methods: {
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
            allCustomers() {
                this.customers = this.all_customers
            },
            async getTables(){
                
                await this.$http.get(`/${this.resource}/tables`).then(response => {
                        this.all_customers = response.data.customers
                        this.vehicle_types = response.data.vehicle_types
                        this.insurances = response.data.insurances
                        this.vehicle_brands = response.data.vehicle_brands
                        this.colors = response.data.colors
                        this.fuels = response.data.fuels
                    })
            },
            initForm() { 
                this.errors = {} 

                this.form = {
                    id: null,
                    license_plate: null,
                    old_license_plate: null,
                    registration_starting: null,
                    title: null,
                    date: moment().format('YYYY-MM-DD'),
                    condition: null,
                    owner_type: null,
                    denomination: null,
                    lastname: null,
                    mother_lastname: null,
                    names: null,
                    acquisition_date: moment().format('YYYY-MM-DD'),
                    expedition_date: moment().format('YYYY-MM-DD'),
                    temporary_validity: null,
                    address: null,
                    category: null,
                    vehicle_brand_id: null,
                    color_id: null,
                    motor: null,
                    fuel_id: null,
                    rolling_form: null,
                    vin: null,
                    serie: null,
                    manufacturing_year: null,
                    model: null,
                    model_year: null,
                    version: null,
                    axes: 0,
                    seating: 0,
                    passengers: 0,
                    wheel: 0,
                    bodywork: null,
                    power: null,
                    cylinders: 0,
                    displacement: 0,
                    gross_weight: 0,
                    net_weight: 0,
                    useful_load: 0,
                    length: 0,
                    height: 0,
                    width: 0,
                    vehicle_type_id: null,
                    insurance_date_of_due: moment().format('YYYY-MM-DD'),
                    insurance_id: null,
                    customer_id: null, 
                }

                this.allCustomers()

            },
            create() {

                this.titleDialog = (this.recordId)? 'Editar vehículo':'Nuevo vehículo'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                            this.form = response.data
                            this.getCustomer(this.form.customer_id)
                        })
                }
            },
            getCustomer(customer_id) { 
                this.$http.get(`/${this.resource}/search/customer/${customer_id}`).then((response) => {
                    this.customers = response.data.customers
                })                  
            },
            submit() {   
 

                this.loading_submit = true  
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data 
                        } else {
                            console.log(error.response)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
                    
            },  
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            }
        }
    }
</script>