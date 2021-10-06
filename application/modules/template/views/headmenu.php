<ul id="nav-mobile" class="sidenav sidenav-fixed light-blue darken-4 grey-text text-lighten-5">
	<li class="center-align" id="profile">
		<div class="card profile blue-grey darken-2">
			<div class="card-image blue-grey lighten-2 center-align">
				<img style="width: 50%;" class=" z-depth-5" src="<?php echo $this->config->item('root_url'); ?>assets/image/favicon.png">
			</div>
			<div class="card-content grey-text text-lighten-5">
				<span class="card-title name truncate">
					<?php echo $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'); ?>
					<a class="right tooltipped" data-position="right" data-tooltip="Setting Account" href="<?php echo base_url('profile'); ?>"><i class="grey-text text-lighten-1 small material-icons">build</i></a>
				</span>
				<span class="card-title position truncate"><?php echo $this->session->userdata('position'); ?></span>
				<span class="card-title role truncate"><?php echo 'NIK : ' . $this->session->userdata('nik'); ?></span>
			</div>
		</div>
	</li>
	<li id="mob-menu_dashboard" class="no-padding bold"><a class="a-flat grey-text text-lighten-5" href='<?php echo base_url('dashboard'); ?>'><i class="material-icons">polls</i>Dashboard</a></li>
	<li class="no-padding">
		<ul class="collapsible collapsible-accordion">
			<li id="mob-menu_artikel" class="bold"><a class="a-flat collapsible-header grey-text text-lighten-5"><i class="material-icons">assignment</i>Editorial<i class="material-icons no-margin right">arrow_drop_down</i></a>
				<div class="collapsible-body blue-grey darken-2">
					<ul class="submenu-sidenav">
						<li id="mob-submenu_artikel_penugasan"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('artikel_penugasan'); ?>"><i class="material-icons">folder_shared</i>Penugasan</a></li>
						<li id="mob-submenu_artikel_usulan"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('artikel_usulan'); ?>"><i class="material-icons">folder_open</i>Usulan</a></li>
						<li id="mob-submenu_artikel_naskah"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('artikel_naskah'); ?>"><i class="material-icons">folder_special</i>Naskah</a></li>
						<li class="divider no-margin" tabindex="-1"></li>
						<li id="mob-submenu_artikel_kategori"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('artikel_kategori'); ?>"><i class="material-icons">view_stream</i>Kategori</a></li>
						<li id="mob-submenu_artikel_tag"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('artikel_tag'); ?>"><i class="material-icons">turned_in</i>Tag</a></li>
					</ul>
				</div>
			</li>
			<li id="mob-menu_iklan" class="bold"><a class="a-flat collapsible-header grey-text text-lighten-5"><i class="material-icons">view_quilt</i>Iklan<i class="material-icons no-margin right">arrow_drop_down</i></a>
				<div class="collapsible-body blue-grey darken-2">
					<ul class="submenu-sidenav">
						<li id="mob-submenu_adsd_billboard"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('adsd_billboard'); ?>"><i class="material-icons">laptop</i>Billboard</a></li>
						<li id="mob-submenu_adsd_rectangel"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('adsd_rectangel'); ?>"><i class="material-icons">laptop</i>Rectangel</a></li>
						<li id="mob-submenu_adsd_skycrapper"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('adsd_skycrapper'); ?>"><i class="material-icons">laptop</i>Skycrapper</a></li>
						<li id="mob-submenu_adsd_leaderboard"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('adsd_leaderboard'); ?>"><i class="material-icons">laptop</i>Leaderboard</a></li>
						<li class="divider no-margin" tabindex="-1"></li>
						<li id="mob-submenu_adsm_sticky"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('adsm_sticky'); ?>"><i class="material-icons">phone_android</i>Sticky</a></li>
						<li id="mob-submenu_adsm_center"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('adsm_center'); ?>"><i class="material-icons">phone_android</i>Center</a></li>
					</ul>
				</div>
			</li>
			<li id="mob-menu_website" class="bold"><a class="a-flat collapsible-header grey-text text-lighten-5"><i class="material-icons">settings</i>Website<i class="material-icons no-margin right">arrow_drop_down</i></a>
				<div class="collapsible-body blue-grey darken-2">
					<ul class="submenu-sidenav">
						<li id="mob-submenu_setup_compro"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('setup_compro'); ?>"><i class="material-icons">view_compact</i>Setup Com-Pro</a></li>
						<li id="mob-submenu_setup_home"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('setup_home'); ?>"><i class="material-icons">web</i>Setup Home</a></li>
						<li id="mob-submenu_setup_web"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('setup_web'); ?>"><i class="material-icons">settings_applications</i>Setup Website</a></li>
						<li class="divider no-margin" tabindex="-1"></li>
						<li id="mob-submenu_setup_pengumuman"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('setup_pengumuman'); ?>"><i class="material-icons">notifications_active</i>Pengumuman</a></li>
					</ul>
				</div>
			</li>
			<li id="mob-menu_ols" class="bold"><a class="a-flat collapsible-header grey-text text-lighten-5"><i class="material-icons">store</i>Store<i class="material-icons no-margin right">arrow_drop_down</i></a>
				<div class="collapsible-body blue-grey darken-2">
					<ul class="submenu-sidenav">
						<li id="mob-submenu_ols_dashboard"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('ols_dashboard'); ?>"><i class="material-icons">dashboard</i>Dashboard</a></li>
						<li class="divider no-margin" tabindex="-1"></li>
						<li id="mob-submenu_ols_buku"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('ols_buku'); ?>"><i class="material-icons">import_contacts</i>Buku</a></li>
						<li id="mob-submenu_ols_majalah"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('ols_majalah'); ?>"><i class="material-icons">chrome_reader_mode</i>Majalah</a></li>
						<li class="divider no-margin" tabindex="-1"></li>
						<li id="mob-submenu_ols_kategori_buku"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('kategori_buku'); ?>"><i class="material-icons">import_contacts</i>Kategori Buku</a></li>
						<li id="mob-submenu_ols_kategori_majalah"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('kategori_majalah'); ?>"><i class="material-icons">chrome_reader_mode</i>Kategori Majalah</a></li>
						<li class="divider no-margin" tabindex="-1"></li>
						<li id="mob-submenu_ols_pengiriman"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('ols_pengiriman'); ?>"><i class="material-icons">local_shipping</i>Pengiriman</a></li>
						<li id="mob-submenu_ols_promosi"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('ols_promosi'); ?>"><i class="material-icons">cast</i>Promosi</a></li>
						<li id="mob-submenu_ols_transaksi"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('ols_transaksi'); ?>"><i class="material-icons">swap_horiz</i>Transaksi</a></li>
						<li id="mob-submenu_ols_member"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('ols_member'); ?>"><i class="material-icons">people_outline</i>Membership</a></li>
						<li id="mob-submenu_ols_member_chat"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('ols_member_chat'); ?>"><i class="material-icons">chat</i>Customer Chats</a></li>
					</ul>
				</div>
			</li>
			<li id="mob-menu_user" class="bold"><a class="a-flat collapsible-header grey-text text-lighten-5"><i class="material-icons">accessibility</i>Users<i class="material-icons no-margin right">arrow_drop_down</i></a>
				<div class="collapsible-body blue-grey darken-2">
					<ul class="submenu-sidenav">
						<li id="mob-submenu_user_management"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('user_management'); ?>"><i class="material-icons">people</i>User Management</a></li>
					</ul>
				</div>
			</li>
			<li id="mob-menu_master" class="bold"><a class="a-flat collapsible-header grey-text text-lighten-5"><i class="material-icons">group_work</i>Master Data<i class="material-icons no-margin right">arrow_drop_down</i></a>
				<div class="collapsible-body blue-grey darken-2">
					<ul class="submenu-sidenav">
						<li id="mob-submenu_master_department"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('mst_department'); ?>"><i class="material-icons">grade</i>Department</a></li>
						<li id="mob-submenu_master_division"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('mst_division'); ?>"><i class="material-icons">grade</i>Division</a></li>
						<li id="mob-submenu_master_position"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('mst_position'); ?>"><i class="material-icons">grade</i>Position</a></li>
						<li class="divider no-margin" tabindex="-1"></li>
						<li id="mob-submenu_master_role"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('mst_role'); ?>"><i class="material-icons">grade</i>Role Privilege</a></li>
						<li class="divider no-margin" tabindex="-1"></li>
						<li id="mob-submenu_master_location"><a class="a-flat grey-text text-lighten-5" href="<?php echo base_url('mst_location'); ?>"><i class="material-icons">map</i>Location</a></li>
					</ul>
				</div>
			</li>
		</ul>
	</li>
</ul>