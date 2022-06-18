<?php
// namespace Debits\GetDebits;

use Debits\Base;

class RimplenetGetDebits extends Base
{
    public function getDebits($id, $type)
    {
        if($Debits = $this->debitsExists($id, $type)):
            $Debits = get_post($Debits->post_id);
            return $this->response = [
                'status_code' => 200,
                'status' => 'success',
                'response_message' => 'Transaction retrieved',
                'data' => $this->formatDebits($Debits)
            ];
        else:
            $this->response['status_code'] = 404;
            $this->response['response_message'] = "Transction not found";
            $this->response['error'][] = 'Invalid Transaction Id '.$id;
        endif;
        return $this->response;
    }

    protected function formatDebits($data)
    {
        $this->id = $data->ID;

        $res = [
            'amount'            => $this->debitsMeta('amount'),
            'balance_after'     => $this->debitsMeta('balance_after'),
            'balance_before'    => $this->debitsMeta('balance_before'),
            'currency'          => $this->debitsMeta('currency'),
            'funds_type'        => $this->debitsMeta('funds_type'),
            'request_id'        => $this->debitsMeta('request_id'),
            'total_balance_after' => $this->debitsMeta('total_balance_after'),
            'total_balance_before' => $this->debitsMeta('total_balance_before'),
            'Debits_request_id'       => $this->debitsMeta('Debits_request_id'),
            'Debits_type'             => $this->debitsMeta('Debits_type'),
            'note'                 => $this->debitsMeta('note'),
            'description'          => $data->post_title
        ];
        return $res;
    }

    private function debitsMeta($field= '', $id = '', $opt = true)
    {
        return get_post_meta($this->id ?? $id, $field, $opt);
    }
}