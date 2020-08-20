<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                </a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>{{ title }}</span>
                </li>
            </ol>
            <div class="right-wrapper pull-right">
                <a href="#" @click.prevent="clickCreate()" class="btn btn-custom btn-sm mt-2 mr-2">
                    <i class="fa fa-plus-circle"></i> Nuevo
                </a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de {{ title }}</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th >#</th>
                        <th class="text-center">Número</th>
                        <th>Fecha emisión</th>
                        <th>Almacén</th>
                        <th>Motivo</th>
                        <th>Fec. Referencia</th>
                        <th>Moneda</th>
                        <th>Total original</th>
                        <th>Total nacional</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr></tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.number }}</td>
                        <td>{{ row.date_of_issue }}</td>
                        <td>{{ row.warehouse_description }}</td>
                        <td>{{ row.warehouse_income_reason_description }}</td>
                        <td>{{ row.reference_date}}</td>
                        <td>{{ row.currency_type_id}}</td>
                        <td>{{ row.original_total}}</td>
                        <td>{{ row.national_total}}</td>
                        <td class="text-right">
                            <button  type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickOptions(row.id)">Opciones</button>
                        </td>

                    </tr>
                </data-table>
            </div>

            <warehouse-income-options :showDialog.sync="showDialogOptions"
                            :recordId="recordId"
                            :showClose="true"></warehouse-income-options>
        </div>
    </div>
</template>

<script>

    import DataTable from "@components/DataTable.vue";
    import {deletable} from "@mixins/deletable";
    import WarehouseIncomeOptions from './partials/options.vue'

    export default {
        components: {DataTable, WarehouseIncomeOptions},
        mixins: [deletable],
        data() {
            return {
                title: null,
                showDialog: false,
                showDialogOptions: false,
                resource: 'warehouse-income',
                recordId: null,
                typeTransaction: null,
            };
        },
        created() {
            this.title = "Ingresos almacén";
        },
        methods: {
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
            clickCreate(recordId = null) {
                location.href = `/${this.resource}/create`;
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit("reloadData")
                );
            },
        },
    };
</script>
