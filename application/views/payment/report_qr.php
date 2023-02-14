<div class="content-wrapper">
<div class="card">
<div class="card-body">

<h1>
    Report QR Event
</h1>
<div class="card">
    <div class="card-body">
        <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>invoice</th>
                <th>Date Time</th>
                <th>ID User</th>
                <th>Nama User</th>
                <th>ID QR Event</th>
                <th>Nama Event</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $i = 1;
                foreach($qr_event_history as $log){
            ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$log['invoice']?></td>
                <td><?=$log['regtime']?></td>
                <td><?php //$log['id_user_p']?></td>
                <td><?php //$log['nama_user_p']?></td><!-- nama user -->
                <td><?php $log['id_qr_event']?></td>
                <td><?php //$log['nama_event']?></td>
            </tr>
            <?php
                $i++;}
            ?>

        </tbody>

        </table>
    </div><!-- /card-body -->
</div><!-- /card -->

</div><!-- /card-body -->
</div><!-- /card -->
</div><!-- /content-wrapper -->