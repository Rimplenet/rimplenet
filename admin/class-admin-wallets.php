<?php

class Rimplenet_Admin_Wallets
{
    public $admin_post_page_type, $viewed_url, $post_id;

    public function __construct()
    {

        $this->viewed_url = $_SERVER['REQUEST_URI'];
        $this->admin_post_page_type = sanitize_text_field($_GET["rimplenettransaction_type"] ?? '');
        $this->post_id = sanitize_text_field($_GET['post'] ?? '');

        $wallet_id = get_post_meta($this->post_id, 'rimplenet_wallet_id', true);
        if (empty($this->post_id) or !empty($wallet_id)) {
            add_action('init',  array($this, 'required_admin_functions_loaded'));
            //save meta value with save post hook when Template Settings is POSTED
            add_action('save_post_rimplenettransaction',  array($this, 'save_wallet_settings'), 10, 3);
        }
    }

    function required_admin_functions_loaded()
    {
        if (empty($this->admin_post_page_type) or has_term('rimplenet-wallets', 'rimplenettransaction_type', $this->post_id)) {

            //Register Rimplenet Template Settings Meta Box
            add_action('add_meta_boxes',  array($this, 'rimplenet_template_register_meta_box'));
        }
    }

    function rimplenet_template_register_meta_box()
    {

        add_meta_box('rimplenet-admin-wallet-settings-meta-box', esc_html__('Wallet Settings', 'rimplenet'),   array($this, 'rimplenet_admin_wallet_meta_box_callback'), 'rimplenettransaction', 'normal', 'high');
        add_meta_box('rimplenet-admin-wallet-balance-shortcode-meta-box', esc_html__('Wallet Balance Shortcode', 'rimplenet'),   array($this, 'rimplenet_admin_wallet_balance_shortcode_meta_box_callback'), 'rimplenettransaction', 'side', 'high');
    }

    function rimplenet_admin_wallet_meta_box_callback($meta_id)
    {

        include_once plugin_dir_path(dirname(__FILE__)) . '/admin/partials/metabox-admin-wallet-settings.php';
    }

    function rimplenet_admin_wallet_balance_shortcode_meta_box_callback($meta_id)
    {

        $wallet_post_id = $meta_id->ID;
        $wallet_id = get_post_meta($wallet_post_id, 'rimplenet_wallet_id', true);
        $user_balance_shortcode  = '[rimplenet-wallet action="view_balance" wallet_id="' . $wallet_id . '"]';
        if (!empty($this->post_id) and has_term('rimplenet-wallets', 'rimplenettransaction_type', $this->post_id)) {
            echo "<p style='color:red;'><code class='rimplenet_click_to_copy'>$user_balance_shortcode</code></p>";
        } else {
            echo "<p style='color:red;'>Wallet Shortcode for displaying user balance will appear here after publish</p>";
        }
    }

    function save_wallet_settings($post_id, $post, $update)
    {

        $rimplenettransaction_type = sanitize_text_field($_POST['rimplenettransaction_type']);
        $rimplenet_wallet_id = sanitize_text_field($_POST['rimplenet_wallet_id']);
        if (!empty($rimplenet_wallet_id) or $rimplenettransaction_type == "rimplenet-wallets") {
            $WALLET_CAT_NAME = 'RIMPLENET WALLETS';
            wp_set_object_terms($post_id, $WALLET_CAT_NAME, 'rimplenettransaction_type');

            $rimplenet_wallet_name = get_the_title();
            $rimplenet_wallet_decimal = sanitize_text_field($_POST['rimplenet_wallet_decimal']);
            $rimplenet_min_withdrawal_amount = sanitize_text_field($_POST['rimplenet_min_withdrawal_amount']);
            $rimplenet_max_withdrawal_amount = sanitize_text_field($_POST['rimplenet_max_withdrawal_amount']);
            $rimplenet_wallet_symbol = sanitize_text_field($_POST['rimplenet_wallet_symbol']);
            $rimplenet_wallet_symbol_position = sanitize_text_field($_POST['rimplenet_wallet_symbol_position']);
            $include_in_withdrawal_form = sanitize_text_field($_POST['include_in_withdrawal_form']);
            $include_in_woocommerce_currency_list = sanitize_text_field($_POST['include_in_woocommerce_currency_list']);
            $enable_as_woocommerce_product_payment_wallet = sanitize_text_field($_POST['enable_as_woocommerce_product_payment_wallet']);
            $rimplenet_wallet_id = sanitize_text_field($_POST['rimplenet_wallet_id']);
            $rimplenet_wallet_type = sanitize_text_field($_POST['rimplenet_wallet_type']);
            $rimplenet_wallet_note = sanitize_text_field($_POST['rimplenet_wallet_note']);


            $metas = array(
                'rimplenet_wallet_name' => $rimplenet_wallet_name,
                'rimplenet_wallet_decimal' => $rimplenet_wallet_decimal,
                'rimplenet_min_withdrawal_amount' => $rimplenet_min_withdrawal_amount,
                'rimplenet_max_withdrawal_amount' => $rimplenet_max_withdrawal_amount,
                'rimplenet_wallet_symbol' => $rimplenet_wallet_symbol,
                'rimplenet_wallet_symbol_position' => $rimplenet_wallet_symbol_position,
                'rimplenet_wallet_id' => strtolower($rimplenet_wallet_id),
                'include_in_woocommerce_currency_list' => $include_in_woocommerce_currency_list,
                'enable_as_woocommerce_product_payment_wallet' => $enable_as_woocommerce_product_payment_wallet,
                'rimplenet_wallet_type' => $rimplenet_wallet_type,
                'rimplenet_wallet_note' => $rimplenet_wallet_note,

                'rimplenettransaction_type' => 'rimplenet-wallets',

                'rimplenet_rules_before_wallet_withdrawal' => $rimplenet_rules_before_wallet_withdrawal,
                'rimplenet_rules_after_wallet_withdrawal' => $rimplenet_rules_after_wallet_withdrawal,
            );

            foreach ($metas as $key => $value) {
                update_post_meta($post_id, $key, $value);
            }
        }
    }
}


$Rimplenet_Admin_Wallets = new Rimplenet_Admin_Wallets();
