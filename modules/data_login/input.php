<html> 
<head> 
<meta charset="utf-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
<meta name="viewport" content="width=1024,height=device-height"> 
<title>Data Login User</title> 
<link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
 
</head><body class="bg-light"> 
<div class="container p-3">
<div class="card bg-light mb-3"> 
<form class="needs-validation mb-0" novalidate method="post" action="modules/data_login/actions.php?act=<?php echo $_GET["act"]; ?>&id_login=<?php echo $_GET["id_login"]; ?>" enctype="multipart/form-data"> 
<div class="card-body bg-white"> 
<?php if ($_GET["act"]=="update"){ include("connection.php"); 
$sql = "SELECT * FROM `data_login` WHERE `id_login` = '$_GET[id_login]' "; 
$result = $conn->query($sql); 
$row = $result->fetch_assoc();  
 
 
?> 
<div class="form-group"><h1>Update Record</h1></div> 
<div class="form-group"><label>Photo</label>
<div class="custom-file">
<input type="hidden" value="<?php echo $row[photo]; ?>" name="photo"> 
<input type="file" class="custom-file-input" id="input_file1" name="photo">
<label class="custom-file-label"><?php echo $row["photo"]; ?></label>
</div>
<div class="invalid-feedback">Error: this field is required.</div>
</div><div class="form-group"><label for="nama_lengkap">Nama Lengkap</label>
<input type="text" class="form-control" required="required" name="nama_lengkap" data-toggle="datetimepicker" value="<?php echo $row['nama_lengkap']; ?>">
<div class="invalid-feedback">Error: this field is required.</div>
</div><div class="form-group"><label>Jenis Kelamin</label>
<div class="col-12"><div class="custom-control custom-radio">
<input type="radio" class="custom-control-input" value="Pria" name="jenis_kelamin" id="jenis_kelamin-1" required="required" <?php if ($row['jenis_kelamin'] == 'Pria'){ echo 'checked'; } ?> />
<label class="custom-control-label" for="jenis_kelamin-1">Gentleman</label></div>
<div class="custom-control custom-radio">
<input type="radio" class="custom-control-input" value="Wanita" name="jenis_kelamin" id="jenis_kelamin-2" required="required" <?php if ($row['jenis_kelamin'] == 'Wanita'){ echo 'checked'; } ?> />
<label class="custom-control-label" for="jenis_kelamin-2">Ladies</label></div></div>
<div class="invalid-feedback">Error: this field is required.</div>
</div><div class="form-group"><label for="alamat">Alamat</label>
<textarea type="text" class="form-control" required="required" name="alamat" id="summernote"><?php echo $row['alamat']; ?></textarea>
<div class="invalid-feedback">Error: this field is required.</div>
</div><div class="form-group"><label for="bio">Bio</label>
<input type="text" class="form-control" required="required" name="bio" data-toggle="datetimepicker" value="<?php echo $row['bio']; ?>">
<div class="invalid-feedback">Error: this field is required.</div>
</div><div class="form-group"><label for="email">Email</label>
<input type="text" class="form-control" required="required" name="email" data-toggle="datetimepicker" value="<?php echo $row['email']; ?>">
<div class="invalid-feedback">Error: this field is required.</div>
</div><div class="form-group"><label for="password">Kata Sandi Login</label>
<input type="password" class="form-control" required="required" name="password" data-toggle="datetimepicker">
<div class="invalid-feedback">Error: this field is required.</div>
</div> 
<?php }else{ ?> 






<div class="form-group" id="title-form"><h1>Data Login User</h1></div>

<div class="form-group"><label>Photo</label>
<div class="custom-file">
<input type="file" class="custom-file-input" id="input_file1" name="photo" required="required">
<label class="custom-file-label">Choose file</label>
</div>
<div class="invalid-feedback">Error: this field is required.</div>
</div><div class="form-group"><label for="nama_lengkap">Nama Lengkap</label>
<input type="text" class="form-control" required="required" name="nama_lengkap" data-toggle="datetimepicker">
<div class="invalid-feedback">Error: this field is required.</div>
</div>

<div class="form-group"><label>Jenis Kelamin</label>
<div class="col-12"><div class="custom-control custom-radio">
<input type="radio" class="custom-control-input" value="Pria" name="jenis_kelamin" id="jenis_kelamin-1" required="required">
<label class="custom-control-label" for="jenis_kelamin-1">Gentleman</label></div>
<div class="custom-control custom-radio">
<input type="radio" class="custom-control-input" value="Wanita" name="jenis_kelamin" id="jenis_kelamin-2" required="required">
<label class="custom-control-label" for="jenis_kelamin-2">Ladies</label></div></div>
<div class="invalid-feedback">Error: this field is required.</div>
</div>
<div class="form-group"><label for="alamat">Alamat</label>
<textarea type="text" class="form-control" required="required" name="alamat" id="summernote"></textarea>
<div class="invalid-feedback">Error: this field is required.</div>
</div>
<div class="form-group"><label for="bio">Bio</label>
<input type="text" class="form-control" required="required" name="bio" data-toggle="datetimepicker">
<div class="invalid-feedback">Error: this field is required.</div>
</div>
<div class="form-group"><label for="email">Email</label>
<input type="text" class="form-control" required="required" name="email" data-toggle="datetimepicker">
<div class="invalid-feedback">Error: this field is required.</div>
</div>
<div class="form-group"><label for="password">Kata Sandi Login</label>
<input type="password" class="form-control" required="required" name="password" data-toggle="datetimepicker">
<div class="invalid-feedback">Error: this field is required.</div>
</div>
 
<?php } ?> 
</div> 
<div class="card-footer bg-light border-dark"> 
<a class="btn border-dark" onClick="history.back()">Cancel</a>  
<button type="submit" class="btn btn-primary float-right">Submit</button> 
</div> 
</form> 
</div>
</div>
<script src="modules/data_login/js/jquery.min.js"></script> 
<script src="modules/data_login/js/bootstrap.bundle.min.js"></script> 
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script> 
$(function () {
    // Summernote
    $('#summernote').summernote()
})
  
(function() {"use strict";window.addEventListener("load", function() { 
var forms = document.getElementsByClassName("needs-validation"); 
var validation = Array.prototype.filter.call(forms, function(form) { 
form.addEventListener("submit", function(event) { 
if (form.checkValidity() === false) { 
event.preventDefault(); 
event.stopPropagation(); 
}form.classList.add("was-validated");}, false);});}, false);})(); 
 
</script> 
</body></html>