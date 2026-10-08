
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Brand Logo -->
<a href="./" class="brand-link">
<img src="dist/img/PUPR.jpg" alt="Logo" class="brand-image elevation-3" style="opacity: .8;">
<span class="brand-text font-weight-light">SiLOKA PPK 1.5</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
<!-- Sidebar user (optional) -->
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
<div class="image">
    <img src="<?php echo $data_login['foto']; ?>" class="img-circle elevation-2" alt="User Image" 
    style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
</div>

<div class="info">
<a href="./?folder=pages&file=profile" class="d-block"><?php echo $data_login['nama_lengkap']; ?></a>
</div>
</div>
<!-- Sidebar Menu -->
<nav class="mt-2">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
<!-- Add icons to the links using the .nav-icon class
 with font-awesome or any other icon font library -->
<?php if($data_login['jabatan']=='Admin'){ ?> 
<li class="nav-item">
<a href="#" class="nav-link">
<i class="nav-icon fas fa-tachometer-alt"></i>
<p>
Data Admin
<i class="right fas fa-angle-left"></i>
</p>
</a>
<ul class="nav nav-treeview">
<li class="nav-item">
<a href="./?folder=pages&file=inputUser" class="nav-link">
<i class="far fa-circle nav-icon"></i>
<p>Data User</p>
</a>
</li>
<li class="nav-item">
<a href="./?folder=pages&file=dataKegiatan" class="nav-link">
<i class="far fa-circle nav-icon"></i>
<p>Data Kegiatan</p>
</a>
</li>
<li class="nav-item">
<a href="./?folder=pages&file=dataTitik" class="nav-link">
<i class="far fa-circle nav-icon"></i>
<p>Data Titik</p>
</a>
</li>
</ul>
</li>
<?php } ?>
<li class="nav-item">
<a href="./?folder=pages&file=inputswakelola" class="nav-link">
<i class="nav-icon fas fa-th"></i>
<p>Dokumentasi Kegiatan</p>
</a>
</li>
<li class="nav-item">
<a href="./?folder=pages&file=exportswakelola" class="nav-link">
<i class="nav-icon fas fa-th"></i>
<p>Export Dokumentasi Kegiatan</p>
</a>
</li>

</ul>
</nav>
<!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>