<ul class="sidebar-menu">
	<!-- Menu Title -->
	<li class="header">MENU</li>
	<!-- Penjualan -->
	<li <?php if ($top_menu_sel=="menu_home"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>index.php"><i class="fa fa-home"></i> <span>Home</span></a></li>
	<li class="treeview <?php if ($top_menu_sel=="menu_penawaran" || $top_menu_sel=="menu_customer" || $top_menu_sel=="menu_project" || $top_menu_sel=="menu_po" || $top_menu_sel=="menu_jual" || $top_menu_sel=="menu_sewa" || $top_menu_sel=="menu_claim" || $top_menu_sel=="menu_sjkirim" || $top_menu_sel=="menu_sjkembali" || $top_menu_sel=="menu_invoice") { ?> active <?php } ?>">
		<a href="#">
			<i class="fa fa-cart-arrow-down"></i>
			<span>Penjualan</span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			<li <?php if ($top_menu_sel=="menu_customer"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/customer/Customer.php"><i class="fa fa-users"></i> <span>Customer</span></a></li>
			<li <?php if ($top_menu_sel=="menu_project"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/project/Project.php"><i class="fa fa-building-o"></i> <span>Project</span></a></li>
			<li <?php if ($top_menu_sel=="menu_penawaran"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-envelope-o"></i> <span>Penawaran</span></a></li>
			<li <?php if ($top_menu_sel=="menu_po"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/pocustomer/POCustomer.php"><i class="fa fa-file-text-o"></i> <span>PO Customer</span></a></li>
			<li <?php if ($top_menu_sel=="menu_jual"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/transaksijual/TransaksiJual.php"><i class="fa fa-credit-card"></i> <span>Transaksi Jual</span></a></li>
			<li <?php if ($top_menu_sel=="menu_sewa"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/transaksisewa/TransaksiSewa.php"><i class="fa fa-money"></i> <span>Transaksi Sewa</span></a></li>
			<li <?php if ($top_menu_sel=="menu_claim"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/transaksiclaim/TransaksiClaim.php"><i class="fa fa-chain-broken"></i> <span>Transaksi Claim</span></a></li>
			<li <?php if ($top_menu_sel=="menu_sjkirim"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/sjkirim/SJKirim.php"><i class="fa fa-automobile"></i> <span>SJ Kirim</span></a></li>
			<li <?php if ($top_menu_sel=="menu_sjkembali"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/sjkembali/SJKembali.php"><i class="fa fa-automobile"></i> <span>SJ Kembali</span></a></li>
			<li <?php if ($top_menu_sel=="menu_invoice"){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>pages/invoice/Invoice.php"><i class="fa fa-list-alt"></i> <span>Invoice</span></a></li>
		</ul>
	</li>
	<li class="treeview <?php if (0) { ?> active <?php } ?>">
		<a href="#">
			<i class="fa fa-cart-plus"></i>
			<span>Pembelian</span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-envelope-o"></i> <span>Permintaan</span></a></li>
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-file-text-o"></i> <span>PO</span></a></li>
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-automobile"></i> <span>Penerimaan</span></a></li>
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-history"></i> <span>Retur</span></a></li>
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-list-alt"></i> <span>Invoice</span></a></li>
		</ul>
	</li>
	<li class="treeview <?php if (0) { ?> active <?php } ?>">
		<a href="#">
			<i class="fa fa-archive"></i>
			<span>Inventori</span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-folder-open-o"></i> <span>Lihat Stok</span></a></li>
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-database"></i> <span>Penyesuaian Stok</span></a></li>
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-exchange"></i> <span>Transfer Antar Gudang</span></a></li>
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-cubes"></i> <span>Daftar Barang</span></a></li>
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-industry"></i> <span>Daftar Gudang</span></a></li>
		</ul>
	</li>
	<li class="treeview <?php if (0) { ?> active <?php } ?>">
		<a href="#">
			<i class="fa fa-gears "></i>
			<span>Manufaktur</span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
	</li>
	<li class="treeview <?php if (0) { ?> active <?php } ?>">
		<a href="#">
			<i class="fa fa-money"></i>
			<span>Kas</span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-plus"></i> <span>Penerimaan</span></a></li>
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-minus"></i> <span>Pembayaran</span></a></li>
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-exchange"></i> <span>Transfer Antar Akun</span></a></li>
			<li <?php if (0){ ?> class="active" <?php } ?>><a href="<?php echo $ROOT; ?>#"><i class="fa fa-user-plus"></i> <span>Daftar Akun</span></a></li>
		</ul>
	</li>
	<li class="treeview <?php if (0) { ?> active <?php } ?>">
		<a href="#">
			<i class="fa  fa-book"></i>
			<span>Buku Besar</span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
	</li>
	
</ul>
