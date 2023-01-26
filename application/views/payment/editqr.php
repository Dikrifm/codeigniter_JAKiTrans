<!-- partial -->

<div class="container-fluid mt-xl-50 mt-sm-30 mt-15 px-xxl-65 px-xl-20">
    <div class="row ">
        <div class="col-md-8 offset-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        Ubah Data QR Code Event
                    </h4>

                    <?= form_open_multipart('payments/ubah/' . $id); ?>
                    <input type="hidden" name="id" value='<?= $id ?>'>
                    
                    <div class="form-group">
                        <input type="file" class="dropify" name="logo" data-max-file-size="3mb" data-default-file="<?= base_url('asset/images/qr/') . $id ?>"/>
                    </div>
                    
                    <div class="form-group">
                        <label for="newstitle">ID QRIS</label>
                        <input type="text" class="form-control" id="id" name="id" value="<?= $id ?>" required>
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
                            <option id="STATIC" value="STATIC" >"STATIC"</option>
                            <option id="DYNAMIC" value="DYNAMIC" >"DYNAMIC"</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="getFname" onchange="admSelectCheck(this);" class="form-control custom-select  mt-15" name="status">
                            <option id="non-active" value="0" >non-Active</option>
                            <option id="active" value="1" >Active</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label fot="expired_date">Expired Date</label>
                        <input type="date" name="expired_date" class="form-control custom-select  mt-15" value="<?= $expired_date ?>">
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="<?= base_url() ?>metode" class="btn btn-danger">Cancel</a>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end of content wrapper -->
