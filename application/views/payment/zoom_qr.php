<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
    <div class="print-area">
        <?php if ($this->session->flashdata('ubah')) : ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $this->session->flashdata('ubah'); ?>
                </div>
        <?php endif; ?>
        <a href="<?=base_url('payments/qr')?>">
            <button class="btn btn-outline-success">Kembali</button>
        </a>
        <h3> Detail QR Event</h3>

        <?php   
            if(file_get_contents(base_url('images/qr/'.$image_path))){
        ?>  
            <img style="max-width:350px;height:auto" src="<?= base_url('images/qr/'.$image_path)?>" alt="QR_CODE_Image">
            
        <?php   
            }else{
        ?>  
            <img style="max-width:500px;height:auto" src="<?= base_url('images/no_image.png')?>" alt="no image exist">
        <?php   
            }
        ?>
        <br>
        <a href='<?= base_url(). 'payments/regen_qr/'.$id; ?>'>
            <button class='btn btn-outline-warning'>Re-generate QR</button>
        </a>
        <a href='<?= base_url(). 'payments/qr_gen_logo/'.$image_path; ?>'>
            <button class='btn btn-outline-warning'>Generate with logo</button>
        </a>
        <table class="table">
            <tr>
                <th>ID QR</th>
                <td><h4><?= $id ?></h4></td>
            <tr>
                <th>Nama Event</th>
                <td><h4><?= $nama_event ?></h4>
            </tr>
            <tr>
                <th>Nominal</th>
                <td><h4><?= number_format($nominal,0,'.','.') ?></h4></td>    
            </tr>
            <tr>
                <th>Tipe</th>
                <td><h4><?= "'".$tipe."'" ?></h4></td>
            </tr>
            <tr>
                <th>Status</th>
                <?php 
                if($status == 1){
                    $status_qr = 'Active';
                    $color     = 'success';
                }else{
                    $status_qr = 'non-Active';
                    $color     = 'danger';
                } 
                ?>
                <td>
                    <h4>
                        <span class="badge badge-<?= $color ?>">
                        <?= $status_qr ?>
                        </span>
                    </h4>
                </td>
            <tr>
                <th>Created Date</th>
                <td><h4><?= $created_date ?></h4></td>
            </tr>
            <tr>
                <th>Expired Date</th>
                <td><h4><?= $expired_date ?></h4></td>
            </tr>
        </table>
    </div>
                <a>
                    <button class='btn btn-outline-secondary' onclick='window.print()' class='print-area'>
                    Print QR : <?= substr($nama_event, 0, 15).' . . .' ?>
                    </button>
                </a>
                <a href="<?= base_url('images/qr/'.$image_path)?>" class="btn btn-outline-secondary" download="QR - <?= $nama_event ?>">
                Download QR
                </a>
        
        
                
                
        </div><!-- /.CARD-Body -->
    </div><!-- /.CARD-->
</div><!-- /.Content-wrapper -->
<div class='card'>
    <div class='card-body'>

        <h4>History QR : <?=$nama_event?> Total saldo : Rp. <?=number_format($saldo_qr,0,',','.')?></h4>
        <table class="table table-striped table-hover dt-responsive display nowrap" id="detail_qr">
        <thead>
            <tr>
                <th>No</th>                    
                <th>Invoice</th>
                <th>Date Time</th>
                <th>Nominal</th>
                <th>ID User</th>
                <th>Nama User</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $i = 1;
                foreach($detail_qr as $d){       
            ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$d['invoice_w']?></td>
                <td><?=$d['regtime']?></td>
                <td><?=number_format($d['jumlah_w'],0,',','.')?></td>
                <td><?=$d['id_user_w']?></td>
                <td><?=$d['nama_user']?></td>
            </tr>
            <?php
                $i++;
                }
            ?>
        </tbody>
        </table>
    </div>
</div>