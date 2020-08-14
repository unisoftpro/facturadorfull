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
                        <th class="text-center">O. Compra N°</th>
                        <th>Fecha</th>
                        <th>Almacén</th>
                        <th>Factura</th>
                    </tr>
                    <tr></tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.number }}</td>
                        <td class="text-center">{{ row.purchase_order_number }}</td>
                        <td>{{ row.date_of_issue }}</td>
                        <td>{{ row.warehouse_description }}</td>
                        <td>{{ row.invoice_description}}</td>
                    </tr>
                </data-table>
            </div>

        </div>
    </div>
</template>

<script>
import DataTable from "@components/DataTable.vue";
import {deletable} from "@mixins/deletable";

export default {
    components: {DataTable},
    mixins: [deletable],
    data() {
        return {
            title: null,
            showDialog: false,
            resource: 'purchase-order-income',
            recordId: null,
            typeTransaction: null,
        };
    },
    created() {
        this.title = "Ingresos - O. Compra";
    },
    methods: {
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
