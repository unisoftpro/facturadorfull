<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%"
               :close-on-click-modal="false"
               :close-on-press-escape="false"
               :show-close="false">

        <div class="row">

            <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold mt-3">
                <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickDownload('format1')">
                    <i class="fa fa-file-alt"></i>
                </button>
                <p>Formato 1 A4</p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold mt-3">
                <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickDownload('format2')">
                    <i class="fa fa-file-alt"></i>
                </button>
                <p>Formato 2 A4</p>
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
        props: ['showDialog', 'recordId', 'showClose', 'type'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'warehouse-income',
                errors: {},
                form: {},
            }
        },
        created() {
            this.initForm()
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
                }
            },
            create() {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                        this.titleDialog = `Ingreso registrado: ` +this.form.number
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
        }
    }
</script>
