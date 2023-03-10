<!-- partial -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
 <div class="container-fluid mt-xl-50 mt-sm-30 mt-15 px-xxl-65 px-xl-20">
    <div class="row ">
        <div class="col-md-8 offset-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add Driver</h4>
                    <?php if (validation_errors() or $this->session->flashdata()) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= validation_errors() ?>
                            <?php echo $this->session->flashdata('invalid'); ?>
                            <?php echo $this->session->flashdata('demo'); ?>
                        </div>
                    <?php endif; ?>
                    <?= form_open_multipart('driver/tambah'); ?>
                    <br>
                    <h6 class="card-title">Main Info</h6>
                    <div class="form-group">
                        <label>Foto Profile</label>
                        <input id="uploadProfile" type="file" class="dropify" name="foto" onchange="PreviewProfile();" data-max-file-size="3mb" required /><br>
                         <img id="ProfilePreview" style="width: 300px; height: 200px;" />
                       <script type="text/javascript">
                        function PreviewProfile() {
                            var oFReader = new FileReader();
                            oFReader.readAsDataURL(document.getElementById("uploadProfile").files[0]);
                    
                            oFReader.onload = function (oFREvent) {
                                document.getElementById("ProfilePreview").src = oFREvent.target.result;
                            };
                        };
                    
                    </script>
                    </div>
                    <div class="form-group">
                        <label for="nama_driver">Name</label>
                        <input type="text" class="form-control" id="nama_driver" name="nama_driver" <?php if ($_POST != NULL) { ?> value="<?= $_POST['nama_driver']; ?>" <?php } ?> placeholder="enter name" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control custom-select  mt-15" style="width:100%" name="gender">
                            <option value="2" <?php
                                                if ($_POST != NULL) {
                                                    if ($_POST['gender'] == '2') {
                                                ?>selected<?php }
                                                    } ?>>Male</option>
                            <option value="1" <?php
                                                if ($_POST != NULL) {
                                                    if ($_POST['gender'] == '1') {
                                                ?>selected<?php }
                                                    } ?>>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tgl_lahir">Birth Date</label>
                        <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" <?php if ($_POST != NULL) { ?> value="<?= $_POST['tgl_lahir']; ?>" <?php } ?> placeholder="enter birth date " required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" <?php if ($_POST != NULL) { ?> value="<?= $_POST['email']; ?>" <?php } ?> placeholder="enter email " required>
                    </div>

                    <label class="text-small">Phone Number</label>
                    <div class="row">

                        <div class="form-group col-2">
                            <input type="tel" id="txtPhone" class="form-control" name="countrycode" <?php if ($_POST != NULL) { ?> value="<?= $_POST['countrycode']; ?>" <?php } ?> required>
                        </div>
                        <div class=" form-group col-10">
                            <input type="text" class="form-control" id="phone" name="phone" <?php if ($_POST != NULL) { ?> value="<?= $_POST['phone']; ?>" <?php } ?> placeholder="enter phone number" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input name="alamat_driver" rows="6" class="form-control" required><?php if ($_POST != NULL) {
                                                                                                    echo $_POST['alamat_driver']; ?>" <?php } ?></input>
                    </div>
                    <br>

                    <h6 class="card-title">Job & Vehicle</h6>

                    <div class="form-group">
                        <label for="Job Service">Vehicle</label>
                        <select class="form-control custom-select  mt-15" name="job" style="width:100%">
                            <?php foreach ($driverjob as $drj) { ?>
                                <option value="<?= $drj['id'] ?>"><?= $drj['driver_job'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="brand">Vehicle Brand</label>
                        <input type="text" class="form-control" name="merek" id="brand" <?php if ($_POST != NULL) { ?> value="<?= $_POST['merek']; ?>" <?php } ?> placeholder="enter vehicle brand" required>
                    </div>
                    <div class="form-group">
                        <label for="variantvehicle">Vehicle Variant</label>
                        <input type="text" class="form-control" name="tipe" id="variantvehicle" <?php if ($_POST != NULL) { ?> value="<?= $_POST['tipe']; ?>" <?php } ?> placeholder="enter vehicle variant" required>
                    </div>
                    <div class="form-group">
                        <label for="vehiclecolor">Vehicle Color</label>
                        <input type="text" class="form-control" name="warna" id="vehiclecolor" <?php if ($_POST != NULL) { ?> value="<?= $_POST['warna']; ?>" <?php } ?> placeholder="enter vehicle color" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicleregistration">Vehicle Registration Number</label>
                        <input type="text" class="form-control" name="nomor_kendaraan" id="vehicleregistration" <?php if ($_POST != NULL) { ?> value="<?= $_POST['nomor_kendaraan']; ?>" <?php } ?> placeholder="enter vehicle registration number" required>
                    </div>
                    <br>

                    <h6 class="card-title">Files</h6>

                    <div class="form-group">
                        <label for="idcard">Nomor KTP</label>
                        <input type="text" class="form-control" name="no_ktp" <?php if ($_POST != NULL) { ?> value="<?= $_POST['no_ktp']; ?>" <?php } ?> placeholder="enter id card number" required>
                    </div>

                    <div>
                        <label>Foto KTP</label>
                        <input id="uploadKTP" type="file" class="dropify" name="foto_ktp" onchange="PreviewKtp();" data-max-file-size="3mb" required /><br>
                         <img id="KtpPreview" style="width: 300px; height: 200px;" />
                       <script type="text/javascript">
                        function PreviewKtp() {
                            var oFReader = new FileReader();
                            oFReader.readAsDataURL(document.getElementById("uploadKTP").files[0]);
                    
                            oFReader.onload = function (oFREvent) {
                                document.getElementById("KtpPreview").src = oFREvent.target.result;
                            };
                        };
                    
                    </script>
                        <br>
                    </div>
                    <div class="form-group">
                        <label for="idcard">Nomor SIM</label>
                        <input type="text" class="form-control" name="id_sim" <?php if ($_POST != NULL) { ?> value="<?= $_POST['id_sim']; ?>" <?php } ?> placeholder="enter driver's license number " required>
                    </div>
                    <div>
                        <label>Foto SIM</label>
                        <input id="uploadSIM" type="file" class="dropify" name="foto_sim" onchange="PreviewSIM();" data-max-file-size="3mb" required /><br>
                         <img id="SimPreview" style="width: 300px; height: 200px;" />
                       <script type="text/javascript">
                        function PreviewSIM() {
                            var oFReader = new FileReader();
                            oFReader.readAsDataURL(document.getElementById("uploadSIM").files[0]);
                    
                            oFReader.onload = function (oFREvent) {
                                document.getElementById("SimPreview").src = oFREvent.target.result;
                            };
                        };
                    
                    </script>
                    </div>
                    <br>



                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function previewFile() {
  var preview = document.querySelector('foto_sim');
  var file    = document.querySelector('input[type=file]').files[0];
  var reader  = new FileReader();

  reader.onloadend = function () {
    preview.src = reader.result;
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = "";
  }
}
</script>
<script type="text/javascript">
    $(function() {
        var code = "+62"; // Assigning value from model.
        $('#txtPhone').val(code);
        $('#txtPhone').intlTelInput({
            autoHideDialCode: true,
            autoPlaceholder: "ON",
            dropdownContainer: document.body,
            formatOnDisplay: true,
            hiddenInput: "full_number",
            initialCountry: "auto",
            nationalMode: true,
            placeholderNumberType: "MOBILE",
            preferredCountries: ['US'],
            separateDialCode: false
        });
        console.log(code)
    });
</script>
<!-- end of content wrapper -->