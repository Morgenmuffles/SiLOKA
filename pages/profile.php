<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h1>Profile</h1>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="index.php">Home</a></li>
<li class="breadcrumb-item active">User Profile</li>
</ol>
</div>
</div>
</div><!-- /.container-fluid -->
</section>
<section class="content">
<div class="container-fluid">
<div class="row">
<div class="col-md-3">

<!-- Profile Image -->
<div class="card card-primary card-outline">
<div class="card-body box-profile">
<div class="text-center">
<img class="profile-user-img img-fluid img-circle" 
     src="<?php echo $data_login['foto']; ?>" 
     alt="None" 
     style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">

</div>

<h3 class="profile-username text-center">
	<?php echo $data_login['nama_lengkap']; ?></h3>

<p class="text-muted text-center"><?php echo $data_login['jenis_kelamin']; ?></p>

<ul class="list-group list-group-unbordered mb-3">



</ul>

<a href="#" class="btn btn-primary btn-block"><b>Upload Photo</b></a>
</div>
<!-- /.card-body -->
</div>
<!-- /.card -->

<!-- About Me Box -->
<div class="card card-primary">
<div class="card-header">
<h3 class="card-title">About Me</h3>
</div>
<!-- /.card-header -->
<div class="card-body">
<strong><i class="fas fa-book mr-1"></i> Bio</strong>

<p class="text-muted">
<?php echo $data_login['bio']; ?>
</p>
<hr>
<strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
<p class="text-muted"><?php echo $data_login['alamat']; ?></p>
<hr>
</div>
<!-- /.card-body -->
</div>
<!-- /.card -->
</div>
<!-- /.col -->
<div class="col-md-9">
<div class="card">

<div class="card-body">
<div class="tab-content">

<!-- /.tab-pane -->

<!-- /.tab-pane -->
<div class="tab-pane active" id="settings">
<form class="form-horizontal">
<div class="form-group row">
<label for="inputName" class="col-sm-2 col-form-label">Nama</label>
<div class="col-sm-10">
<input type="text" class="form-control" id="inputName" placeholder="Nama" value="<?php echo $data_login['nama_lengkap']; ?>">
</div>
</div>
<div class="form-group row">
<label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
<div class="col-sm-10">
<input type="text" class="form-control" id="inputEmail" placeholder="Email" value="<?php echo $data_login['email']; ?>">
</div>
</div>
<div class="form-group row">
<label for="inputName2" class="col-sm-2 col-form-label">Bio</label>
<div class="col-sm-10">
<input type="text" class="form-control" id="inputName2" placeholder="Name" value="<?php echo $data_login['bio']; ?>">
</div>
</div>
<div class="form-group row">
<label for="inputExperience" class="col-sm-2 col-form-label">Alamat</label>
<div class="col-sm-10">
<textarea class="form-control" id="inputExperience" placeholder="Experience"><?php echo $data_login['alamat']; ?></textarea>
</div>
</div>


<div class="form-group row">
<div class="offset-sm-2 col-sm-10">
<button type="submit" class="btn btn-danger">Update</button>
</div>
</div>
</form>
</div>
<!-- /.tab-pane -->
</div>
<!-- /.tab-content -->
</div><!-- /.card-body -->
</div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div><!-- /.container-fluid -->
</section>