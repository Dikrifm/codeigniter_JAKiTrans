<!-- partial -->

 <div class="container-fluid mt-xl-50 mt-sm-30 mt-15 px-xxl-65 px-xl-20">
    <div class="row ">
        <div class="col-md-8 offset-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <?php if ($this->session->flashdata()) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $this->session->flashdata('demo'); ?>
                            </div>
                        <?php endif; ?>
                        Tambah Data Payment Method</h4>
                    <?= form_open_multipart('payments/tambah'); ?>
                    <div class="form-group">
                        <input type="file" class="dropify" name="logo" data-max-file-size="3mb" required />
                    </div>

                    <div class="form-group">
                        <label for="newscategory">Tipe payment</label>
                        <select id="getFname" onchange="admSelectCheck(this);" class="form-control custom-select  mt-15" name="tipe">
                            <option id="online" value="1" >Top Up</option>
                            <option id="offline" value="2" >Withdraw(wd)</option>
                        </select>
                    </div>
                    
                    <div id="jenischeck" style="display:block;" class="form-group">
                        <label for="newscategory">Jenis</label>
                        <select id="getFname"  class="form-control custom-select  mt-15" name="jenis">
                            <option value="1" >Ewallet</option>
                            <option value="2" >Virtual Account</option>
                            <option value="3" >Retail Outlet</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="newstitle">Nama</label>
                        <input type="text" class="form-control" id="newstitle" name="nama" required>
                    </div>

                    <div class="form-group">
                        <label for="newstitle">Keterangan</label>
                        <input type="text" class="form-control" id="newstitle" name="keterangan" required>
                    </div>
                    
                    <div id="bankcheck" style="display:none;" class="form-group">
                        <label for="link">Nama Bank</label>
                        <input type="text" class="form-control" id="linktes" name="nama_bank">
                    </div>
                    
                    <div id="rekcheck" style="display:none;" class="form-group">
                        <label for="link">No Rekening</label>
                        <input type="text" class="form-control" id="linktes" name="no_rekening">
                    </div>
                    
                    <div id="namacheck" style="display:none;" class="form-group">
                        <label for="link">Atas Nama</label>
                        <input type="text" class="form-control" id="linktes" name="nama_rekening">
                    </div>
                    
                    <div id="codecheck" style="display:block;" class="form-group">
                        <label for="link">Kode Chanel</label>
                        <input type="text" class="form-control" id="channeltes" name="channel_code">
                    </div>
                    
                     <div class="form-group">
                        <label for="newstitle">Biaya</label>
                        <input type="number" class="form-control" id="biaya" name="biaya" required>
                    </div>

                    <div class="form-group">
                        <label for="newscategory">Status</label>
                        <select class="form-control custom-select  mt-15" name="status" style="width:100%">
                            <option value="0" >Nonactive</option>
                            <option value="1" >Active</option>
                        </select>
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
<script>
    function admSelectCheck(nameSelect) {
        console.log(nameSelect);

        if (nameSelect) {
            onlineValue = document.getElementById("online").value;
            offlineValue = document.getElementById("offline").value;

            if (onlineValue == nameSelect.value) {
                //document.getElementById("linktes").required = false;
                document.getElementById("channeltes").required = true;

                document.getElementById("bankcheck").style.display = "none";
                document.getElementById("rekcheck").style.display = "block";
                document.getElementById("namacheck").style.display = "none";
                document.getElementById("codecheck").style.display = "block";
                document.getElementById("jenischeck").style.display = "block";
            } else if (offlineValue == nameSelect.value) {
                //document.getElementById("linktes").required = true;
                //document.getElementById("channeltes").required = true;
                document.getElementById("bankcheck").style.display = "block";
                document.getElementById("codecheck").style.display = "block";

                document.getElementById("rekcheck").style.display = "none";
                document.getElementById("namacheck").style.display = "none";
                document.getElementById("jenischeck").style.display = "none";
            }
        } else {
            document.getElementById("servicecheck").style.display = "block";
        }
    }
</script>