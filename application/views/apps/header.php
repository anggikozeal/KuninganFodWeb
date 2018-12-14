<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">
    <title>Food Marketplace</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/dashboard.css'); ?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/js/jquery.js');?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
  </head>

  <body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Food Marketplace</a>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="#">Sign out</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if($this->uri->segment(2)==null) echo "active"; ?>" href="<?php echo site_url('apps/'); ?>">
                  <span data-feather="shopping-cart"></span>
                  Request Pencairan <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item" <?php if($this->uri->segment(2)!="riwayat") echo "active"; ?>>
                <a class="nav-link" href="<?php echo site_url('apps/riwayat/'); ?>">
                  <span data-feather="bar-chart-2"></span>
                  Riwayat Pencairan
                </a>
              </li>
              <li class="nav-item" <?php if($this->uri->segment(2)!="petunjuk") echo "active"; ?>>
                <a class="nav-link" href="<?php echo site_url('apps/petunjuk/'); ?>">
                  <span data-feather="layers"></span>
                  Petunjuk Penggunaan
                </a>
              </li>
              <li class="nav-item" <?php if($this->uri->segment(2)!="tentang_aplikasi") echo "active"; ?>>
                <a class="nav-link" href="<?php echo site_url('apps/tentang_aplikasi/'); ?>">
                  <span data-feather="file"></span>
                  Tentang Aplikasi
                </a>
              </li>
            </ul>
          </div>
        </nav>
