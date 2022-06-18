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

    $prop = empty($req) ? $this->req : $req;
    extract($prop);

   
  }

  public function getWithdrawal()
  {
    # code...
  }


}
