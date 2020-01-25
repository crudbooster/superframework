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
</head>
<body>
<div id="app" class="container-fluid">
    <div id="wrapper">
        <div v-if="is_loading===true" class="loading">Loading&#8230;</div>
        <nav id="navbar-menu" class="navbar navbar-expand-lg navbar-dark bg-dark-gradient" style="display: none">
            <a class="navbar-brand" href="javascript:;" @click="$router.push('/dashboard')"><?php out(config('app_name'))?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul v-if="users_id!=''" class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:;" @click="$router.push('/dashboard')">Dashboard <span class="sr-only">(current)</span></a>
                    </li>
                    <!-- User Custom Navbar Menu After This -->

                </ul>
                <ul v-if="users_id!=''" class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span>{{ users_name }}</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="javascript:;" @click="$router.push('/profile')">Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo backend_url('logout')?>">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="wrapper-content">
            <router-view></router-view>
        </div><!--end wrapper-content-->
    </div><!--end wrapper-->
</div><!--end container-->
<script>
    const base_api = "<?php out(url('admin-api'))?>"
    const backend_path = "<?php out(config('backend_path'))?>"
</script>
<script type="module" src="<?php out(base_url('app/Vue/main.js'))?>"></script>
</body>
</html>