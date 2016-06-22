<ul class="sidebar-menu">
	<!-- Menu Title -->
	<li class="header">MENU</li>
	<!-- Penjualan -->
	<li <?php if ($top_menu_sel=="menu_home"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>index.php"><i class="fa fa-home"></i> <span>Home</span></a></li>
	<li class="treeview <?php if ($top_menu_sel=="menu_customer" || $top_menu_sel=="menu_project" || $top_menu_sel=="menu_po" || $top_menu_sel=="menu_jual" || $top_menu_sel=="menu_sewa" || $top_menu_sel=="menu_claim" || $top_menu_sel=="menu_sjkirim" || $top_menu_sel=="menu_sjkembali" || $top_menu_sel=="menu_invoice") { ?> active <?php } ?>">
		<a href="#">
			<i class="fa fa-dashboard"></i>
			<span>Penjualan</span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			<li <?php if ($top_menu_sel=="menu_customer"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/customer/Customer.php"><i class="fa fa-users"></i> <span>Customer</span></a></li>
			<li <?php if ($top_menu_sel=="menu_project"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/project/Project.php"><i class="fa fa-building-o"></i> <span>Project</span></a></li>
			<li <?php if ($top_menu_sel=="menu_po"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/pocustomer/POCustomer.php"><i class="fa fa-file-text-o"></i> <span>PO Customer</span></a></li>
			<li <?php if ($top_menu_sel=="menu_jual"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/transaksijual/TransaksiJual.php"><i class="fa fa-credit-card"></i> <span>Transaksi Jual</span></a></li>
			<li <?php if ($top_menu_sel=="menu_sewa"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/transaksisewa/TransaksiSewa.php"><i class="fa fa-money"></i> <span>Transaksi Sewa</span></a></li>
			<li <?php if ($top_menu_sel=="menu_claim"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/transaksiclaim/TransaksiClaim.php"><i class="fa fa-chain-broken"></i> <span>Transaksi Claim</span></a></li>
			<li <?php if ($top_menu_sel=="menu_sjkirim"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/sjkirim/SJKirim.php"><i class="fa fa-automobile"></i> <span>SJ Kirim</span></a></li>
			<li <?php if ($top_menu_sel=="menu_sjkembali"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/sjkembali/SJKembali.php"><i class="fa fa-automobile"></i> <span>SJ Kembali</span></a></li>
			<li <?php if ($top_menu_sel=="menu_invoice"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/invoice/Invoice.php"><i class="fa fa-list-alt"></i> <span>Invoice</span></a></li>
		</ul>
	</li>
	
</ul>
