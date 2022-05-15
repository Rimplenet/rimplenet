<?php
namespace Txn\GetTxn;

use Txn\Base;

abstract class BaseTxn extends Base
{
    protected function getTxn($id, $type)
    {
        if($txn = $this->txnExists($id, $type)):
            $txn = get_post($txn->post_id);
            return $this->response = [
                'status_code' => 200,
                'status' => 'success',
                'response_message' => 'Transaction retrieved',
                'data' => $this->formatTnx($txn)
            ];
        else:
            $this->response['status_code'] = 404;
            $this->response['response_message'] = "Transction not found";
            $this->response['error'][] = 'Invalid Transaction Id '.$id;
        endif;
        return $this->response;
    }

    protected function formatTnx($data)
    {
        $this->id = $data->ID;

        $res = [
            'amount'            => $this->txnMeta('amount'),
            'balance_after'     => $this->txnMeta('balance_after'),
            'balance_before'    => $this->txnMeta('balance_before'),
            'currency'          => $this->txnMeta('currency'),
            'funds_type'        => $this->txnMeta('funds_type'),
            'request_id'        => $this->txnMeta('request_id'),
            'total_balance_after' => $this->txnMeta('total_balance_after'),
            'total_balance_before' => $this->txnMeta('total_balance_before'),
            'txn_request_id'       => $this->txnMeta('txn_request_id'),
            'txn_type'             => $this->txnMeta('txn_type'),
            'note'                 => $this->txnMeta('note'),
            'description'          => $data->post_title
        ];
        return $res;
    }

    private function txnMeta($field= '', $id = '', $opt = true)
    {
        return get_post_meta($this->id ?? $id, $field, $opt);
    }
}