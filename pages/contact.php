<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h1>Contacts</h1>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Contacts</li>
</ol>
</div>
</div>
</div><!-- /.container-fluid -->
</section>
<section class="content">

<!-- Default box -->
<div class="card card-solid">
<div class="card-body pb-0">
<div class="row">
<?php
$query = $conn->query("SELECT * FROM `data_login`");
while($data = $query->fetch_assoc()){ // untuk looping data (mengulang data)
?>

<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
<div class="card bg-light d-flex flex-fill">
<div class="card-header text-muted border-bottom-0">
<?php echo $data['jenis_kelamin']; ?>
</div>
<div class="card-body pt-0">
<div class="row">
<div class="col-7">
<h2 class="lead"><b><?php echo $data['nama_lengkap']; ?></b></h2>
<p class="text-muted text-sm"><b>About: <?php echo $data['bio']; ?></b> </p>
<ul class="ml-4 mb-0 fa-ul text-muted">
<li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span><?php echo $data['alamat']; ?></li>
<li class="small"><span class="fa-li"><i class="fas fa-lg fa-envelope"></i></span><?php echo $data['email']; ?></li>
</ul>
</div>
<div class="col-5 text-center">
<img src="modules/data_login/uploads/<?php echo $data['photo']; ?>" alt="user-avatar" class="img-circle img-fluid">
</div>
</div>
</div>
<div class="card-footer">
<div class="text-right">
<a href="#" class="btn btn-sm bg-teal">
<i class="fas fa-comments"></i>
</a>
<a href="#" class="btn btn-sm btn-primary">
<i class="fas fa-user"></i> View Profile
</a>
</div>
</div>
</div>
</div>
<?php } ?>

</div>
</div>
<!-- /.card-body -->
<div class="card-footer">

</div>
<!-- /.card-footer -->
</div>
<!-- /.card -->

</section>