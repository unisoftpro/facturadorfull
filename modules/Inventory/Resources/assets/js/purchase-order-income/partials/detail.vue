<template>
    <el-dialog :title="titleDialog"   :visible="showDialog"  @open="create"   append-to-body :show-close="false">

        <div class="form-body">
            <div class="row" >
                <div class="col-lg-12 col-md-12">

                    <el-table
                        ref="singleTable"
                        :data="items"
                        border
                        style="width: 100%">

                        <el-table-column
                            property="internal_id"
                            label="Cod Interno"
                            width="100">
                        </el-table-column>

                        <el-table-column
                            property="description"
                            label="Descripción"
                            width="200">
                        </el-table-column>

                        <el-table-column
                            property="quantity"
                            label="Cantidad" >
                        </el-table-column>

                        <el-table-column
                            property="pending_quantity_income"
                            label="Pendiente de ingreso">
                        </el-table-column>

                        <el-table-column
                            label="Cantidad atendida">
                            <template slot-scope="scope">
                                <el-input-number v-model="scope.row.attended_quantity" :min="0" :max="scope.row.pending_quantity_income"></el-input-number>
                            </template>
                        </el-table-column>

                    </el-table>


                </div>

            </div>
        </div>

        <div class="form-actions text-right pt-2">
            <el-button @click.prevent="close()">Cerrar</el-button>
        </div>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'number', 'items'],
        data() {
            return {
                titleDialog: '',
                loading: false,

            }
        },
        async created() {

        },
        methods: {
            create(){
                this.titleDialog = `Detalle O. Compra N°: ${this.number}`
            }, 
            close() {
                this.$emit('update:showDialog', false)
            }, 
        }
    }
</script>
