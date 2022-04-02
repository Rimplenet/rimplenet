<?php

class Rimplenet_Email{
 
    public function __construct() {
         
       add_action('after_add_user_mature_funds_to_wallet', array($this,'sendEmailNotification'), 10, 7 );
        
    }
    
   function sendEmailNotification($txn_add_bal_id,$user_id,$amount_to_add,$wallet_id,$note,$tags,$tnx_type)
        {
         $txn_id = $txn_add_bal_id;
         $wallet_obj = new Rimplenet_Wallets();
         $all_wallets = $wallet_obj->getWallets();
         
         $user_wdr_bal = $wallet_obj->get_withdrawable_wallet_bal($user_id, $wallet_id);
         $user_non_wdr_bal = $wallet_obj->get_nonwithdrawable_wallet_bal($user_id, $wallet_id);
         $user_balance_total = $wallet_obj->get_total_wallet_bal($user_id,$wallet_id);
         
         $dec = $all_wallets[$wallet_id]['decimal'];
         $symbol = $all_wallets[$wallet_id]['symbol'];
         $symbol_position = $all_wallets[$wallet_id]['symbol_position'];
         if($symbol_position=='right'){
            $amount_formatted = number_format($amount_to_add,$dec)." ".$symbol;
            $user_wdr_bal_formatted = number_format($user_wdr_bal,$dec)." ".$symbol;
            $user_non_wdr_bal_formatted = number_format($user_non_wdr_bal,$dec)." ".$symbol;
            $user_balance_total_formatted = number_format($user_balance_total,$dec)." ".$symbol;
            
         }
        else{
            $amount_formatted = $symbol.number_format($amount_to_add,$dec);
            $user_wdr_bal_formatted  = $symbol.number_format($user_wdr_bal,$dec);
            $user_non_wdr_bal_formatted = $symbol.number_format($user_non_wdr_bal,$dec);
            $user_balance_total_formatted = $symbol.number_format($user_balance_total,$dec);
            
        }
         
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
         
         $date_time = get_the_date('D, M j, Y', $txn_id).'<br>'.get_the_date('g:i A', $txn_id);
         
            $to = $user_email;
            
            $subject  = "$tnx_type_uppercase of $amount_formatted ON $blogname_uppercase - TXN #$txn_add_bal_id";
            
            $body ="<style>
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
                    This email was sent from <a href='$blogurl' style='color: #d28787;'> $blogname_uppercase </a>
                    <small>Powered by <em><a href='https://rimplenet.com'>Rimplenet</a></em></small>
                 </p>
                <div/>";
            
            $headers  = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=".get_bloginfo('charset')."" . "\r\n";
            //$headers .= "From: Transaction Notifier ~ $blogname <noreply@".$domain_name.">" . "\r\n";//we now use default because of hosting issues
 
             
            wp_mail($to, $subject, $body, $headers );
            
    }
    
 }

$Rimplenet_Email = new Rimplenet_Email();