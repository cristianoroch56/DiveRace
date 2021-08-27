<!DOCTYPE html>
<html <?php language_attributes (); ?>>
    <head>
        <?php do_action ( 'fl_head_open' ); ?>
        <meta charset="<?php bloginfo ( 'charset' ); ?>" />
        <?php echo apply_filters ( 'fl_theme_viewport', "<meta name='viewport' content='width=device-width, initial-scale=1.0' />\n" ); ?>
        <?php echo apply_filters ( 'fl_theme_xua_compatible', "<meta http-equiv='X-UA-Compatible' content='IE=edge' />\n" ); ?>
        <link rel="profile" href="https://gmpg.org/xfn/11" />
        <?php
        global $post;
        $current_post_slug = $post->post_title;
        ?>
        <title><?php echo $current_post_slug; ?> - DiveRACE</title>
        <!-- iCheck -->
        <!-- <link rel="stylesheet" href="<?php //echo FL_CHILD_THEME_URL.'/dashboard-assets/css/icheck-bootstrap.min.css';                                                                          ?>"> -->

        <link rel="stylesheet" href="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/css/bootstrap.min.css'; ?>">
        <link rel="stylesheet" href="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/css/bootstrap-datetimepicker.min.css'; ?>">

        <!-- Default style -->
        <link rel="stylesheet" href="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/css/default.css'; ?>">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/css/OverlayScrollbars.min.css'; ?>">
        <link rel="stylesheet" href="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/css/slick.min.css'; ?>">
        <link rel="stylesheet" href="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/css/slick-theme.css'; ?>">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/fontawesome-free/css/all.min.css'; ?>">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo 'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'; ?>">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <!-- Custom style -->
        <link rel="stylesheet" href="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/css/style.css'; ?>">
        <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
        <!-- <script src="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/js/jquery-3.3.1.min.js'; ?>"></script> -->
        <script src="<?php echo site_url () . '/wp-content/plugins/gravityforms/js/gravityforms.min.js?ver=2.4.23'; ?>"></script>

        <?php
        //wp_head();
        //FLTheme::head();	
        if( is_user_logged_in () ) {
            $current_user = wp_get_current_user ();
            $userID       = $current_user->ID;
            if( class_exists ( 'acf' ) ) {
                $genreal_img = get_field ( 'user_profile_general_image', 'options' );
                $user        = new WP_User ( $userID );
                if( ! empty ( $user->roles ) && is_array ( $user->roles ) ) {
                    foreach ( $user->roles as $role ) {
                        if( $role == 'agent' ) {
                            $user_current_id = $user->ID;
                            $user_image      = get_field ( 'user_image', 'user_' . $user_current_id );
                            if( ! is_array ( $user_image ) ) {
                                $user_image = array ();
                            }
                            $user_image[] = $user_current_id;
                            update_field ( 'user_image', $user_image, 'user_' . $user_current_id );
                        } elseif( $role == 'subscriber' ) {
                            $user_image = get_field ( 'user_image', 'user_' . $userID );
                            $user_image = ! empty ( $user_image ) ? $user_image : $genreal_img;
                        } else {
                            $user_image = get_field ( 'user_profile_general_image', 'options' );
                        }
                    }
                }
            }
        }
        ?>
    </head>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto" >
                    <!-- Notifications Dropdown Menu -->
                    <li class="nav-item dropdown" style="display:none">
                        <a class="nav-link notification" data-toggle="dropdown" href="#">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-black navbar-badge">.</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-item dropdown-header">15 Notifications</span>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-envelope mr-2"></i> 4 new messages
                                <span class="float-right text-muted text-sm">3 mins</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-users mr-2"></i> 8 friend requests
                                <span class="float-right text-muted text-sm">12 hours</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-file mr-2"></i> 3 new reports
                                <span class="float-right text-muted text-sm">2 days</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                        </div>
                    </li>

                    <!-- User Dropdown Menu -->
                    <li class="nav-item dropdown user-menu-wrapper">
                        <?php if( ! empty ( $user_image ) ) { ?>
                            <a class="nav-link" data-toggle="dropdown" href="#">
                                <div class="user-panel">
                                    <div class="image">
                                        <img src="<?php echo $user_image[ 'url' ]; ?>" class="img-circle" alt="User Image">
                                        <i class="fa fa-angle-down"></i>
                                    </div>
                                </div>
                            </a>
                        <?php } ?>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <a class="dropdown-item" href="<?php echo home_url () . '/dashboard-account-settings'; ?>">
                                <span><i class="nav-icon fas fa-edit" aria-hidden="true"></i>Account Settings</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo wp_logout_url ( esc_url ( home_url ( '/' ) ) ); ?>">
                                <span><i class="fas fa-sign-out-alt"></i>Log Out</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary">
                <!-- Brand Logo -->
                <a href="<?php echo home_url (); ?>" class="brand-link">
                    <img src="<?php echo FL_CHILD_THEME_URL . '/dashboard-assets/images/logo.png'; ?>" alt="Diverace Logo" class="logo"
                         style="opacity: .8">
                    <span class="brand-text font-weight-light">Dive Race</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                            <?php
                            global $post;
                            $current_post_slug = $post->post_name;
                            $add_class_1       = '';
                            $add_class_2       = '';
                            $add_class_3       = '';
                            $add_class_4       = '';
                            $add_class_5       = '';
                            $add_class_6       = '';
                            $add_class_7       = '';

                            if( $current_post_slug == 'dashboard-index' ) {
                                $add_class_1 = 'active';
                            } else {
                                $add_class_1 = '';
                            }
                            if( $current_post_slug == 'dashboard-add-ons' ) {
                                $add_class_2 = 'active';
                            } else {
                                $add_class_2 = '';
                            }
                            if( $current_post_slug == 'dashboard-history' ) {
                                $add_class_3 = 'active';
                            } else {
                                $add_class_3 = '';
                            }
                            if( $current_post_slug == 'dashboard-payment-methods' ) {
                                $add_class_4 = 'active';
                            } else {
                                $add_class_4 = '';
                            }
                            if( $current_post_slug == 'dashboard-testimonial-form' ) {
                                $add_class_5 = 'active';
                            } else {
                                $add_class_5 = '';
                            }
                            if( $current_post_slug == 'dashboard-account-settings' ) {
                                $add_class_6 = 'active';
                            } else {
                                $add_class_6 = '';
                            }
                            if( $current_post_slug == 'dashboard-share' ) {
                                $add_class_7 = 'active';
                            } else {
                                $add_class_7 = '';
                            }

                            $userID = '';
                            if( is_user_logged_in () ) {
                                $current_user = wp_get_current_user ();
                                $user_id      = $current_user->ID;
                                $user         = new WP_User ( $user_id );

                                if( ! empty ( $user->roles ) && is_array ( $user->roles ) ) {
                                    foreach ( $user->roles as $role )
                                        if( $role == 'agent' ) {
                                            ?>
                                            <li class="nav-item">
                                                <a href="<?php echo home_url () . '/dashboard-index'; ?>" class="nav-link <?php echo $add_class_1; ?>">
                                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                                    <p>Dashboard</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo home_url () . '/dashboard-add-ons'; ?>" class="nav-link <?php echo $add_class_2; ?>">
                                                    <i class="nav-icon fas fa-th"></i>
                                                    <p> Add-Ons </p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo home_url () . '/dashboard-history'; ?>" class="nav-link <?php echo $add_class_3; ?>">
                                                    <i class="nav-icon fas fa-copy"></i>
                                                    <p> History </p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo home_url () . '/dashboard-payment-methods'; ?>" class="nav-link <?php echo $add_class_4; ?>">
                                                    <i class="nav-icon fas fa-tree"></i>
                                                    <p>Payment Methods</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo home_url () . '/dashboard-account-settings'; ?>" class="nav-link <?php echo $add_class_6; ?>">
                                                    <i class="nav-icon fas fa-edit"></i>
                                                    <p>Account Settings</p>
                                                </a>
                                            </li>
                                            <?php
                                        } elseif( $role == 'subscriber' || $role = 'administrator' ) {
                                            $args = array (
                                                'post_type'   => 'orders',
                                                'post_status' => 'publish',
                                                'meta_query'  => array (
                                                    'relation' => 'AND',
                                                    array (
                                                        'key'     => 'order_status',
                                                        'value'   => 'Trip Completed',
                                                        'compare' => '=',
                                                    ),
                                                    array (
                                                        'key'     => 'user_id',
                                                        'value'   => $user_id,
                                                        'compare' => '=',
                                                    ),
                                                ),
                                            );

                                            $order_datas = get_posts ( $args );

                                            $i          = 0;
                                            $order_data = [];

                                            foreach ( $order_datas as $order_list ) {
                                                if( ! empty ( $user_id ) ) {
                                                    $order_id = $order_list->ID;
                                                }
                                            }
                                            ?>
                                            <li class="nav-item">
                                                <a href="<?php echo home_url () . '/dashboard-index'; ?>" class="nav-link <?php echo $add_class_1; ?>">
                                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                                    <p>Dashboard</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo home_url () . '/dashboard-add-ons'; ?>" class="nav-link <?php echo $add_class_2; ?>">
                                                    <i class="nav-icon fas fa-th"></i>
                                                    <p> Add-Ons </p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo home_url () . '/dashboard-history'; ?>" class="nav-link <?php echo $add_class_3; ?>">
                                                    <i class="nav-icon fas fa-copy"></i>
                                                    <p> History </p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo home_url () . '/dashboard-payment-methods'; ?>" class="nav-link <?php echo $add_class_4; ?>">
                                                    <i class="nav-icon fas fa-tree"></i>
                                                    <p>Payment Methods</p>
                                                </a>
                                            </li>
                                            <?php if( ! empty ( $order_id ) ) { ?>
                                                <li class="nav-item">
                                                    <a href="<?php echo home_url () . '/dashboard-testimonial-form'; ?>" class="nav-link <?php echo $add_class_5; ?>">
                                                        <i class="nav-icon fas fa-edit"></i>
                                                        <p>Testimonials</p>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <li class="nav-item">
                                                <a href="<?php echo home_url () . '/dashboard-account-settings'; ?>" class="nav-link <?php echo $add_class_6; ?>">
                                                    <i class="nav-icon fas fa-edit"></i>
                                                    <p>Account Settings</p>
                                                </a>
                                            </li>
                                            <!-- <li class="nav-item">
                                                 <a href="<?php echo home_url () . '/dashboard-share'; ?>" class="nav-link <?php echo $add_class_7; ?>">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>Share</p>
                                    </a>
                                    </li> -->   
                                            <?php
                                        } else {
                                            ?>
                                            <li class="nav-item">
                                                <a href="<?php echo home_url () . '/dashboard-index'; ?>" class="nav-link <?php echo $add_class_1; ?>">
                                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                                    <p>Dashboard</p>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                }
                            }
                            ?>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">