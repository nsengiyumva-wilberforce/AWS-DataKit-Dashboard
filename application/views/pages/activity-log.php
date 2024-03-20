<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h3 class="h2">User Activity Logs</h3>
    
	<div class="btn-toolbar mb-2 mb-md-0">
    <?php
    //var_dump( $login_info);
    ?>
	</div>
</div>
<br>
    <br>
<div class="table-responsive mb-5">
    <?php if (isset($login_info)): ?>
        <table class="table table-striped">
  <thead>
  <tr>
      <th scope="col">log ID</th>
      <th scope="col">name</th>
      <th scope="col">Device name</th>
      <th scope="col">IP Address</th>
      
    </tr>
 
  <tbody>
    
    <?php foreach ($login_info as $log): ?>
        <tr>
        <th scope="row"><?= $log->id ?></th>
        <td>
            <a href="<?= base_url('logs/'.$log->id) ?>"><?= $log->name ?></a>
        </td>
        <td>
            <a href="<?= base_url('logs/'.$log->id) ?>"><?= $log->agent ?></a>
        </td>
        <td>
            <a href="<?= base_url('logs/'.$log->id) ?>"><?= $log->ip ?></a>
        </td>
        </tr>
    <?php endforeach ?>
  
</table>
<?php else: ?>
    <h5>There is no data in the log datatable</h5>
<?php endif?>
</div>