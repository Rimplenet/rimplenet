<?php

class Utils
{

    /**
     * @var array
     */
    public static $error;

    /**
     * @var string
     */
    const TAXONOMY   = 'rimplenettransaction_type';
    const POST_TYPE  = 'rimplenettransaction';
    const MIN_AMOUNT = 0;
    const MAX_AMOUNT = 999999999;
    const WALLETS = 'WALLETS';
    const DEBIT = 'DEBIT';
    const CREDIT = 'CREDIT';
    const TRANSFERS = 'TRANSFERS';
    const LIMIT = 100;

    // public function __construct(mixed $var = '')
    public function __construct($var = "")
    {
        $this->var = $var;
    }

    /**
     * @var array
     */
    public static $response = [
        'status_code' => 400,
        'status' => false,
        'message' => ''
    ];

    // public static $error = [
    //     'status_code' => 400,
    //     'status' => false,
    //     'message' => ''
    // ];

    public $query = null;
    /**
     * Check empty Fields
     * @return mixed
     */
    public function checkEmpty(array $req = [])
    {
        // return "hekko";
        $prop = empty($req) ? $this->req : $req;


        foreach ($prop as $key => $value) :

            if ($key == 'r_a_b_w' || $key == 'r_b_b_w' || $key == 'e_a_w_p' || $key == 'min_withdrawal_amount' || $key == 'max_withdrawal_amount' || $key == 'inc_i_w_cl' || $key == 'wallet_symbol_pos' || $key == 'note' || $key == 'app_id') continue;

            if (is_bool($value) && !$value || is_bool($value) && $value) continue;

            if ($value == '')
                self::$error[$key] = 'Field Cannot be empty';
        endforeach;

        if (!empty(self::$error)) {
            Res::error(self::$error, "one or more field is required", 400);
            return true;
            exit;
        }
        return false;
    }

    public static function requires(array $params = [])
    {
        foreach ($params as $key => $value) {
           $exploded = explode('||', $value);
           $val = $exploded[0] ?? '';
           $type = $exploded[1] ?? '';

            $type = trim($type); $val = trim($val);
            if ($val !== '') {
                if ($type == 'int') {
                    if ((int) $val == 0) self::$error[$key] = "$key cannot be zero";
                    if (!preg_match('/^\d+$/', $val)) self::$error[$key] = "$key requires an integer";
                } elseif ($type == 'amount') {
                    if (!is_numeric($val)) self::$error[$key] = "$key requires a valid amount";
                } elseif ($type == 'string') {
                    if (!preg_match('/^([a-zA-Z_ ])+$/', $val)) self::$error[$key] = $key . ' requires only alphabets';
                } elseif ($type == 'alnum') {
                    if (!preg_match('/^[a-zA-Z0-9_]+$/', $val)) self::$error[$key] = $key . ' can contain aplhpabets, numbers and underscore';
                }elseif($type == 'bool'){
                    if (!is_bool($type))  self::$error[$key] = $key . ' Must be a boolean';
                }elseif($type == 'strInt'){
                    if (!preg_match('/\w+/', $val))  self::$error[$key] = $key . ' Invalid Chars';
                }
            } else {
                self::$error[$key] = 'Field Cannot be empty';
            }
        }

        if (!empty(self::$error)) {
            Res::error(self::$error, "invalid input", 400);
            return true;
        }
        return false;
    }

    public function getWalletById(string $walletId)
    {
        global $wpdb;
        $walletId = sanitize_text_field($walletId);
        $wallet = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='rimplenet_wallet_id' AND meta_value='$walletId' OR post_id = '$walletId' AND meta_key='rimplenet_wallet_id'");

        if ($wallet) :
            return $wallet;
        else :
            Res::error(["Invalid wallet Id"], "Wallet not found", 404);
            return false;
        endif;
    }

    /**
     * Check if wallet already exists
     */
    public function walletExists()
    {
        global $wpdb;

        $exists = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='rimplenet_wallet_id' AND meta_value='$this->wallet_id' OR post_id = '$this->wallet_id' AND meta_key='rimplenet_wallet_id'");

        if ($exists)
            self::$error[] = 'Wallet Already Exists';
        if (!empty(self::$error)) return true;
        else return false;
    }



    protected function queryDb($page)
    {

        $this->query = new WP_Query([
            'post_type' => self::POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => 100,
            'paged' => $page,
            'tax_query' => array([
                'taxonomy' => self::TAXONOMY,
                'field'    => 'name',
                'terms'    => static::WALLETS,
            ]),
        ]);
    }

    public function queryTxn($page, $type = self::CREDIT)
    {
        $this->query = new WP_Query(
            array(
                'post_type' => 'rimplenettransaction',
                'post_status' => 'any',
                'author' => 'any',
                'posts_per_page' => -1,
                'paged' => 1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'rimplenettransaction_type',
                        'field'    => 'name',
                        'terms'    =>  $type
                    ),
                ),
            )
        );
    }

    protected function postMeta($field = '')
    {
        return get_post_meta($this->id, $field, true);
    }
}
