<?php include('db_connect.php');

$astat = array("Not Yet Started","On-going","Closed");
 ?>

<div class="col-12">
    <div class="card">
      <div class="card-body">
        Welcome <?php echo $_SESSION['login_name'] ?>!
        <br>
        <div class="col-md-5">
          <div class="callout callout-info">
            <h5><b>Academic Year: <?php echo $_SESSION['academic']['year'].' '?> </b></h5>
            <h6><b>Evaluation Status: <?php echo $astat[$_SESSION['academic']['status']] ?></b></h6>
          </div>
        </div>
      </div>
    </div>
</div>
