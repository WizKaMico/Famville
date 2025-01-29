<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Patient Management</div>
            <div class="table-responsive">
                <div class="col-md-12 mt-2">
                    <table id="upcomingTable"
                        class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <th>UID</th>
                            <th>ACCOUNT</th>
                            <th>FULLNAME</th>
                            <th>ADDRESS</th>
                            <th>EMAIL</th>
                            <th>PHONE</th>
                            <th>PATIENT</th>
                            <th>ACTION</th>
                        </thead>
                        <tbody>
                            <?php
                            $appointment = $portCont->patientAccount();
                            if (!empty($appointment)) {
                                foreach ($appointment as $key => $value) {
                                        echo 
                                        "<tr>
                                            <td>".$value['user_id']."</td>
                                            <td>".$value['username']."</td>
                                            <td>".$value['fullname']."</td>
                                            <td>".$value['address']."</td>
                                            <td>".$value['email']."</td>
                                            <td>".$value['phone']."</td>
                                            <td>".$value['patient']."</td>
                                            <td>
                                                <a href='?view=ACCOUNTHISTORY&id=".$value['user_id']."' class='btn btn-success btn-sm'> <i class='fa fa-info-circle'></i></span> View Record</a>
                                            </td>
                                        </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>