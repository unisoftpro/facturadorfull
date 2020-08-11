<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Órdenes de trabajo</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a href="#" @click.prevent="clickCreate()" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0"> 
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">O. Trabajo</th>
                        <th class="text-center">Responsable</th>
                        <th>Cliente</th>
                        <th>Fecha apertura</th>
                        <th>Estado</th>
                        <th class="text-center">Proceso</th> 
                        <th class="text-center">Descarga</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.number_full }}</td>
                        <td>{{ row.user_name }}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td>{{ row.opening_date }}</td>
                        <td>{{ row.work_order_state_description }}</td>
                        <td>{{ row.process_description }}</td>
                      
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.external_id)">PDF</button> 
                        </td>   

                        <td class="text-right">

                            <button v-if="row.state_type_id != '11'" type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickVoided(row.id)">Anular</button>

                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickCreate(row.id)" v-if="row.btn_generate && row.state_type_id != '11'">Editar</button>

                            <button  v-if="row.state_type_id != '11'"  type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickOptions(row.id)">Opciones</button>
                          
                        </td>


                    </tr>
                </data-table>
            </div>
        </div>
 

        <work-order-options :showDialog.sync="showDialogOptions"
                          :recordId="saleNotesNewId"
                          :showClose="true"></work-order-options>
 

    </div>
</template>

<script>

    import DataTable from '../../components/DataTable.vue'
    import WorkOrderOptions from './partials/options.vue'
    import {deletable} from '@mixins/deletable'

    export default {
        mixins: [deletable],
        components: {DataTable, WorkOrderOptions},
        data() {
            return {
                resource: 'transport/work-orders',
                showDialogOptions: false,
                saleNotesNewId: null,
                recordId: null,
            }
        },
        created() {
        }, 
        methods: {
            clickDownload(external_id) {
                window.open(`/sale-notes/downloadExternal/${external_id}`, '_blank');
            },
            clickOptions(recordId) {
                this.saleNotesNewId = recordId
                this.showDialogOptions = true
            }, 
            clickCreate(id = '') {
                location.href = `/${this.resource}/create/${id}`
            },
            clickVoided(id) {
                 this.anular(`/${this.resource}/anulate/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },

        }
    }
</script>
