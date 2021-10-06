<!DOCTYPE html>

<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="<?php echo $this->config->item('web_icon_url'); ?>">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/materialize1/css/materialize.css" media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/jquery-confirm-v3.3.4/css/jquery-confirm.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/css/styles.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/css/app.css" media="screen,projection">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('root_url'); ?>assets/css/loaders.min.css" media="screen,projection">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($page_title) ? $page_title : ''; ?> | <?php echo $this->config->item('app_name'); ?></title>

    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/js/jquery-3.3.1.min.js"></script>

    <style>
        .page {
            height: 100%;
            position: relative;
        }

        html,
        body {
            height: 100%;
        }

        .logo-on-small {
            padding: 0 !important;
            position: absolute;
            top: 11%;
            left: 50% !important;
            transform: translate(-50%, -50%);
        }

        .login-box {
            padding: 60px 30px 60px 30px !important;
        }

        .card-login {
            border-radius: 10px !important;
            position: absolute;
            left: 50% !important;
            transform: translate(-50%, -50%);
            top: 267px;
            padding: 12px 12px 12px 12px !important;
            box-shadow: 0px 14px 13px 8px rgba(0, 0, 0, 0.40);
        }

        .img-logo {
            width: 97%;
            height: auto;
            max-width: 370px;
            box-shadow: 0 2px 10px 3px rgba(0, 0, 0, 0.40);
            border-radius: 100px;
            background: linear-gradient(38deg, rgba(16, 40, 104, 1) 0%, rgba(40, 74, 164, 0.5410539215686274) 100%);
        }

        .submit-button {
            width: 100%;
        }

        div.fixed {
            position: fixed;
            bottom: 0;
            right: 0;
            display: none;
        }

        body {
            background-repeat: no-repeat;
            background-position-y: bottom;
            background-position-x: center;
            background: rgb(40, 74, 164);
            background: radial-gradient(circle, rgba(40, 74, 164, 0.43461134453781514) 0%, rgba(16, 40, 104, 1) 100%);

        }
    </style>
</head>

<body onload="loading();">
    <div id="loading" class="loading valign-wrapper">
        <div class="center" style="width: 100%;height: 62px;">
            <div class="loading-content"><img src="<?php echo $this->config->item('web_icon_url'); ?>">
            </div>
            <div class="preloader-wrapper big active">
                <div class="spinner-layer">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="row">
            <div class="col s10 m7 l6 logo-on-small" style="top: 68px;">
                <div class="col s12 center-align">
                    <img alt="" class="img-logo" src="<?php echo $this->config->item('root_url'); ?>assets/image/navlogo.png">
                </div>
            </div>
            <div class="col s10 m7 l6 card login-box card-login">
                <div class="col s12 center match-height amber lighten-5" style="border-radius: 10px; padding-block-start: 15px; padding-block-end: 15px;">
                    <form id="form_login" name="form_login" enctype="multipart/form-data">
                        <div class="input-field col s12" style="background: #fffcf1;">
                            <input id="gp_username" name="gp_username" type="text" class="validate" placeholder="Username">
                            <label for="gp_username" class="active">Username</label>
                        </div>
                        <div class="input-field col s12" style="background: #fffcf1;">
                            <input id="gp_password" name="gp_password" type="password" class="validate" placeholder="Password">
                            <label for="gp_password" class="active">Passsword</label>
                        </div>
                        <div class="input-field col s12">
                            <button class="btn blue darken-4 submit-button" type="submit" name="btn_login" id="btn_login">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed ip_address_div">
        <?php
        $ipserver = explode('.', $_SERVER['SERVER_ADDR']);
        $ip4 = $ipserver[3];
        $ip3 = $ipserver[2];
        ?>
        IP SERVER : <?php echo $_SERVER['SERVER_ADDR']; ?>
    </div>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/materialize1/js/materialize.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/js/jquery-additional-methods.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/jquery-confirm-v3.3.4/js/jquery-confirm.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('root_url'); ?>assets/js/loaders.css.js"></script>

    <script type="text/javascript">
        var preloader = document.getElementById("loading");

        function loading() {
            preloader.style.display = 'none';
        }

        $(document).ajaxStart(function() {
            $("#loading").show();
        }).ajaxStop(function() {
            $("#loading").hide();
        }).ajaxError(function() {
            $("#loading").hide();
        });


        $(function() {
            $(document).on('click', '#btn_login', function(event) {
                $("#form_login").validate({
                    rules: {
                        gp_password: {
                            required: true
                        },
                        gp_password: {
                            required: true
                        },
                    },
                    errorClass: 'invalid',
                    validClass: "valid",
                    ignore: ":hidden:not(select)",
                    errorPlacement: function(error, element) {
                    var ss = element.attr("id");
                    var ele = document.getElementById(ss);
                    var ele_tag = ele.tagName;

                    if (ele_tag === 'SELECT') {
                        $(element)
                            .parent('div')
                            .siblings("label[for='" + element.attr("id") + "']")
                            .append(error.css({
                                "margin-left": "7px",
                                "color": "red"
                            }));
                    } else {
                        $(element)
                            .siblings("label[for='" + element.attr("id") + "']")
                            .append(error.css({
                                "margin-left": "7px",
                                "color": "red"
                            }));
                    }

                },
                errorElement: "span",
                    success: function(error, element) {},
                    submitHandler: function(form) {
                        submit_login(form);
                    }
                });
            });
        });

        function submit_login(form) {
            var param = new FormData($(form)[0]);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('auth/do_login'); ?>",
                data: param,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    var obj = $.parseJSON(data);
                    if (parseInt(obj['res']) === 1) {
                        window.location = "<?php echo base_url('dashboard'); ?>";
                    } else {
                        if (parseInt(obj['res']) === 99) {
                            $.alert({
                                title: 'FAILED!',
                                content: obj['message'],
                                type: 'red',
                                draggable: false,
                            });
                        }
                    }
                }
            });
        }
    </script>

</body>

</html>
<!-- loading(); -->