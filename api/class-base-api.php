<?php

/**
 * The api-facing functionality of the plugin.
 *
 * @link       https://rimplenet.com/
 * @since      1.0.0
 *
 * @package    Rimplenet
 * @subpackage Rimplenet/api
 */
/**
 * The api-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * @package    Rimplenet
 * @subpackage Rimplenet/api
 * @author     Rimplenet <info@rimplenet.com>
 */
class Rimplenet_Api
{

	public function __construct()
	{
		$this->load_required_files();
	}

	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/api-keys/class-base-api-keys.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/mlm-matrix/class-base-mlm-matrix.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/wallets/class-base-wallets.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/transfers/class-base-transfers.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/debits/class-base-debits.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/credits/class-base-credits.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/user-wallet-balance/class-base-user-wallet-balance.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/users/class-base-users.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/auth/class-base-auth.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/referrals/class-base-referral.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/transactions/class-base-transactions.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/withdrawals/class-base-withdrawals.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/investments/class-base-investments.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'api/core/emails/class-base-emails.php';
	}
}


$Rimplenet_Api = new Rimplenet_Api();
