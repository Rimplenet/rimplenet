<?php
global $current_user,$wp;
wp_get_current_user();
// $wallet_obj = new Rimplenet_Wallets();

$wallet_obj = new RimplenetGetWallets();
// $wallet_obj->createQuery();
$wallet_obj->getWallets();
$all_wallets=$wallet_obj->response['data'];

// var_dump($all_wallets);
// die;


?>

<!-- <style>
  @media screen and (max-width: 600px) {	

      table {width:100%;}

      /* thead {display: none;} */

      tr:nth-of-type(2n) {background-color: inherit;}

      tr td:first-child {background: #f0f0f0; font-weight:bold;font-size:1.3em;}

      /* tbody td {display: block;  text-align:center;} */

      tbody td:before {

        /* content: attr(data-th); */

        display: block;

        text-align:center; 

      }

}
</style> -->



<div class="bg-white border rounded-5">
    
    <section class="w-100 p-4">
      <div class="datatable">
        <table>
          <thead>
            <tr>
              <th class="th-sm">Name</th>
              <th class="th-sm">Position</th>
              <th class="th-sm">Office</th>
              <th class="th-sm">Age</th>
              <th class="th-sm">Start date</th>
              <th class="th-sm">Salary</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Tiger Nixon</td>
              <td>System Architect</td>
              <td>Edinburgh</td>
              <td>61</td>
              <td>2011/04/25</td>
              <td>$320,800</td>
            </tr>
            <tr>
              <td>Garrett Winters</td>
              <td>Accountant</td>
              <td>Tokyo</td>
              <td>63</td>
              <td>2011/07/25</td>
              <td>$170,750</td>
            </tr>
            <tr>
              <td>Ashton Cox</td>
              <td>Junior Technical Author</td>
              <td>San Francisco</td>
              <td>66</td>
              <td>2009/01/12</td>
              <td>$86,000</td>
            </tr>
            <tr>
              <td>Cedric Kelly</td>
              <td>Senior Javascript Developer</td>
              <td>Edinburgh</td>
              <td>22</td>
              <td>2012/03/29</td>
              <td>$433,060</td>
            </tr>
            <tr>
              <td>Airi Satou</td>
              <td>Accountant</td>
              <td>Tokyo</td>
              <td>33</td>
              <td>2008/11/28</td>
              <td>$162,700</td>
            </tr>
            <tr>
              <td>Brielle Williamson</td>
              <td>Integration Specialist</td>
              <td>New York</td>
              <td>61</td>
              <td>2012/12/02</td>
              <td>$372,000</td>
            </tr>
            <tr>
              <td>Herrod Chandler</td>
              <td>Sales Assistant</td>
              <td>San Francisco</td>
              <td>59</td>
              <td>2012/08/06</td>
              <td>$137,500</td>
            </tr>
            <tr>
              <td>Rhona Davidson</td>
              <td>Integration Specialist</td>
              <td>Tokyo</td>
              <td>55</td>
              <td>2010/10/14</td>
              <td>$327,900</td>
            </tr>
            <tr>
              <td>Colleen Hurst</td>
              <td>Javascript Developer</td>
              <td>San Francisco</td>
              <td>39</td>
              <td>2009/09/15</td>
              <td>$205,500</td>
            </tr>
            <tr>
              <td>Sonya Frost</td>
              <td>Software Engineer</td>
              <td>Edinburgh</td>
              <td>23</td>
              <td>2008/12/13</td>
              <td>$103,600</td>
            </tr>
            <tr>
              <td>Jena Gaines</td>
              <td>Office Manager</td>
              <td>London</td>
              <td>30</td>
              <td>2008/12/19</td>
              <td>$90,560</td>
            </tr>
            <tr>
              <td>Quinn Flynn</td>
              <td>Support Lead</td>
              <td>Edinburgh</td>
              <td>22</td>
              <td>2013/03/03</td>
              <td>$342,000</td>
            </tr>
            <tr>
              <td>Charde Marshall</td>
              <td>Regional Director</td>
              <td>San Francisco</td>
              <td>36</td>
              <td>2008/10/16</td>
              <td>$470,600</td>
            </tr>
            <tr>
              <td>Haley Kennedy</td>
              <td>Senior Marketing Designer</td>
              <td>London</td>
              <td>43</td>
              <td>2012/12/18</td>
              <td>$313,500</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
    
</div>





<h2> ACTIVE WALLETS</h2>
<div class="table-responsive bg-white p-5 mr-3 ml-3">
<table class="table table-sm table-borderless table-striped" style="width:100%" id="rimplenetmyTable">

<thead>
 <tr>
   <th> Wallet Name </th>
   <th> Description </th>
   <th> Wallet Symbol - (ID) </th>
   <th> Wallet Decimal </th>
   <th> User Balance Shortcode </th>
   <!-- <th> Include Wallet in Withdrawal Form</th> -->
   <!-- <th> Include Wallet in Woocommerce Currency List</th> -->
   <th> Actions </th>
 </tr>
</thead>
 
<tbody>

<?php
   foreach ($all_wallets as $key => $value) {
       // $wallet_id = get_post_meta($txn_id, 'rimplenet_wallet_id', true);
       $user_balance_shortcode  = '[rimplenet-wallet action="view_balance" wallet_id="'.$value['post_id'].'"]';
       $edit_wallet_link = '<a href="'.get_edit_post_link($value['post_id']).'" target="_blank" class="btn-primary btn">Edit Wallet</a>';
       if(!empty($linked_page_id)){
           $view_wallet_page_link = ' | <a href="'.get_permalink($linked_page_id).'" target="_blank" class="btn-transparent">View Wallet</a>' ;
       }
       
   //     //$view_linked_product_link = ' | <a href="'.get_post_permalink($linked_woocommerce_product_id).'"  target="_blank">View Linked Product</a>';

?>
 
 <tr>
   <td><?php echo $value['wallet_name']; ?></td>
   <td><?php echo $value['wallet_note']; ?></td>
   <td><?php echo $value['wallet_symbol']; ?> - (<?php echo $value['wallet_id']; ?>)</td>
   <td><?php echo $value['wallet_decimal']; ?></td>
   <td> <code class="rimplenet_click_to_copy bg-white"> <?php echo $user_balance_shortcode; ?></code> </td>
   <!-- <td><?php //echo $value['include_in_withdrawal_form']; ?></td> -->
   <!-- <td><?php //echo $value['include_in_woocommerce_currency_list']; ?></td> -->
   <td> 
     <?php echo $edit_wallet_link; ?> <?php //echo $view_wallet_page_link; ?> <?php //echo $edit_linked_product_link; ?> <?php //echo $view_linked_product_link; ?>
   </td>

   
 </tr>

 <?php

   }

 

 ?>
 
</tbody>

<tfoot>
 <tr>
   <th> Wallet Name </th>
   <th> Description </th>
   <th> Wallet Symbol - (ID) </th>
   <th> Wallet Decimal </th>
   <th> User Balance Shortcode </th>
   <!-- <th> Include Wallet in Withdrawal Form</th> -->
   <!-- <th> Include Wallet in Woocommerce Currency List</th> -->
   <th> Actions </th>
 </tr>
 </tfoot>

</table>
</div>
