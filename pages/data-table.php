<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Table</h1>
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
<div class="col-12">
  <div class="card">
              <div class="card-header">
                <h3 class="card-title">Striped Full Width Table</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">


                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10px">No</th>
                      <th>Nama</th>
                      <th>Bio</th>
                      <th>Email</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $mysql = "SELECT * FROM data_login"; 
                    $eksekusi = $conn->query($mysql);
                    $no = 1;
                    while($data = $eksekusi->fetch_assoc()) { ?> 
                    <tr>
                      <td><?php echo $no; ?></td>
                      <td><?php echo $data['nama_lengkap']; ?></td>
                      <td><?php echo $data['bio']; ?></td>
                      <td><?php echo $data['email']; ?></td>
                    </tr>
                    <?php $no++;} ?>
                  </tbody>
                </table>



              </div>
              <!-- /.card-body -->
            </div>
</div>
</section>