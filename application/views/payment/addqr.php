<!-- partial -->

<div class="container-fluid mt-xl-50 mt-sm-30 mt-15 px-xxl-65 px-xl-20">
    <div class="row ">
        <div class="col-md-8 offset-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        Tambah QR Code Event
                    </h4>

                    <?= form_open_multipart('payments/insert_qr'); ?>

                    <div class="form-group">
                        <label for="nama_event">Nama Event</label>
                        <input type="text"  id="nama_event" name="nama_event" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="nominal">Nominal</label>
                        <input type="number"  id="nominal" name="nominal" required class="form-control">
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
                        <input type="date" name="expired_date" class="form-control custom-select  mt-15" required>
                    </div>

                    

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="<?= base_url() ?>ppoboperator" class="btn btn-danger">Cancel</a>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end of content wrapper -->
