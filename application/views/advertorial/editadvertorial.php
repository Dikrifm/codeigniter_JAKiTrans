<!-- partial -->
 <div class="container-fluid mt-xl-50 mt-sm-30 mt-15 px-xxl-65 px-xl-20">
  <div class="row ">
    <div class="col-md-8 offset-md-2 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <?php if ($this->session->flashdata()) : ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $this->session->flashdata('demo'); ?>
            </div>
          <?php endif; ?>
          <h4 class="card-title">Edit Advertorial form</h4>
          <?= form_open_multipart('advertorial/ubah/' . $advertorial['id_advertorial']); ?>
          <div class="form-group">
            <input type="hidden" class="form-control" name="id_advertorial" id="id_advertorial" value="<?= $advertorial['id_advertorial'] ?>">
            <div class="form-group">
              <label>Advertorial Image</label>
              <input type="file" class="dropify" name="foto_advertorial" data-max-file-size="20mb" data-default-file="<?= base_url('images/advertorial/') . $advertorial['foto_advertorial'] ?>" />
            </div>
            <div class="form-group">
              <label for="newstitle">Title</label>
              <input type="text" class="form-control" name="title" id="title" value="<?= $advertorial['title'] ?>" required>
            </div>
            <div class="form-group">
              <label for="newscategory">Advertorial Category</label>
              <select class="form-control custom-select  mt-15" style="width:100%" name="id_kategori">
                <?php foreach ($kadv as $adv) { ?>


                  <option value="<?= $adv['id_kategori_adv'] ?>" <?php if ($adv['id_kategori_adv'] == $advertorial['id_kategori']) { ?>selected<?php } ?>> <?= $adv['kategori'] ?></option>

                <?php } ?>

              </select>
            </div>
            <div class="form-group">
              <label for="newscategory">Advertorial Status</label>
              <select class="form-control custom-select  mt-15" style="width:100%" name="status_advertorial">

                <option value="1" <?php if ($advertorial['status_advertorial'] == '1') { ?>selected<?php } ?>>Active</option>
                <option value="2" <?php if ($advertorial['status_advertorial'] == '2') { ?>selected<?php } ?>>NonActive</option>

              </select>
            </div>
            <div class="form-group">
              <label for="newscontent">Advertorial Content</label>
              <textarea type="text" class="form-control" id="summernoteExample1" placeholder="Location" name="content" required><?= $advertorial['content'] ?></textarea>
            </div>
            <button type="submit" class="btn btn-success mr-2">Submit</button>
            <button class="btn btn-light">Cancel</button>
            <?= form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end of content wrapper -->