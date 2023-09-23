<div class="wp-list-table widefat">
		<div id="the-list">
		    
		    <?php 
		      $all_wallets_url = add_query_arg( array( 'post_type'=>'rimplenettransaction', 'rimplenettransaction_type'=>'rimplenet-wallets', 'viewing_user'=>$current_user->ID), admin_url( "edit.php") ); 
		      $all_packages_url = add_query_arg( array( 'post_type'=>'rimplenettransaction', 'rimplenettransaction_type'=>'rimplenet-mlm-packages', 'viewing_user'=>$current_user->ID), admin_url( "edit.php") ); 
		      $all_matrix_url = add_query_arg( array( 'post_type'=>'rimplenettransaction', 'rimplenettransaction_type'=>'rimplenet-mlm-matrix', 'viewing_user'=>$current_user->ID), admin_url( "edit.php") ); 
            ?>
			
					<div class="plugin-card">
					    <a href="<?php echo $all_wallets_url; ?>" class="plugin-image">
							<img src="<?php echo plugin_dir_url(dirname( __FILE__ ));  ?>img/file-icon.png" alt="Create New Wallet">
						</a>
						<div class="plugin-card-top">
							<h3><a href="<?php echo $all_wallets_url; ?>">Create New Wallet</a></h3>

							<div class="desc column-description">
							   <?php
							     echo __('Wallets can be created to support deposit & withdraw, wallet is needed for Investments functionality, MLM Matrixes rewards or MLM commisions payment, Referral Bonus Payments or anything that concerns funds. Users can fund the wallet via supported payments methods (all major payments processors around the world supported).','rimplenet');
							   ?>
							 </div>

							<div class="action-links">
								<ul class="plugin-action-buttons">
									<li>
										<a href="<?php echo $all_wallets_url; ?>" class="action-btn button">
										 <?php
            							     echo __('Go to all Wallets','rimplenet');
            							  ?>
										</a>
									</li>
									<li>
										<a href="https://rimplenet.tawk.help/category/wallets" target="_blank">
											<?php
            							     echo __('Learn More from Docs','rimplenet');
            							    ?>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>

				
					<div class="plugin-card">
						<a href="<?php echo $all_packages_url; ?>" class="plugin-image">
							<img src="<?php echo plugin_dir_url(dirname( __FILE__ ));  ?>img/folder-icon.png" alt="Create Investment / Packages">
						</a>
						<div class="plugin-card-top">
							<h3><a href="<?php echo $all_packages_url; ?>"> Create Package / Investment </a></h3>

							<div class="desc column-description">
							   <?php
							     echo __('Packages or Investment Packages can be created for users to have a savings plan, or maybe to give your users rewards (daily, weekly, monthly etc), packages can as well support deposits or withdrawal (you need to create a wallet first for package deposit, withdraw & investment). You can set auto rewarding rules too for your packages or investments.','rimplenet');
							   ?>
							 </div>

							<div class="action-links">
								<ul class="plugin-action-buttons">
									<li>
										<a href="<?php echo $all_packages_url; ?>" class="action-btn button">
										 <?php
            							     echo __('Go to all Packages','rimplenet');
            							  ?>
										</a>
									</li>
									<li>
										<a href="https://rimplenet.tawk.help/category/investments-packages" target="_blank">
										    <?php
            							     echo __('Learn More from Docs','rimplenet');
            							    ?>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>

				
					<div class="plugin-card">
						<a href="<?php echo $all_matrix_url; ?>" class="plugin-image">
							<img src="<?php echo plugin_dir_url(dirname( __FILE__ ));  ?>img/users-icon.png" alt="Create New Matrix">
						</a>
						<div class="plugin-card-top">
							<h3><a href="<?php echo $all_matrix_url; ?>">Create New MLM Matrix</a></h3>

							<div class="desc column-description">
							   <?php
							     echo __('MLM Matrix can be created to rewards your users based on the task they have performed (you can set your own rules for users to join or exit your matrix along the bonus for your compensation plan), some matrix do have a tree, we support matrix tree. The dept and width of the matrix tree as well as the matrix entry method (placement method) is set by you.','rimplenet');
							   ?>
							 </div>

							<div class="action-links">
								<ul class="plugin-action-buttons">
									<li>
										<a href="<?php echo $all_matrix_url; ?>" class="action-btn button">
										 <?php
            							     echo __('Go to all Matrix','rimplenet');
            							  ?>
										</a>
									</li>
									<li>
										<a href="https://rimplenet.tawk.help/category/mlm-matrix" target="_blank">
											<?php
            							     echo __('Learn More from Docs','rimplenet');
            							    ?>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>

				
				
		</div>
	</div>