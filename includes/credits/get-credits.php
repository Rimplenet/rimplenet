<?php
namespace Credits\GetCredits;

use Credits\Base;

abstract class BaseCredits extends Base
{
    protected function getCredits($id, $type)
    {
        if($credits = $this->CreditsExists($id, $type)):
            $credits = get_post($credits->post_id);
            return $this->response = [
                'status_code' => 200,
                'status' => 'success',
                'response_message' => 'Transaction retrieved',
                'data' => $this->formatCredits($credits)
            ];
        else:
            $this->response['status_code'] = 404;
            $this->response['response_message'] = "Transction not found";
            $this->response['error'][] = 'Invalid Transaction Id '.$id;
        endif;
        return $this->response;
    }

    protected function formatCredits($data)
    {
        $this->id = $data->ID;

        $res = [
            'amount'            => $this->creditsMeta('amount'),
            'balance_after'     => $this->creditsMeta('balance_after'),
            'balance_before'    => $this->creditsMeta('balance_before'),
            'currency'          => $this->creditsMeta('currency'),
            'funds_type'        => $this->creditsMeta('funds_type'),
            'request_id'        => $this->creditsMeta('request_id'),
            'total_balance_after' => $this->creditsMeta('total_balance_after'),
            'total_balance_before' => $this->creditsMeta('total_balance_before'),
            'Credits_request_id'       => $this->creditsMeta('Credits_request_id'),
            'Credits_type'             => $this->creditsMeta('Credits_type'),
            'note'                 => $this->creditsMeta('note'),
            'description'          => $data->post_title
        ];
        return $res;
    }

    private function creditsMeta($field= '', $id = '', $opt = true)
    {
        return get_post_meta($this->id ?? $id, $field, $opt);
    }
}