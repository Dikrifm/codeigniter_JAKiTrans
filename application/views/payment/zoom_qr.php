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

        <table>
            <tr>
                <th>Nama Event</th>
                <th>Nominal</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Expired Date</th>
            </tr>
            <tr>
                <td><h4><?= $nama_event ?></h4>
                <td><h4><?= $nominal ?></h4></td>
                <td><h4><?= $tipe ?></h4></td>
                <?php 
                if($status == 1){
                    $status_qr = 'Active';
                    $color     = 'success';
                }else{
                    $status_qr = 'non-Active';
                    $color     = 'danger';
                } 
                ?>
                <td><h4>
                    Status       : <span class="badge badge-<?= $color ?>">
                                <?= $status_qr ?>
                                </span>
                </h4></td>
                <td><h4>created date : <?= $created_date ?></h4></td>
                <td><h4>expired date : <?= $expired_date ?></h4></td>
            </tr>
        </table>
                <a>
                    <button class='btn btn-outline-secondary' onclick='window.print()' >
                    Print QR : <?= substr($nama_event, 0, 15).' . . .' ?>
                    </button>
                </a>
                <a href="<?= base_url('images/qr/'.$image_path)?>" class="btn btn-outline-secondary" download="QR - <?= $nama_event ?>">
                Download QR
                </a>
        </div><!-- /.CARD-Body -->
    </div><!-- /.CARD-->
</div><!-- /.Content-wrapper