<?php

// namespace Debits\CreateDebits;

// use Rimplenet_Wallets;
use Withdrawals\Base;
use Traits\Wallet\RimplenetWalletTrait;

class RimplenetGetWithdrawals extends Base
{

  use RimplenetWalletTrait;

  public function getWithdrawals(array $req = [])
  {
    self::$response['data']=$this->fetchWithdrawals();
    return self::$response['data'];
  }

  public function getWithdrawal()
  {
    # code...
  }


  public function formatData(Type $var = null)
  {
    # code...
  }

}
