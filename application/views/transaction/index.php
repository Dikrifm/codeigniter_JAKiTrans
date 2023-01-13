<div class="container-fluid mt-xl-50 mt-sm-30 mt-15 px-xxl-65 px-xl-20">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <?php if ($this->session->flashdata()) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $this->session->flashdata('demo'); ?>
                            <?php echo $this->session->flashdata('cancel'); ?>
                            <?php echo $this->session->flashdata('hapus'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="card-body table-responsive p-0">
                        <table id="mwtable1" class="table table-hover table-head-fixed text-nowrap w-500" data-info="true">
                            <thead>
                                <tr>
                                    <th>+/-</th>
                                    <th>No</th>
                                    <th>Waktu Order</th>
                                    <th>Selesai Order</th>
                                    <th>Customer</th>
                                    <th>Driver</th>
                                    <th>Layanan</th>
                                    <th>Harga</th>
                                    <th>Pembayaran</th>
                                    <th>Status</th>
                                    <th>Actions</th>
									<th>Ambil</th>
                                    <th>Tujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($transaksi as $tr) { ?>

                                    <tr>
                                        <td></td>
                                        <td><?= $i;?></td>
                                        <td>
                                            <?= $tr['waktu_order'] ?>
                                        </td>
                                        <td>
                                            <?php
                                                if($tr['waktu_selesai'] != NULL){
                                                    echo $tr['waktu_selesai'];
                                                }else{
                                                    echo "<label class='badge badge-danger'>" . 'Transaction ' . $tr['status_transaksi']; 
                                                }
                                            ?>
                                        </td>
                                        <td><?= $tr['fullnama'] ?></td>
                                        <td><?= $tr['nama_driver'] ?></td>
                                        <td><?= $tr['fitur'] ?></td>
                                        <td><?= $currency['app_currency'] ?>
                                            <?= rupiah($tr['biaya_akhir']) ?></td>
                                        <td>
                                            <?php if ($tr['pakai_wallet'] == '0') {
                                                echo 'CASH';
                                            } else {
                                                echo 'WALLET';
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ($tr['status'] == '2') { ?>
                                                <label class="badge badge-primary"><?= $tr['status_transaksi']; ?></label>
                                            <?php } ?>
                                            <?php if ($tr['status'] == '3') { ?>
                                                <label class="badge badge-success"><?= $tr['status_transaksi']; ?></label>
                                            <?php } ?>
                                            <?php if ($tr['status'] == '5') { ?>
                                                <label class="badge badge-danger"><?= $tr['status_transaksi']; ?></label>
                                            <?php } ?>
                                            <?php if ($tr['status'] == '4') { ?>
                                                <label class="badge badge-info"><?= $tr['status_transaksi']; ?></label>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url(); ?>dashboard/detail/<?= $tr['id_transaksi']; ?>" class="btn btn-outline-primary">View</a>
                                            <a onclick="return confirm ('Are You Sure?')" href="<?= base_url(); ?>dashboard/delete/<?= $tr['id_transaksi']; ?>" class="btn btn-outline-danger">Delete</a>
                                        </td>
										 <td style="max-width:3000px;"><?= $tr['alamat_asal'] ?></td>
                                        <td style="max-width:3000px;"><?= $tr['alamat_tujuan'] ?></td>
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