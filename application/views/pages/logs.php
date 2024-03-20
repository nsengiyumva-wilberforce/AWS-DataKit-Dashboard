<div class="container">
    <div class="content">
        <br>
        <?php if (!empty($login_info)): ?>
            <?php foreach ($login_info as $log): ?>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h3 class="h2"><?= $log->name ?>'s User Activity Details</h3>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr class="table-light">
                                <td><strong>Log ID:</strong> <?= $log->id ?></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>Name:</strong> <?= $log->name ?></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>Device Name:</strong> <?= $log->agent ?></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>Device IP:</strong> <?= $log->ip ?></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>Session ID:</strong> <?= $log->uni_id ?></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>Regional ID:</strong> <?= $log->region_id ?></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>Regional Code:</strong> <?= $log->region_code ?></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>Login Time:</strong> <?= $log->login_time ?></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>Logout Time:</strong> <?= $log->logout_time ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <h5>There is no data in the log datatable</h5>
        <?php endif ?>
    </div>
</div>
