<div class="content-wrapper">
    <div class="card">
        <div class="card-body">

            <!-- FLASH DATA-> Notification -->
            <?php if ($this->session->flashdata('ubah')) : ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $this->session->flashdata('ubah'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('hapus')) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $this->session->flashdata('hapus'); ?>
                </div>
            <?php endif; ?>


            <div>
                <a class="btn btn-info" href="<?= base_url(); ?>payments/insert_qr"><i class="mdi mdi-plus-circle-outline"></i>Tambah QR Code Event</a>
            </div>
            <br>
            
            <!-- TAB-MINIMAL -->
            <div class="tab-minimal tab-minimal-success">

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="nav-topup" data-toggle="tab" href="#tab-topup" role="tab" aria-controls="tab-topup" aria-selected="true">
                        <i class="mdi mdi-rotate-3d"></i>QR Event list</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="nav-withdraw" data-toggle="tab" href="#tab-withdraw" role="tab" aria-controls="tab-withdraw" aria-selected="false">
                            <i class="mdi mdi-import"></i>QR Event Expired</a>
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
                                                    <th>QR Code Img</th>
                                                    <th>Tipe</th>
                                                    <th>Status</th>
                                                    <th>Nama</th>
                                                    <th>Nominal</th>
                                                    <th>Expired</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            <?php
                                                $i=1;
                                                foreach($qr_data as $qr){
                                                
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?= $i ?>
                                                    </td>
                                                    <td>
                                                        <div class="badge badge-primary">
                                                            <?php
                                                                $path_valid = base_url()."images/qr/".$qr['image_path'];
                                                                if($qr['image_path'] == NULL){
                                                            ?>
                                                                    <img width="80" height="80" class="avatar-img" src="<?= base_url('images/') . 'no_image.png'; ?>">
                                                            <?php }else{ ?>
                                                                    <img width="80" height="80" class="avatar-img" src="<?= $path_valid; ?>">
                                                                    
                                                            <?php
                                                                }
                                                            ?>

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if($qr['tipe'] == "STATIC") {?>
                                                            <label class="badge badge-info">"STATIC"</label>
                                                        <?php }else{ ?>
                                                            <label class="badge badge-info">"DYNAMIC"</label>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if($qr['status'] == 1) {?>
                                                            <label class="badge badge-success">Active</label>
                                                        <?php }else{ ?>
                                                            <label class="badge badge-danger">Non-Active</label>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?= $qr['nama_event'] ?>
                                                    </td>
                                                    <td>
                                                        Rp. <?= number_format($qr['nominal'], 0, ',', '.') ?>
                                                    </td>
                                                    <td>
                                                        <?= $qr['expired_date'] ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url(); ?>payments/edit_qr/<?= $qr['id']; ?>">
                                                            <button class="btn btn-primary">Edit</button>
                                                        </a>
                                                        <a href="<?= base_url(); ?>payments/delete_qr/<?= $qr['id']; ?>" onclick="return confirm ('Are you sure?')">
                                                            <button class="btn btn-outline-danger">Delete</button>
                                                        </a>
                                                        <a href="<?= base_url(); ?>payments/print_qr/<?= $qr['id']; ?>">
                                                            <button class="btn btn-outline-secondary">Cetak</button>
                                                        </a>
                                                    </td>

                                                </tr>
                                            <?php
                                                $i++;
                                                }

                                            ?>
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
                                                    <th>QR Code Img</th>
                                                    <th>Tipe</th>
                                                    <th>Status</th>
                                                    <th>Nama</th>
                                                    <th>Nominal</th>
                                                    <th>Expired</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

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