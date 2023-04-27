<?php

$website_url = parse_url(get_bloginfo('url'));
$domain_name = trim($website_url['host'] ? $website_url['host'] : array_shift(explode('/', $website_url['path'], 2)));

$blogurl = get_bloginfo('url');

$blogname = get_bloginfo('name');
$blogname_uppercase = strtoupper($blogname);

$user_info = get_user_by('id', $user_id);
         $user_email = $user_info->user_email;
         
         $username = $user_info->user_login;
         $username_uppercase = strtoupper($username);
         
         $tnx_type_uppercase = strtoupper($tnx_type);

    $subject = 'Credit ~ '.$data['symbol'].$data["amount_formatted"].''.$blogname;
    // $message = 'Your account has been credited with '.$amount.' Please visit Middey for more enquiries';
    // $message = "
    //             <div style='text-align:center; padding: 9%; align-self:center;'>
                    
    //                 <h2>Credit Alert - ".$data['symbol'].$data['amount_formatted']."</h2>
    //                 Your account has been credited with the sum 
    //                 of <strong>".$data['symbol'].$data['amount_formatted']."</strong>
    //                 on transaction <strong>#".$data['transaction_id']."</strong> with 
    //                 description: ".$data['note'].". 
    //                 Your new balance is <strong>".$data['symbol'].number_format($data['balance_after'], $data['decimal'])."</strong>
    //                 Please visit Middey for more enquiries and If this is not you,
    //                 do not hesitate to change your password and contact us immediately. <br>
  
                    
  
    //                 <p style='margin-top:3%'>Thank you for choosing us <strong>&copy; <script>document.write(new Date().getFullYear())</script></strong></p>
    
    //             </div>";

                // <div style='width:100%; margin-top:15px; margin-bottom:15px;'>
                //         <a href='".get_site_url()."' style='padding: 200px; padding-top:15px; padding-bottom: 15px; background-color: black; color:white; margin-top: 20px;  border-radius: 5px; cursor:pointer;'>Go to Dashboard</a>
                //     </div>

                // <img src='https://app.middey.com/img/middey-logowhitebg.7893c61c.png' width='200' height='150' style='max-width: 400px; text-align: center'> <br>
                
    // $headers = 'just a test';

    $message = "<style>
    table,td {
      border: 1px solid white;
      border-collapse: collapse;
      
      
    }
    
</style>
<div style='background-color:#fff'>

<table style='width:100%'>
  <tr>
    <th colspan='2' style='background: #ccc;padding: 15px;'>
       $blogname_uppercase : A NEW TRANSACTION HAS OCCURED ON YOUR ACCOUNT ~ @{$username}
    </th>
  </tr>

  <tr>
    <td style='background-color:#ccc; padding: 20px; text-align: left;'><strong>TRANSACTION TYPE</strong></td>
    <td style='background-color:#eee; padding: 20px; text-align: left;'> $tnx_type_uppercase </td>
  </tr>
  
  <tr>
    <td style='background-color:#ddd; padding: 20px; text-align: left;'><strong>TRANSACTION AMOUNT</strong></td>
    <td style='background-color:#eee; padding: 20px; text-align: left;'> $amount_formatted </td>
  </tr>
  
  <tr>
    <td style='background-color:#ccc; padding: 20px; text-align: left;'><strong>TRANSACTION NOTE</strong></td>
    <td style='background-color:#eee; padding: 20px; text-align: left;'> $note </td>
  </tr>
  
  <tr>
    <td style='background-color:#ddd; padding: 20px; text-align: left;'><strong>UNCLEARED BALANCE</strong></td>
    <td style='background-color:#eee; padding: 20px; text-align: left;'> $user_non_wdr_bal_formatted </td>
  </tr>
  
  <tr>
    <td style='background-color:#ccc; padding: 20px; text-align: left;'><strong>CLEARED/ AVAILABLE BALANCE</strong></td>
    <td style='background-color:#eee; padding: 20px; text-align: left;'> $user_wdr_bal_formatted </td>
  </tr>
  
  <tr>
    <td style='background-color:#ddd; padding: 20px; text-align: left;'><strong>TOTAL BALANCE</strong></td>
    <td style='background-color:#eee; padding: 20px; text-align: left;'> $user_balance_total_formatted </td>
  </tr>
  
  <tr>
    <td style='background-color:#ccc; padding: 20px; text-align: left;'><strong>TRANSACTION DATE / TIME</strong></td>
    <td style='background-color:#eee; padding: 20px; text-align: left;'> $date_time </td>
  </tr>
  
</table>
 <p style='background:#2d2f33; color:white; padding:25px; text-align:center;'>
    This email was sent from <a href='#' style='color: #d28787;'> $blogname_uppercase </a>
    <small>Powered by <em><a href='https://rimplenet.com'>Rimplenet</a></em></small>
 </p>
<div/>";

    $headers = array('Content-Type: text/html; charset='.get_bloginfo('charset').'' . '\r\n');