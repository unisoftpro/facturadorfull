<template>
    <div>
        <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%"
                :close-on-click-modal="false"
                :close-on-press-escape="false"
                :show-close="false">  
            <div class="row"> 
                <div class="col-lg-12 ">
                    <div class="form-group" :class="{'has-danger': errors.dipatch_id}"> 
                        <label class="control-label">Generar Guía Remisión</label>
                        <!-- <el-checkbox  v-model="generate_dispatch">Generar Guía Remisión</el-checkbox> -->
                        <el-select v-model="form.dispatch_id" popper-class="el-select-document_type" filterable  class="border-left rounded-left border-info" >
                            <el-option v-for="option in dispatches" :key="option.id" :value="option.id" :label="option.number_full"></el-option>
                        </el-select>
                        <small class="form-control-feedback" v-if="errors.dipatch_id" v-text="errors.dipatch_id[0]"></small>
                    </div>
                </div>
            </div>
            <span slot="footer" class="dialog-footer"> 
                <el-button @click="clickClose">Cerrar</el-button>         
                <el-button class="submit" type="primary" @click="submit" :loading="loading_submit" v-if="form.dispatch_id">Generar</el-button>
            </span>
 

        </el-dialog>
    </div>
</template>

<script>

    import DocumentOptions from '../../documents/partials/options.vue'

    export default {
        components: {DocumentOptions},

        props: ['showDialog', 'recordId'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'documents',
                errors: {},
                form: {},
                document:{},
                generate:false,
                loading_submit:false,
                flag_generate:true,
                dispatches: [],
            }
        },
        created() {
            this.initForm()

           // console.log(moment().format('YYYY-MM-DD'))
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    external_id: null,
                    number: null,
                    date_of_issue:null,
                    dispatch_id:null,
                }
            }, 
            async submit() {
                
                if(!this.form.dispatch_id){
                    return this.$message.error('Debe seleccionar una guía base')
                } 

                window.open(`/dispatches/create/${this.form.id}/i/${this.form.dispatch_id}`)

            }, 
            async create() {
 
                await this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                        this.titleDialog = 'Comprobante: '+this.form.number
                    })

                    
                await this.$http.get(`/${this.resource}/dispatches`)
                    .then(response => {
                        this.dispatches = response.data 
                    })
            }, 
            clickFinalize() {
                location.href = `/${this.resource}`
            }, 
            clickClose() {
                this.$emit('update:showDialog', false)
                this.initForm()
            } 
        }
    }
</script>
