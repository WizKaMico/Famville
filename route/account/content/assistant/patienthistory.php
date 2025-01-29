<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Patient Management</div>
            <div class="table-responsive">
                <div class="col-md-12 mt-2">
                    <table id="upcomingTable"
                        class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <th>PID</th>
                            <th>PATIENT</th>
                            <th>PURPOSE</th>
                            <th>AGE</th>
                            <th>STATUS</th>
                            <th>DIAGNOSIS</th>
                            <th>DOCTOR</th>
                        </thead>
                        <tbody>
                            <?php
                            $uid = $_GET['id'];
                            $appointment = $portCont->patientAccountHistory($uid);
                            if (!empty($appointment)) {
                                foreach ($appointment as $key => $value) {
                                        echo 
                                        "<tr>
                                            <td>".$value['pid']."</td>
                                            <td>".$value['fullname']."</td>
                                            <td>".$value['purpose']."</td>
                                            <td>".$value['age']."</td>
                                            <td>".$value['status']."</td>
                                            <td>".$value['diagnosis']."</td>
                                            <td>DR. ".$value['doctor']."</td>
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