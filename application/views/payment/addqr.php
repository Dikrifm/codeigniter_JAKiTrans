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
                        <input type="file" class="dropify" name="logo" data-max-file-size="3mb" required />
                    </div>

                    <div class="form-group">
                        <label for="newscategory">Status</label>
                        <select id="getFname" class="form-control custom-select  mt-15" name="tipe">
                            <option id="online" value="0" >Non-Active</option>
                            <option id="offline" value="1" >Active</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="newstitle">Nama Event</label>
                        <input type="text" class="form-control" id="nama_event" name="nama_event" required>
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
