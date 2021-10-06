<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="<?php echo $this->config->item('web_icon_url'); ?>">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/materialize1/css/materialize.css" media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/select2/dist/css/select2.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/jquery-confirm-v3.3.4/css/jquery-confirm.css">
    <!--    <link type="text/css" rel="stylesheet" href="--><?php //echo $this->config->item( 'root_url' ); 
                                                            ?>
    <!--assets/css/modal_size.css">-->
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/css/styles.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/css/icon.css" media="screen,projection">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/css/app.css" media="screen,projection">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/css/loaders.min.css" media="screen,projection">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/DataTables/datatables.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/DataTables/Buttons-1.5.4/css/buttons.dataTables.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/DataTables/Responsive-2.2.2/css/responsive.dataTables.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/DataTables/FixedHeader-3.1.4/css/fixedHeader.dataTables.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/DataTables/RowGroup-1.1.0/css/rowGroup.dataTables.min.css">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($page_title) ? $page_title : ''; ?> | <?php echo $this->config->item('app_name'); ?></title>

    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/js/jquery-3.3.1.min.js"></script>


</head>

<body onload="loading();" class="blue-grey lighten-3" style="font-size: 90%;">
    <?php $this->load->view('template/preloader'); ?>

    <div class="navbar-fixed" id="navbar_fixed">
        <nav class="blue-grey darken-3">
            <div class="nav-wrapper row">
                <div class="no-padding col s7 m8 l9">
                    <a href="#" data-target="nav-mobile" class="left sidenav-trigger full"><i class="material-icons">menu</i></a>
                    <span class="content-title truncate"><?php echo isset($content_title) ? $content_title : "GATRAPEDIA"; ?></span>
                </div>
                <div class="no-padding col s5 m4 l3">
                    <ul class="right">
                        <li id="mob-menu_notif" class="hoverable"><a class="blue-grey darken-2 dropdown-trigger tooltipped" data-position="left" data-tooltip="Pengumuman" data-target="dropdowndesktop-menunotif" href="#"><i class="material-icons">notifications</i></a></li>
                        <li id="mob-menu_logout" class="hoverable"><a class="blue-grey darken-4 tooltipped" data-position="left" data-tooltip="Logout" href="#" onclick="do_logout();"><i class="material-icons">directions_run</i></a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <ul id="dropdowndesktop-menunotif" class="dropdown-content roboto width-dropdown">
        <li class="hoverable"><a class="dropdown-button" href="<?php echo base_url(''); ?>">Pengumuman 1</a></li>
        <li class="hoverable"><a class="dropdown-button" href="<?php echo base_url(''); ?>">Pengumuman 2</a></li>
        <li class="hoverable"><a class="dropdown-button" href="<?php echo base_url(''); ?>">Pengumuman 3</a></li>
        <li class="divider" tabindex="-1"></li>
        <li class="hoverable"><a class="dropdown-button" href="<?php echo base_url('pengumuman'); ?>"><strong>Lihat Semuanya</strong></a></li>
    </ul>
    <header>
        <?php echo isset($headmenu) ? $headmenu : ''; ?>
    </header>

    <main>

        <!--MAINCONTENT -->
        <?php echo isset($maincontent) ? $maincontent : ''; ?>
        <!--MAINCONTENT -->
    </main>
    <?php echo isset($footer) ? $footer : ''; ?>
    <div id="modal_content">

    </div>

    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/materialize1/js/materialize.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/js/jquery-additional-methods.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/select2/dist/js/select2.full.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/jquery-confirm-v3.3.4/js/jquery-confirm.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/js/loaders.css.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/DataTables/Buttons-1.5.4/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/DataTables/Responsive-2.2.2/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/DataTables/FixedHeader-3.1.4/js/dataTables.fixedHeader.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/DataTables/RowGroup-1.1.0/js/dataTables.rowGroup.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/js/tableHeadFixer.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/DataTables/Plugins/dataRender/datetime.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/DataTables/Plugins/dataRender/ellipsis.js"></script>


    <?php //echo isset( $maincontent_general_script ) ? $maincontent_general_script : ''; 
    ?>
    <?php echo isset($maincontent_script) ? $maincontent_script : ''; ?>


    <?php echo isset($lib_js) ? $lib_js : ''; ?>
    <?php echo isset($js) ? $js : ''; ?>

    <!--Load Default JS Lib-->


</body>

</html>