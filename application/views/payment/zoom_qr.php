<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
        <?php   
            if(file_get_contents(base_url('images/qr/'.$image_path))){
        ?>  
            <img style="max-width:500px;height:auto" src="<?= base_url('images/no_image.png')?>" alt="QR_CODE_Image">
        <?php   
            }else{
        ?>  
            <img style="max-width:500px;height:auto" src="<?= base_url('images/qr/'.$image_path)?>" alt="QR_CODE_Image">
        <?php   
            }
        ?>  
            
            <h4>Nama Event   : <?= $nama_event ?></h4>
            <h4>Nominal      : <?= $nominal ?></h4>
            <h4>Tipe         : <?= $tipe ?></h4>
            <h4>Status       : <?= $status ?></h4>
            <h4>created date : <?= $created_date ?></h4>
            <h4>expired date : <?= $expired_date ?></h4>

            <a href=<?= base_url().'payments/qr_download/'.$id ?>>
                <button class="btn btn-outline-warning">Download QR</button>
            </a>
        </div><!-- /.CARD-Body -->
    </div><!-- /.CARD-->
</div><!-- /.Content-wrapper