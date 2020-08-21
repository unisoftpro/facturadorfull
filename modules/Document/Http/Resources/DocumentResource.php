<?php

namespace Modules\Document\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    { 

        $additional_information = implode('|', $this->additional_information);

        return [
            
            'user_id' => $this->user_id,
            'external_id' => $this->external_id,
            'establishment_id' => $this->establishment_id,
            'establishment' => $this->establishment,
            'soap_type_id' => $this->soap_type_id,
            'state_type_id' => $this->state_type_id,
            'ubl_version' => $this->ubl_version,
            'group_id' => $this->group_id,
            'document_type_id' => $this->document_type_id,
            'series' => $this->series,
            'number' => $this->number,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'time_of_issue' => $this->time_of_issue,
            'customer_id' => $this->customer_id,
            'customer' => $this->customer,
            'currency_type_id' => $this->currency_type_id,
            'purchase_order' => $this->purchase_order,
            'quotation_id' => $this->quotation_id,
            'exchange_rate_sale' => $this->exchange_rate_sale,
            'total_prepayment' => $this->total_prepayment,
            'total_discount' => $this->total_discount,
            'total_charge' => $this->total_charge,
            'total_exportation' => $this->total_exportation,
            'total_free' => $this->total_free,
            'total_taxed' => $this->total_taxed,
            'total_unaffected' => $this->total_unaffected,
            'total_exonerated' => $this->total_exonerated,
            'total_igv' => $this->total_igv,
            'total_base_isc' => $this->total_base_isc,
            'total_isc' => $this->total_isc,
            'total_base_other_taxes' => $this->total_base_other_taxes,
            'total_other_taxes' => $this->total_other_taxes,
            'total_taxes' => $this->total_taxes,
            'total_value' => $this->total_value,
            'total' => $this->total,
            'charges' => $this->charges,
            'discounts' => $this->discounts,
            'prepayments' => $this->prepayments,
            'guides' => $this->guides,
            'related' => $this->related,
            'perception' => $this->perception,
            'detraction' => $this->detraction,
            'legends' => $this->legends,
            'additional_information' => $additional_information == "" ? null : $additional_information,
            'filename' => $this->filename,
            // 'hash' => $this->hash,
            // 'qr' => $this->qr,
            'has_xml' => $this->has_xml,
            'has_pdf' => $this->has_pdf,
            'has_cdr' => $this->has_cdr,
            'has_prepayment' => $this->has_prepayment,
            'affectation_type_prepayment' => $this->affectation_type_prepayment,
            'data_json' => $this->data_json,
            'send_server' => $this->send_server,
            'shipping_status' => $this->shipping_status,
            'sunat_shipping_status' => $this->sunat_shipping_status,
            'query_status' => $this->query_status,
            'total_plastic_bag_taxes' => $this->total_plastic_bag_taxes,
            'sale_note_id' => $this->sale_note_id,
            'success_shipping_status' => $this->success_shipping_status,
            'success_sunat_shipping_status' => $this->success_sunat_shipping_status,
            'success_query_status' => $this->success_query_status,
            'plate_number' => $this->plate_number,
            'total_canceled' => $this->total_canceled,
            'order_note_id' => $this->order_note_id,
            'soap_shipping_response' => $this->soap_shipping_response,
            'pending_amount_prepayment' => $this->pending_amount_prepayment,
            'payment_method_type_id' => $this->payment_method_type_id,

            'items' => $this->items,
            'invoice' => [
                'id' => $this->invoice->id,
                'document_id' => $this->invoice->document_id,
                'date_of_due' => $this->invoice->date_of_due->format('Y-m-d'),
                'operation_type_id' => $this->invoice->operation_type_id,
            ],
            'payments' => $this->payments->transform(function($row, $key){
                return [
                    'id' => $row->id,
                    'document_id' => $row->document_id,
                    'date_of_payment' => $row->date_of_payment->format('Y-m-d'),
                    'payment_method_type_id' => $row->payment_method_type_id,
                    'has_card' => $row->has_card,
                    'card_brand_id' => $row->card_brand_id,
                    'reference' => $row->reference,
                    'change' => $row->change,
                    'payment' => $row->payment,
                    'payment_destination_id' => ($row->global_payment) ? ($row->global_payment->type_record == 'cash' ? 'cash':$row->global_payment->destination_id):null,
                ];
            }),

        ];
    }
}
