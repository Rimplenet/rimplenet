<?php

    $subject = 'Credit Alert - '.$data['symbol'].$data["amount_formatted"];
    // $message = 'Your account has been credited with '.$amount.' Please visit Middey for more enquiries';
    $message = "
                <div style='text-align:center; padding: 9%; align-self:center;'>
                    
                    <h2>Credit Alert - ".$data['symbol'].$data['amount_formatted']."</h2>
                    Your account has been credited with the sum 
                    of <strong>".$data['symbol'].$data['amount_formatted']."</strong>
                    on transaction <strong>#".$data['transaction_id']."</strong> with 
                    description: ".$data['note'].". 
                    Your new balance is <strong>".$data['symbol'].number_format($data['balance_after'], $data['decimal'])."</strong>
                    Please visit our website for more enquiries and If this is not you,
                    do not hesitate to change your password and contact us immediately. <br>
  
                    
  
                    <p style='margin-top:3%'>Thank you for choosing us <strong>&copy; <script>document.write(new Date().getFullYear())</script></strong></p>
    
                </div>";

                // <div style='width:100%; margin-top:15px; margin-bottom:15px;'>
                //         <a href='".get_site_url()."' style='padding: 200px; padding-top:15px; padding-bottom: 15px; background-color: black; color:white; margin-top: 20px;  border-radius: 5px; cursor:pointer;'>Go to Dashboard</a>
                //     </div>

                // <img src='https://app.middey.com/img/middey-logowhitebg.7893c61c.png' width='200' height='150' style='max-width: 400px; text-align: center'> <br>
                
    // $headers = 'just a test';

    $headers = array('Content-Type: text/html; charset=UTF-8');