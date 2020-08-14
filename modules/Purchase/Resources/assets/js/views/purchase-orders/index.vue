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
                    <span>Ordenes de compra</span>
                </li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm mt-2 mr-2">
                    <i class="fa fa-plus-circle"></i> Nuevo
                </a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">F. Emisión</th>
                        <th class="text-center">F. Vencimiento</th>
                        <th>Proveedor</th>
                        <!-- <th>Estado</th> -->
                        <th>O. Compra</th>
                        <th>O. Venta</th>
                        <th>Detalle Productos</th>
                        <!-- <th>F. Pago</th> -->
                        <th class="text-center">Moneda</th>
                        <!-- <th class="text-right">T.Gratuita</th>
            <th class="text-right">T.Inafecta</th>
                        <th class="text-right">T.Exonerado</th>-->
                        <th class="text-right">T.Gravado</th>
                        <th class="text-right">T.Igv</th>
                        <!-- <th>Percepcion</th> -->
                        <th class="text-right">Total</th>
                        <th class="text-center">Descarga</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr></tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td class="text-center">{{ row.date_of_due }}</td>
                        <td>
                            {{ row.supplier_name }}
                            <br />
                            <small v-text="row.supplier_number"></small>
                        </td>
                        <!-- <td>{{row.state_type_description}}</td> -->
                        <td>
                            {{ row.number }}
                            <br />
                            <small v-text="row.document_type_description"></small>
                            <br />
                        </td>
                        <td>{{row.sale_opportunity_number_full}}</td>

                        <td> 

                          <el-popover placement="right" width="400" trigger="click">
                            <el-table :data="row.items">
                              <el-table-column width="260" property="description" label="Producto"></el-table-column>
                              <el-table-column width="100" property="quantity" label="Cantidad"></el-table-column>
                              <el-table-column width="160" property="attended_quantity" label="Cantidad atendida"></el-table-column>
                            </el-table>
                            <el-button slot="reference" icon="el-icon-zoom-in"></el-button>
                          </el-popover>

                        </td>

                        <!-- <td>{{ row.payment_method_type_description }}</td> -->
                        <!-- <td>{{ row.state_type_description }}</td> -->
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <!-- <td class="text-right">{{ row.total_exportation }}</td> -->
                        <!-- <td class="text-right">{{ row.total_free }}</td>
            <td class="text-right">{{ row.total_unaffected }}</td>
                        <td class="text-right">{{ row.total_exonerated }}</td>-->
                        <td class="text-right">{{ row.total_taxed }}</td>
                        <td class="text-right">{{ row.total_igv }}</td>
                        <!-- <td class="text-right">{{ row.total_perception ? row.total_perception : 0 }}</td> -->
                        <td class="text-right">{{ row.total }}</td>

                        <td class="text-center">
                            <button
                                type="button"
                                class="btn waves-effect waves-light btn-xs btn-info"
                                @click.prevent="clickDownload(row.external_id)"
                            >PDF</button>
                        </td>

                        <td class="text-right"> 
                            <template v-if="row.purchase_order_state_id != '13'">
                                <button
                                    type="button"
                                    v-if="!row.has_purchases && row.state_type_id!='11'"
                                    class="btn waves-effect waves-light btn-xs btn-custom m-1__2"
                                    @click.prevent="clickCreate(row.id)"
                                >Editar</button>

                                <!-- <button type="button" v-if="!row.has_purchases && row.state_type_id!='11'" class="btn waves-effect waves-light btn-xs btn-success m-1__2"
                                @click.prevent="clickGenerateDocument(row.id)">Generar compra</button>-->

                                <a
                                    :href="`/purchases/create/${row.id}`"
                                    class="btn waves-effect waves-light btn-xs btn-success m-1__2"
                                    v-if="!row.has_purchases && row.state_type_id!='11'"
                                >Generar compra</a>

                                <button
                                    type="button"
                                    v-if="!row.has_purchases && row.state_type_id!='11'"
                                    class="btn waves-effect waves-light btn-xs btn-danger m-1__2"
                                    @click.prevent="clickAnulate(row.id)"
                                >Anular</button>
                            </template>

                            <button
                                type="button"
                                class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                @click.prevent="clickOptions(row.id)"
                            >Opciones</button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <!-- <documents-voided :showDialog.sync="showDialogVoided"
            :recordId="recordId"></documents-voided>-->

            <!-- <document-generate
        :showDialog.sync="showDialogGenerateDocument"
        :recordId="recordId"
        :showClose="true"
            ></document-generate>-->

            <purchase-options
                :showDialog.sync="showDialogOptions"
                :recordId="recordId"
                :showClose="true"
            ></purchase-options>
        </div>
    </div>
</template>

<script>
// import DocumentGenerate from "./partials/document_generate.vue";
// import DocumentOptions from './partials/document_options.vue'
import DataTable from "../../../../../../../resources/js/components/DataTable.vue";
import PurchaseOptions from "./partials/options.vue";

import { deletable } from "@mixins/deletable";

export default {
    mixins: [deletable],
    // components: {DocumentsVoided, DocumentOptions, DataTable},
    components: { DataTable, PurchaseOptions }, //DocumentOptions
    data() {
        return {
            showDialogVoided: false,
            resource: "purchase-orders",
            recordId: null,
            showDialogOptions: false,
            showDialogGenerateDocument: false,
        };
    },
    created() {},
    methods: {
        clickCreate(id = "") {
            location.href = `/${this.resource}/create/${id}`;
        },
        clickVoided(recordId = null) {
            this.recordId = recordId;
            this.showDialogVoided = true;
        },
        clickDownload(external_id) {
            window.open(`/${this.resource}/download/${external_id}`, "_blank");
        },
        clickGenerateDocument(recordId) {
            this.recordId = recordId;
            this.showDialogGenerateDocument = true;
        },
        clickAnulate(id) {
            this.anular(`/${this.resource}/anular/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickOptions(recordId = null) {
            this.recordId = recordId;
            this.showDialogOptions = true;
        },
    },
};
</script>
