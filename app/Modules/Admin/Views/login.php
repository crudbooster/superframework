<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex">
    <title><?php out(config('app_name'))?></title>
    <link href="<?php echo base_url('assets/css/all.min.css')?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/js/all.min.js')?>"></script>
    <style>
        body {
            background: url(<?php echo base_url("assets/img/login-bg.jpg")?>) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        .login-wrapper {
            margin: 100px auto;
            width: 800px;
        }
        .login-wrapper .login-content {

        }
        .login-bg-blue {
            background-color: #267DFF;
            color: #ffffff;
            min-height: 400px;
            -webkit-border-top-left-radius: 20px;
            -webkit-border-bottom-left-radius: 20px;
            -moz-border-radius-topleft: 20px;
            -moz-border-radius-bottomleft: 20px;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
        }
        .login-bg-white {
            background-color: #ffffff;
            color: #666666;
            min-height: 400px;
            -webkit-border-top-right-radius: 20px;
            -webkit-border-bottom-right-radius: 20px;
            -moz-border-radius-topright: 20px;
            -moz-border-radius-bottomright: 20px;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            text-align: center;
        }
        .login-desc {
            padding: 80px 40px;
        }
        .login-desc h2 {
            padding: 0px 0px 10px 0px;
            font-size: 20px;
        }
        .login-desc p {
            font-size: 14px;
        }
        .login-form {
            padding: 60px 40px;
        }
        .login-form h2 {
            font-size: 25px;
            font-weight: bold;
        }
        .login-form .form-control {
            border: 0px;
            border-bottom: 1px solid #dddddd;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="login-wrapper">
        <div class="login-content">
            <div class="row">
                <div class="col-sm login-bg-blue">
                    <div class="login-desc">
                        <h2>Welcome to <?php out(config('app_name'))?></h2>
                        <div style="width:30%; background-color: #ffffff; height: 3px"></div>
                        <br>
                        <p>Lorem ipsum dolor sit amet</p>
                    </div>
                </div>
                <div class="col-sm login-bg-white">
                    <div class="login-form">
                        <h2>Sign In</h2>
                        <br>
                        <form method="post" autocomplete="off" action="">
                            <?php echo csrf_input()?>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="text" name="email" class="form-control" required >
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control" required >
                            </div>
                            <br>
                            <input type="submit" class="btn btn-block btn-primary" value="Login">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--end container-->
</body>
</html>