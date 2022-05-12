=== Rimplenet E-Banking | E-Wallets  | Investments Plugin | MLM | Matrix Tree | Referral Manager | FinTech ===
Contributors: nellalink
Donate link: https://rimplenet.com/donate
Tags: wallet-creator, ewallet, e-wallet, ebanking, e-banking, mlm, matrix, matrix-tree, investments, investment-plugin, fintech, loan-plugin-maker, woocommerce-payment-processor-maker
Requires at least: 3.0.1
Tested up to: 5.9
Stable tag: 1.1.31
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Rimplenet is a Financial Technology (FinTech) Plugin for Wordpress with E-Wallet Functionality used to setup E-Banking, Loan - Requester App, MLM, Matrix, Investments and Packages. Using this Plugin is simple, install it, RIMPLENET will appear on your admin dashboard menu (with ability to create Ewallets, Matrix and Matrix Tree, Packages, and E-banking Rules). Admin can auto credit or debit using rules or manually via WALLET SETTINGS Page. WALLETS can be also be used for making woocommerce payment. 

NEW:To know how to Use this Plugin: visit [https://rimplenet.tawk.help](https://rimplenet.tawk.help) or [https://rimplenet.com/docs](https://rimplenet.com/docs)

== Description ==


Rimplenet is a Financial Technology (FinTech) Plugin for Wordpress with E-Wallet Functionality used to setup E-Banking, Loan - Requester App, MLM, Matrix, Investments and Packages. Using this Plugin is simple, install it, RIMPLENET will appear on your admin dashboard menu (with ability to create Ewallets, Matrix and Matrix Tree, Packages, and E-banking Rules). Admin can auto credit or debit using rules or manually via WALLET SETTINGS Page. WALLETS can be also be used for making woocommerce payment. 

WALLETS can be used for making woocommerce payment. How to Use this Plugin: visit [https://rimplenet.tawk.help/](https://rimplenet.tawk.help/)

> We update rimplenet features to meet users need & enhance improvement, join our newsletter to know when new updates and features are released [Newsletter Link - https://rimplenet.com/newsletter/](https://rimplenet.com/newsletter).

== Installation ==

***Download rimplenet.zip
1. Upload `rimplenet.zip` via ftp or cpanel upload to the folder `/wp-content/plugins/` directory of your wordpress website installation. OR Login to your wordpress admin dashboard and upload the .zip file
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= What are Required to use this Plugin? =

 Install Wordpress on your server, then install Rimplenet

= How do I create matrix, packages and ewallets? =

Click Rimplenet on your wordpress admin backend, then click the menu you want to create either matrix, packages or ewallets. then Copy paste the shortcode to the page you want it to work

= How can I display Individuals User Credit=

Use shortcode [rimplenet-wallet action="view_balance" wallet_id="YOUR_CREATED_WALLET_ID_HERE"] , if you have woocommerce installed, you automatically have the currency set in woocommerce as a wallet, then you can use shortcode [rimplenet-wallet action="view_balance" wallet_id="woocommerce_base_cur"].


= How can I Credit User Wallet?=

Login as admin to your website, from https://example.com/wp-admin , click Rimplenet -> Settings: Wallet -> you will be presented with a form to Credit or Debit your Users.

= How do I restrict users that joins a particular matrix or packages=

The sweetest thing about our Plugin, We use rules, for example to allow a user to join PACKAGE-1 by buying a product (product 1). Then after earn earn 100USD.

The rules goes this way 

rules_before_package_entry == rimplenet_rules_check_woocomerce_product_purchase_status:PRODUCT_ID
rimplenet_rules_inside_package_and_linked_product_ordered

    rimplenet_rules_add_to_mature_wallet: 100, woocommerce_base_cur
    OR
    rimplenet_rules_add_to_mature_wallet: 100, WALLET_ID
    
    Rules is thoroughly explained at [https://rimplenet.com/rules](https://rimplenet.com/rules)


= I have Setup Issues or other Questions not include =

Contact us via Phonecall or Live Chat or Via our Support forum, we answer as soon as possible in few minutes, visit our website at www.rimplenet.com and choose any contact channel suitable for you.

== Screenshots ==

1. Create Wallet from Admin Screen,Set Minimum and Maximum Withdrawal Amount
2. Edit Wallet Balance from User Edit Screen
3. Display Withdrawal Form using shortcode
4. Create Package from Admin Screen
5. List Package using Shortcode
6. Create Matrix from Admin Screen
7. Display Matrix Tree with Shortcode
8. Simple Designed Wallet Balance and User Meta Dashboard using Shortcodes

== Changelog ==

= 1.1.31 =
* New hook introduced do_action('rimplenet_withdrawal_form_submitted') on withdrawal form
= 1.1.29 =
* Check of ID on credit, debit function and other bugs fixes
= 1.1.28 =
* New Withdrawal Format Introduced, refer to docs on how to use
= 1.1.27 =
* Admin error fixed
= 1.1.26 =
* Bugs fixes
= 1.1.25 =
* Introduction of new Withdrawal Shortcode [rimplenet-withdrawal-form wallet_id="ID_HERE" wdr_dest="bank"] & native support of bootstrap
= 1.1.24 =
* Bugs Fixes for Auto Wallet Setup
= 1.1.21 =
* Wallets Settings now has Metabox
= 1.1.20 =
* Admin Dashboard Redefined
= 1.1.18 =
* Seurity upgrade on Crediting Rules
= 1.1.17 =
* Investment Packages Capital can now be refunded to users on Investment Contract Completion
= 1.1.15 =
* Investment Packages now works async
* Email notification to users on CREDIT and DEBIT is now back
* Tested for Wordpress 5.8 newest version
= 1.1.14 =
* Memory Leak Issue fixed
= 1.1.13 =
* Specification to Redirect Product Page to Cart Page, Checkout or Payment Page, transaction email temporarily disabled to resolve Email Bombing issues with Namecheap
= 1.1.8 =
* Withdrawal Pagination & Bug Fixes
= 1.1.6 =
* Bug fixes & txn_check_fxn included
= 1.1.5 =
* Investment Rules not Counting Fixed
= 1.1.3 =
* Admin can now Credit or Debit User with a form on backend via WP-ADMIN-> SETTINGS: WALLET
* Notification Email on Wallet Funds Additions or Withdrawal has been enabled.
= 1.1.1 =
* Support for QR Code display BETA
* Beta support for Investment Pages,FULL SUPPORT will be available Version 1.2.0 
= 1.0.7 =
* BETA SUPPORT for Creation of Investment Pages, FULL SUPPORT will be available Version 1.2.0
* Fixed Matrix Tree not Showing for last depth of downline
* Added Support for Wordpress 5.6
= 1.0.3 =
* Fixed My Referrals Short Code [rimplenet-display-info action="view_direct_downline"] and Account Activation Status Short Code [rimplenet-display-info action="view_account_activation_status"] accessible to only Logged in User.
= 1.0.2 =
* New Rule Added rimplenet_rules_add_to_mature_wallet_in_matrix: MATX_ID, woocommerce_base_cur, Downline 4 Bonus, IF_DOWNLINES_IS;4;ID_MATRIX_HERE specifically for matrix, for giving bonuses per each matrix downline.
* Fixed - Matrix Entry Rule not subscribing new members.  
* Minimum & Maximum Withdrawal Amount can be set when creating wallet.
* Wallet Position Symbol can be Set to either left or right when creating wallet.

= 1.0.0 =
* Launch of Rimplenet, Download, Use and Give us Feedback.


== Upgrade Notice === 

= 1.1.31 =
* New hook introduced do_action('rimplenet_withdrawal_form_submitted') on withdrawal form
= 1.1.29 =
* Check of ID on credit, debit function and other bugs fixes
= 1.1.28 =
* New Withdrawal Format Introduced, refer to docs on how to use
1.1.27 =
* Admin error fixe
= 1.1.26 =
* Bugs fixes
= 1.1.25 =
* Introduction of new Withdrawal Shortcode [rimplenet-withdrawal-form wallet_id="ID_HERE" wdr_dest="bank"] & native support of bootstrap
= 1.1.24 =
* Bugs Fixes for Auto Wallet Setup
= 1.1.21 =
* Wallets Settings now has Metabox
= 1.1.20 =
* Admin Dashboard Redefined
= 1.1.18 =
* Seurity upgrade on Crediting Rules
= 1.1.17 =
* Investment Packages Capital can now be refunded to users on Investment Contract Completion
= 1.1.15 =
* Investment Packages now works async
* Email notification to users on CREDIT and DEBIT is now back
* Tested for Wordpress 5.8 newest version
= 1.1.14 =
* Memory Leak Issue fixed
= 1.1.13 =
* Specification to Redirect Product Page to Cart Page, Checkout or Payment Page, transaction email temporarily disabled to resolve Email Bombing issues with Namecheap
= 1.1.8 =
* Withdrawal Pagination & Bug Fixes
= 1.1.6 =
* Bug fixes & txn_check_fxn included
= 1.1.5 =
* Investment Rules not Counting Fixed
= 1.1.3 =
* Admin can now Credit or Debit User with a form on backend via WP-ADMIN-> SETTINGS: WALLET
* Notification Email on Wallet Funds Additions or Withdrawal has been enabled.
= 1.1.1 =
* Support for QR Code display BETA
* Beta support for Investment Pages,FULL SUPPORT will be available Version 1.2.0 
= 1.0.7 =
* BETA SUPPORT for Creation of Investment Pages, FULL SUPPORT will be available Version 1.2.0
* Fixed Matrix Tree not Showing for last depth of downline
* Added Support for Wordpress 5.6
= 1.0.3 =
* Fixed My Referrals Short Code [rimplenet-display-info action="view_direct_downline"] and Account Activation Status Short Code [rimplenet-display-info action="view_account_activation_status"] accessible to only Logged in User.
= 1.0.2 =
* New Rule Added rimplenet_rules_add_to_mature_wallet_in_matrix: MATX_ID, woocommerce_base_cur, Downline 4 Bonus, IF_DOWNLINES_IS;4;ID_MATRIX_HERE specifically for matrix, for giving bonuses per each matrix downline.
* Fixed - Matrix Entry Rule not subscribing new members.  
* Minimum & Maximum Withdrawal Amount can be set when creating wallet.
* Wallet Position Symbol can be Set to either left or right when creating wallet.
= 1.0.1 =
* We fix Withdrawal Bugs, user can see their wallet balance on Withdrawal Form before withdrawing, admin can access their withdrawal request at backend.
= 1.0.0 =
* Launch of Rimplenet, Download, Use and Give us Feedback.

== FEATURES OF RIMPLENET ==

1. Create Unlimited Wallet and display with their shortcode
2. Credit or Debit User from Backend as Admin
3. Create MLM and Matrix Tree and display with their shortcode
4. Create Packages/Plans (package/plans are set by rules, visit How to Use this Plugin: visit [https://rimplenet.com/docs](https://rimplenet.com/docs) to learn more)
***Wallets can be set up via rules so users recieve rewards in his /her wallets automatically (rewards can be set daily, weekly, monthly etc)
***Wallet can be used as Woocommerce Payment Processor, setup can be done via Woocommerce Payments, you can use this plugin to create your woocommerce payment processor with your name
5. Users can fund the created wallet using bank, or any Payment Integration Supported via Woocommerce (Meaning your LOCAL PAYMENT PROCESSOR is probably there)
6. Wallet Withdrawal Form Supported by use of Shortcode , Min & Max Withdrawal can be set during creation of each Wallet