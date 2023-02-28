<!-- partial -->

<div class="container-fluid mt-xl-50 mt-sm-30 mt-15 px-xxl-65 px-xl-20">
    <div class="row ">
        <div class="col-md-8 offset-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        Ubah Data QR Code Event
                    </h4>

                    <?= form_open_multipart('payments/edit_qr'); ?>

                    <div class="form-group">
                <?php
                    if(file_exists(base_url("images/qr/".$image_path))){
                ?>    
                        <img style='max-width:300px;height:auto;' src='<?= base_url("images/no_image.png")?>' class="img" alt="no_image"/>
                <?php
                    }else{
                ?>
                        <img style='max-width:300px;height:auto;' src="<?= base_url('images/qr/'.$image_path)?>" class="img" alt="QR_CODE_Image"/>
                        <!--<img max-width="200" height="auto" src='<?php // base_url("images/qr/no_image.png")?>' class="img" alt="no_image"/>-->
                <?php
                    }
                ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="newstitle">ID QRIS</label>
                        <input type="text" class="form-control" id="id" name="id" value="<?= $id ?>" readonly required>
                    </div>

                    <div class="form-group">
                        <label for="newstitle">Nama Event</label>
                        <input type="text" class="form-control" id="nama_event" name="nama_event" value="<?= $nama_event ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="newstitle">Nominal</label>
                        <input type="text" class="form-control" id="nominal" name="nominal" value="<?= $nominal ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Tipe</label>
                        <select id="getFname" onchange="admSelectCheck(this);" class="form-control custom-select  mt-15" name="tipe">
                            <?php 
                                if($tipe == 'STATIC'){
                            ?>
                                <option id="STATIC" value="STATIC" selected>"STATIC"</option>
                                <option id="DYNAMIC" value="DYNAMIC" >"DYNAMIC"</option>
                            <?php
                                }else{
                            ?>
                                <option id="STATIC" value="STATIC" >"STATIC"</option>
                                <option id="DYNAMIC" value="DYNAMIC" selected>"DYNAMIC"</option>
                            <?php
                                }
                            ?>

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="getFname" onchange="admSelectCheck(this);" class="form-control custom-select  mt-15" name="status">
                            <option id="non-active" value="0" >non-Active</option>
                            <option id="active" value="1" <?php if($status == 1) echo 'selected' ?>>Active</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label fot="expired_date">Expired Date</label>
                        <input type="date" name="expired_date" class="form-control custom-select  mt-15" value="<?= $expired_date ?>">
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="<?= base_url() ?>payments/qr" class="btn btn-danger">Cancel</a>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end of content wrapper -->
