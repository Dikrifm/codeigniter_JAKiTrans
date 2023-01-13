<div class="content-wrapper">
    <div class="card">
        <div class="card-body">

            <!-- FLASH DATA-> Notification -->
            <?php if ($this->session->flashdata('ubah')) : ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $this->session->flashdata('ubah'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('demo')) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $this->session->flashdata('demo'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('hapus')) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $this->session->flashdata('hapus'); ?>
                </div>
            <?php endif; ?>


            <div>
            <a class="btn btn-info" href="<?= base_url(); ?>payments/tambah"><i class="mdi mdi-plus-circle-outline"></i>Tambah Payment Method</a>
            </div>
            <br>
            
            <!-- TAB-MINIMAL -->
            <div class="tab-minimal tab-minimal-success">

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="nav-topup" data-toggle="tab" href="#tab-topup" role="tab" aria-controls="tab-topup" aria-selected="true">
                        <i class="mdi mdi-rotate-3d"></i>Top up Method</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="nav-withdraw" data-toggle="tab" href="#tab-withdraw" role="tab" aria-controls="tab-withdraw" aria-selected="false">
                            <i class="mdi mdi-import"></i>Withdraw Method</a>
                    </li>
                </ul>
                
                <div class="tab-content">

                <!-- TAB-TOPUP -->
                <div class="tab-pane fade show active" id="tab-topup" role="tabpanel" aria-labelledby="tab-topup">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        
                                        <!-- TABLE TOPUP -->
                                        <table id="order-listing" class="table table-striped table-hover dt-responsive display nowrap" data-info="false" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Logo</th>
                                                    <th>Tipe</th>
                                                    <th>Jenis</th>
                                                    <th>Nama</th>
                                                    <th>Keterangan</th>
                                                    <th>Biaya</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php 
                                                $i = 1;
                                                foreach ($payment as $s) {
                                                if($s['tipe'] == 1){ 
                                                ?>

                                                    <tr>
                                                        <td><?= $s['id']; ?></td>
                                                        <td>
                                                            <div class="badge badge-primary">
                                                                <img width="80" height="80" class="avatar-img rounded-circle" src="<?= base_url('images/promo/') . $s['image']; ?>">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php if($s['tipe'] > 0){ ?>
                                                                <label class="badge badge-success">Online</label>
                                                            <?php }else{ ?>
                                                                <label class="badge badge-danger">Offline</label>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php if($s['jenis'] == 4){ ?>
                                                                <label class="badge badge-info">Manual Transfer</label>
                                                            <?php }else if($s['jenis'] == 1){ ?>
                                                                <label class="badge badge-info">Ewallet</label>
                                                            <?php }else if($s['jenis'] == 2){ ?>
                                                                <label class="badge badge-info">Virtual Account</label>
                                                            <?php }else if($s['jenis'] == 3){ ?>
                                                                <label class="badge badge-info">Retail Outlet</label>
                                                            
                                                            <?php } ?>
                                                        </td>
                                                        <td><?= $s['nama']; ?></td>
                                                        <td><?= $s['keterangan']; ?></td>
                                                        <td><?= number_format($s['biaya'], '0',',','.'); ?></td>
                                                        <td>
                                                            <?php if ($s['status'] == 1) { ?>
                                                                <label class="badge badge-success">Active</label>
                                                            <?php } else { ?>
                                                                <label class="badge badge-danger">Non Active</label>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?= base_url(); ?>payments/ubah/<?= $s['id']; ?>">
                                                                <button class="btn btn-outline-primary">Edit</button>
                                                            </a>
                                                            <a href="<?= base_url(); ?>payments/hapus/<?= $s['id']; ?>" onclick="return confirm ('are you sure?')">
                                                            <button class="btn btn-outline-danger">Delete</button>
                                                            </a>
                                                        </td>
                                                    <?php 
                                                    $i++;
                                                    }
                                                    } 
                                                    ?>
                                                    </tr>

                                            </tbody>
                                        </table> <!-- /.table TOP UP -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.TAB-TOPUP-->

                <!-- TAB-withdraw -->
                <div class="tab-pane fade" id="tab-withdraw" role="tabpanel" aria-labelledby="tab-withdraw">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        
                                        <!-- TABLE withdraw -->
                                        <table id="order-listing" class="table table-striped table-hover dt-responsive display nowrap" data-info="false" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Logo</th>
                                                    <th>Tipe</th>
                                                    <th>Jenis</th>
                                                    <th>Nama</th>
                                                    <th>Keterangan</th>
                                                    <th>Biaya</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $i = 1;
                                                foreach ($payment as $s){ 
                                                if($s['tipe'] == 2){
                                                ?>
                                                    <tr>
                                                        <td><?= $s['id']; ?></td>
                                                        <td>
                                                            <div class="badge badge-primary">
                                                                <img width="80" height="80" class="avatar-img rounded-circle" src="<?= base_url('images/promo/') . $s['image']; ?>">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php if($s['tipe'] > 0){ ?>
                                                                <label class="badge badge-success">Online</label>
                                                            <?php }else{ ?>
                                                                <label class="badge badge-danger">Offline</label>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php if($s['jenis'] == 4){ ?>
                                                                <label class="badge badge-info">Manual Transfer</label>
                                                            <?php }else if($s['jenis'] == 1){ ?>
                                                                <label class="badge badge-info">Ewallet</label>
                                                            <?php }else if($s['jenis'] == 2){ ?>
                                                                <label class="badge badge-info">Virtual Account</label>
                                                            <?php }else if($s['jenis'] == 3){ ?>
                                                                <label class="badge badge-info">Retail Outlet</label>
                                                            <?php }else if($s['jenis'] == 4){ ?>
                                                                <label class="badge badge-alert">Withdraw Bank</label>
                                                            <?php } ?>
                                                        </td>
                                                        <td><?= $s['nama']; ?></td>
                                                        <td><?= $s['keterangan']; ?></td>
                                                        <td><?= number_format($s['biaya'], '0',',','.'); ?></td>
                                                        <td>
                                                            <?php if ($s['status'] == 1) { ?>
                                                                <label class="badge badge-success">Active</label>
                                                            <?php } else { ?>
                                                                <label class="badge badge-danger">Non Active</label>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?= base_url(); ?>payments/ubah/<?= $s['id']; ?>">
                                                                <button class="btn btn-outline-primary">Edit</button>
                                                            </a>
                                                            <a href="<?= base_url(); ?>payments/hapus/<?= $s['id']; ?>" onclick="return confirm ('are you sure?')">
                                                            <button class="btn btn-outline-danger">Delete</button></a>
                                                        </td>
                                                <?php 
                                                $i++;
                                                } 
                                                }
                                                ?>
                                                </tr>

                                            </tbody>
                                        </table> <!-- /.table TOP UP -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.TAB-withdraw-->

                </div><!-- /.TAB-Content -->
            </div><!-- /.TAB-Minimal -->
       
</div><!-- /.CARD-Body -->

</div><!-- /.CARD-->
</div><!-- /.Content-wrapper