<!-- partial -->
 <div class="container-fluid mt-xl-50 mt-sm-30 mt-15 px-xxl-65 px-xl-20">
    <div class="card">
        <div class="card-body">
            <?php if ($this->session->flashdata('demo') or $this->session->flashdata('hapus')) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $this->session->flashdata('demo'); ?>
                    <?php echo $this->session->flashdata('hapus'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('ubah') or $this->session->flashdata('tambah')) : ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $this->session->flashdata('ubah'); ?>
                    <?php echo $this->session->flashdata('tambah'); ?>
                </div>
            <?php endif; ?>

            <div class="tab-minimal tab-minimal-success">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-2-1" data-toggle="tab" href="#ride-2-1" role="tab" aria-controls="ride-2-1" aria-selected="true">
                            Advertorial
                        </a>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-2-2" data-toggle="tab" href="#car-2-2" role="tab" aria-controls="car-2-2" aria-selected="false">
                            CATEGORY
                        </a>
                    </li>
                </ul>


                <div class="tab-content">

                    <div class="tab-pane fade show active" id="ride-2-1" s="s" role="tabpanel" aria-labelledby="tab-2-1">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <a class="btn btn-info" href="<?= base_url(); ?>advertorial/tambah"><i class="mdi mdi-plus-circle-outline"></i>Add Advertorial</a>
                                </div>
                                <br>
                                <h4 class="card-title">Advertorial</h4>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="order-listing" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Image</th>
                                                        <th>Title</th>
                                                        <th>Create On</th>
                                                        <th>Category</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1;
                                                    foreach ($advertorial as $adv) { ?>
                                                        <tr>
                                                            <td><?= $i ?></td>
                                                            <td>
                                                                <img width="80" height="80" src="<?= base_url('images/berita/') . $adv['foto_advertorial']; ?>">
                                                            </td>
                                                            <td><?= $adv['title']; ?></td>
                                                            <td><?= $adv['created_advertorial']; ?></td>
                                                            <td><?= $adv['kategori']; ?></td>
                                                            <td>
                                                                <?php if ($adv['status_advertorial'] == 1) { ?>
                                                                    <label class="badge badge-success">Active</label>
                                                                <?php } else { ?>
                                                                    <label class="badge badge-danger">Non Active</label>
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <a href="<?= base_url(); ?>advertorial/ubah/<?= $adv['id_advertorial']; ?>">
                                                                    <button class="btn btn-outline-primary">Edit</button>
                                                                </a>
                                                                <a href="<?= base_url(); ?>advertorial/hapus/<?= $adv['id_advertorial']; ?>" onclick="return confirm ('are you sure?')">
                                                                    <button class="btn btn-outline-danger">Delete</button>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php $i++;
                                                    } ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>







                    <div class="tab-pane fade" id="car-2-2" role="tabpanel" aria-labelledby="tab-2-2">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <a class="btn btn-info" href="<?= base_url(); ?>advertorial/tambahcategory"><i class="mdi mdi-plus-circle-outline"></i>Add Advertorial Category</a>
                                </div>
                                <br>
                                <h4 class="card-title">Advertorial Category</h4>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="order-listing1" class="table">
                                                <thead>
                                                    <tr>

                                                        <th>No.</th>
                                                        <th>Category</th>
                                                        <th>Created On</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1;
                                                    foreach ($kategori as $kadv) { ?>
                                                        <tr>
                                                            <td><?= $i ?></td>
                                                            <td><?= $kadv['kategori']; ?></td>
                                                            <td><?= $kadv['created']; ?></td>
                                                            <td>
                                                                <a href="<?= base_url(); ?>advertorial/hapuscategory/<?= $kadv['id_kategori_adv']; ?>"><button class="btn btn-outline-danger">Delete</button></a>
                                                            </td>
                                                        <?php $i++;
                                                    } ?>
                                                        </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->