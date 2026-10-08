<html> 
<head> 
<meta charset="utf-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
<title>Data Login User</title> 
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<link rel="stylesheet" href="modules/data_login/css/bootstrap.min.css"> 
<link rel="stylesheet" href="modules/data_login/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css"> 
<link rel="stylesheet" href="modules/data_login/css/form-validation.css"> 
<link rel="stylesheet" href="modules/data_login/plugins/buttons-export/css/buttons.dataTables.min.css"> 
 
 
</head><body class="bg-light"> 
<div class="container p-3">
<p><button type="button" class="btn btn-outline-primary" onclick="location.href='?folder=modules/data_login/&file=input&act=insert';">Insert Record</button></p> 
<div class="card"> 
<div class="card-header"><h3 class="card-title">Data data_login</h3></div> 
<div class="card-body"> 
<?php include("connection.php"); 
$sql = "SELECT * FROM `data_login`"; 
$result = $conn->query($sql); ?> 
<table id="dataMightyWeb"> 
<thead>
<tr><th>Photo</th>
<th>Nama Lengkap</th>
<th>Jenis Kelamin</th>
<th>Alamat</th>
<th>Bio</th>
<th>Email</th>
<th>Password</th>
<th>Actions</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?><tr>
<td><?php echo $row["photo"]; ?></td>
<td><?php echo $row["nama_lengkap"]; ?></td>
<td><?php echo $row["jenis_kelamin"]; ?></td>
<td><?php echo $row["alamat"]; ?></td>
<td><?php echo $row["bio"]; ?></td>
<td><?php echo $row["email"]; ?></td>
<td><?php echo $row["password"]; ?></td>
<td><a class="btn btn-sm btn-outline-primary" href="?folder=modules/data_login/&file=input&act=update&id_login=<?php echo $row["id_login"]; ?>">Edit</i></a> 
<a class="btn btn-sm btn-outline-warning" href="?folder=modules/data_login/actions.php?act=delete&id_login=<?php echo $row["id_login"]; ?>">Delete</a></td> 
</tr><?php } ?>
</tbody> 
</table> 
</div></div>
</div>
<script src="modules/data_login/js/jquery.min.js"></script> 
<script src="modules/data_login/js/bootstrap.min.js"></script> 
<script src="modules/data_login/plugins/datatables-bs4/js/jquery.dataTables.min.js"></script> 
<script src="modules/data_login/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script> 
<script src="modules/data_login/plugins/buttons-export/js/dataTables.buttons.min.js"></script> 
<script src="modules/data_login/plugins/buttons-export/js/buttons.html5.min.js"></script> 
<script src="modules/data_login/plugins/buttons-export/js/jszip.min.js"></script> 
<script src="modules/data_login/plugins/buttons-export/js/pdfmake.min.js"></script> 
<script src="modules/data_login/plugins/buttons-export/js/vfs_fonts.js"></script> 
 
 
<script type="text/javascript"> 
$(function () { 
$("#dataMightyWeb").addClass("table table-hover table-bordered "); 
var table = $("#dataMightyWeb").DataTable({
"searching": true,
"paging": true,
lengthMenu: [[ 10, 25, 50, -1 ],[ '10', '25', '50', 'Show all' ]],
"ordering": true,
"info": true,
 dom: 'Bfrtip',  buttons: [
{extend: 'excelHtml5', exportOptions: { columns: ':visible' }},
{extend: 'pdfHtml5', download: 'open', exportOptions: { columns: ':visible' }}, 'pageLength',]}); 
 
 
 
}); 
</script> 
</body></html>