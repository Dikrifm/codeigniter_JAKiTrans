<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
        <?php   
            if(file_exists(base_url('images/qr/'.$image_path))){
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
            <h4>create date  : <?= $qrstring ?></h4>
            <h4>expired date : <?= $nama_event ?></h4>

        </div><!-- /.CARD-Body -->
    </div><!-- /.CARD-->
</div><!-- /.Content-wrapper