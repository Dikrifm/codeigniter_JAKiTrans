<!-- partial -->
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15 px-xxl-65 px-xl-20">
    <div class="row justify-content-md-center">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <?php if ($this->session->flashdata('salah')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $this->session->flashdata('salah'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <h4 class="card-title">Edit Detail Withdraw</h4>
                    <?= form_open_multipart('wallet/editpm/' . $data_wd['id']); ?>

                    <div class="form-group">
                        <label for="id_wallet">ID_Wallet</label>
                        <input type="text" data-type="currency" class="form-control" id="id_wallet" name="id_wallet" value='<?= $data_wd['id'] ?>' readonly>
                    </div>
                    <div class="form-group">
                        <label for="id_wallet">Waktu Pengajuan Withdraw</label>
                        <input type="text" data-type="currency" class="form-control" id="waktu" name="waktu" value='<?= $data_wd['waktu'] ?>' readonly>
                    </div>

                    <div class="form-group">
                        <label for="id_payment_method">Bank Pengajuan Rekening</label>
                        <select class="form-control custom-select mt-15 operator" id='id_payment_method' name="id_payment_method" style="width:100%" required>
                        <option value="">Pilih Bank Pencairan</option>
                            <?php foreach ($data_pm as $pm) { 
                                
                                if($pm['tipe'] > 1){
                            ?>
                                    <option value="<?= $pm['id'] ?>"><?= $pm['nama'] . ' biaya : '. $pm['biaya'] ?></option>
                            <?php  
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>No Rekening</label>
                        <input type="text" class="form-control" value='<?php echo $data_wd['rekening']; ?>' readonly>
                    </div>

                    <div class="form-group">
                        <label>Nama Pemilik Rekening</label>
                        <input type="text" class="form-control" value='<?php echo $data_wd['nama_pemilik']; ?>' readonly>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a class="btn btn-danger text-white" href="<?= base_url(); ?>promoslider">Cancel</a>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>