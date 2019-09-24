<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="#"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Importador ventas</span> </li>
            </ol> 
            <div class="right-wrapper pull-right">
                <span>
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button>
                </span>
            </div>
        </div>
        <div class="card mb-0"> 
           <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Vendedor</th>
                        <th>Fecha y Hora subida</th>
                        <th class="text-center">Número de documentos</th> 
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.user_name }}</td> 
                        <td class="">{{ row.created_at }}</td>  
                        <td class="text-center">{{ row.quantity_documents }}</td>  
                        
                    </tr>
                </data-table>
            </div>
        <documents-import :showDialog.sync="showImportDialog"></documents-import>

        </div>
    </div>
</template>
 

<script>
 
    import DataTable from '../../../../../../../resources/js/components/DataTable.vue'
    import DocumentsImport from './import.vue'

    export default { 
        components: {DataTable,DocumentsImport},

        data() {
            return {
                resource: 'imports',                 
                form: {}, 
                showImportDialog: false,

            }
        },
        async created() { 
        },
        methods: { 
             
            clickImport() {
                this.showImportDialog = true
            }
            
        }
    }
</script>