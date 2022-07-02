<?php

class RimplenetDeleteInvestment
{
    public $validation_error = [];

    public function __construct()
    {
        add_shortcode('rimplenet-delete-investment', array($this, 'delete_investment_test'));
    }

    public function delete_investment_test() {
        ob_start();
        var_dump($this->delete_investment([]));
        return ob_get_clean();
    }

    public function delete_investment($investment_id)
    {
        if (!$this->investmentExists($investment_id)) return $this->response(404, "Failed", "Investment not found", [], []);

        wp_delete_post($investment_id);
        return $this->response(200, true, "Investment deleted successfully", [], []);

    }

    public function response($status_code, $status, $message, $data=[], $error=[])
    {
        return [
            "status_code" => $status_code,
            "status" => $status,
            "message" => $message,
            "data" => $data,
            "error" =>$error
        ];
    }

    public function investmentExists($investment_id)
    {
        if (get_post($investment_id)) return true;
  
        return false;
    }
    

}

$RimplenetDeleteInvestment = new RimplenetDeleteInvestment();