<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>404</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/perfect-scrollbar/dist/css/perfect-scrollbar.min.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="<?= base_url(); ?>asset/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="<?= base_url(); ?>asset/images/logo.png" />
</head>

<body>
  <div class="container-scroller">
    TEST 1 <br><br><br>
  <?php
        //$i = count($ceklog);

        if(!empty($status_pm)){


          echo 'Payment Method offline, silahkan hubungi CS Admin';
          
          
          
          if(!empty($ceklog))
          echo var_dump($ceklog);

          if(!empty($ceklog)){
          echo '<br>' . 'CEK CALL Object';
          echo $ceklog->account_holder_name;
          }
        }else{

        echo 'data Kosong boss'. '<br>';
          if(!empty($data_wd)){
            echo var_dump($data_wd);
          }

          if(!empty($callback_wd)){
            echo var_dump($callback_wd);
          }
        }

    ?>
  </div>
    
  
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="<?= base_url(); ?>asset/node_modules/jquery/dist/jquery.min.js"></script>
  <script src="<?= base_url(); ?>asset/node_modules/popper.js/dist/umd/popper.min.js"></script>
  <script src="<?= base_url(); ?>asset/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="<?= base_url(); ?>asset/node_modules/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.min.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="<?= base_url(); ?>asset/js/off-canvas.js"></script>
  <script src="<?= base_url(); ?>asset/js/hoverable-collapse.js"></script>
  <script src="<?= base_url(); ?>asset/js/misc.js"></script>
  <script src="<?= base_url(); ?>asset/js/settings.js"></script>
  <script src="<?= base_url(); ?>asset/js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>