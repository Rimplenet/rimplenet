<?php

use ResponseUtil as Res;
class RimplenetStatistics extends BaseStatistics
{
    use Traits\Wallet\RimplenetWalletTrait;

    public $req = [];
    public $wallet_id = "";
    public $error_message = [
        'error_message' => 'Meta Key Error',
        'suggestion' => 'Enter Valid Meta key'
    ];


    public function query($params = null)
    {
        extract($this->req ?? $params);
        $this->wallet_id = $wallet_id;
        $this->date = $this->setDashtoUnderScore($date);

        switch ($entity_type) {
            case 'user':
                return (!is_null($entity_id) && !empty($entity_id)) ? $this->queryUser($entity_id, $meta_key) : Res::error('Entity Error', 'Please enter a valid user id');
                break;
            case 'sitewide':
                return $this->querySiteWide($meta_key);
                break;
            
            default:
                return Res::error($this->error_message, "$entity_type is not a valid entity type");
                break;
        }
            
    }

    public function queryUser($user_id, $meta_key)
    {
        switch ($this->setDashtoUnderScore($meta_key)) {
            case 'total_debit':
                return Res::success($this->userTotalDebit($user_id),  'Data Retreived Successfully');
                break;

            case 'total_credit':
                return Res::success($this->userTotalCredit($user_id), 'Data Retreived Successfully');
                break;

            case 'highest_amount':
                return Res::success($this->userHighestAmount($user_id),  'Data Retreived Successfully');
                break;

            case 'lowest_amount':
                return Res::success($this->userLowestAmount($user_id),  'Data Retreived Successfully');
                break;

            case 'count_credit':
                return Res::success($this->userCountCredit($user_id),  'Data Retreived Successfully');
                break;

            case 'count_debit':
                return Res::success($this->userCountDebit($user_id), 'Data Retreived Successfully');
                break;

            default:
                return Res::error($this->error_message, "$meta_key is not a valid meta key");
                break;
        }
    }

    public function querySiteWide($meta_key)
    {
        switch ($this->setDashtoUnderScore($meta_key)) {
            case 'total_debit':
                return Res::success($this->sitewideTotalDebit(), 'Data Retreived Successfully');
                break;
            case 'total_credit':
                return Res::success($this->sitewideTotalCredit(), 'Data Retreived Successfully');
                break;

            case 'highest_amount':
                return Res::success($this->sitewideHighestAmount(), 'Data Retreived Successfully');
                break;
            case 'lowest_amount':
                return Res::success($this->sitewideLowestAmount(), 'Data Retreived Successfully');
                break;

            case 'count_credit':
                return Res::success($this->sitewideCountDebit(), 'Data Retreived Successfully');
                break;
            case 'count_debit':
                return Res::success($this->sitewideCountCredit(), 'Data Retreived Successfully');
                break;

            default:
                return Res::error($this->error_message, "$meta_key is not a valid meta key");
                break;
        }
    }





    public function userHighestAmount($user_id)
    {
        return $this->setPrefix('rimplenet_highest_amount_at')
                    ->setwalletId($this->wallet_id)
                    ->setuserId($user_id)
                    ->userqueryBuilder();
    }
    public function userLowestAmount($user_id)
    {
        return $this->setPrefix('rimplenet_lowest_amount_at')
                    ->setwalletId($this->wallet_id)
                    ->setuserId($user_id)
                    ->userqueryBuilder();
    }

    public function userTotalCredit($user_id)
    {
        return $this->setPrefix('rimplenet_total_credit_at')
                    ->setwalletId($this->wallet_id)
                    ->setuserId($user_id)
                    ->userqueryBuilder();
    }

    public function userTotalDebit($user_id)
    {
        return $this->setPrefix('rimplenet_total_debit_at')
                    ->setwalletId($this->wallet_id)
                    ->setuserId($user_id)
                    ->userqueryBuilder();
    }

    public function userCountDebit($user_id)
    {
        return $this->setPrefix('rimplenet_total_count_debit_at')
                    ->setwalletId($this->wallet_id)
                    ->setuserId($user_id)
                    ->userqueryBuilder();
    }

    public function userCountCredit($user_id)
    {
        return $this->setPrefix('rimplenet_total_count_credit_at')
                    ->setwalletId($this->wallet_id)
                    ->setuserId($user_id)
                    ->userqueryBuilder();
    }

    public function sitewideHighestAmount()
    {
        return $this->setPrefix('rimplenet_highest_amount_at')
                    ->setwalletId($this->wallet_id)
                    ->siteWideQueryBuilder();
    }

    public function sitewideLowestAmount()
    {
        return $this->setPrefix('rimplenet_lowest_amount_at')
                    ->setwalletId($this->wallet_id)
                    ->siteWideQueryBuilder();
    }

    public function sitewideTotalCredit()
    {
        return $this->setPrefix('rimplenet_total_credit_at')
                    ->setwalletId($this->wallet_id)
                    ->siteWideQueryBuilder();
    }

    public function sitewideTotalDebit()
    {
        return $this->setPrefix('rimplenet_total_debit_at')
                    ->setwalletId($this->wallet_id)
                    ->siteWideQueryBuilder();
    }

    public function sitewideCountDebit()
    {
        return $this->setPrefix('rimplenet_total_count_debit_at')
                    ->setwalletId($this->wallet_id)
                    ->siteWideQueryBuilder();
    }

    public function sitewideCountCredit()
    {
        return $this->setPrefix('rimplenet_total_count_credit_at')                    
                    ->setwalletId($this->wallet_id)
                    ->siteWideQueryBuilder();
    }

    public function setEntityTypeError(Type $var = null)
    {
        $this->error_message['error_message'] = 'Entity Error';
        $this->error_message['suggestion'] = 'Please enter a valid entity type';
    }

    public function setDashtoUnderScore($value)
    {
        return str_replace('-', '_', $value);
    }
}
