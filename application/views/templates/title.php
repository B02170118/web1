<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- loading -->
    <section id="loading">
        <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </section>
    <!-- loadin -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <?php if (!empty($title)) { ?>
            <h1><?php echo $title ?></h1>
        <?php   } ?>
        <ol class="breadcrumb">
            <li><a href="./<?php echo $this->uri->uri_string() ?>"><i class="fa fa-dashboard"></i><?php echo $this->uri->uri_string() ?></a></li>
            <!-- <li class="active">Dashboard</li> -->
        </ol>
    </section>

    <!-- alert -->
    <section id="alert-msg">
        <div id="alert-content"></div>
    </section>
    <!-- alert -->