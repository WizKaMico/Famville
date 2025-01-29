<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
               Appointment Report <?php echo $_GET['schedule_date']; ?>
            </div>
            <div class="table-responsive">
                <div class="col-md-12 mt-2">
                <table id="upcomingTable"
                        class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <th>PID</th>
                            <th>PATIENT NAME</th>
                            <th>AGE</th>
                            <th>PURPOSE</th>
                            <th>DATE OF APPOINTMENT</th>
                            <th>DOCTOR</th>
                            <th>DIAGNOSIS</th>
                            <th>STATUS</th>
                        </thead>
                        <tbody>
                            <?php
                            $date_appointment = $_GET['schedule_date'];
                            $appointment = $portCont->getReportOnDay($date_appointment);
                            if (!empty($appointment)) {
                                foreach ($appointment as $key => $value) {
                                        echo 
                                        "<tr>
                                            <td>".$value['pid']."</td>
                                            <td>".$value['fullname']."</td>
                                            <td>".$value['age']."</td>
                                            <td>".$value['purpose']."</td>
                                            <td>".$value['schedule_date']."</td>
                                            <td>Dr. ".$value['doctor']."</td>
                                            <td>".$value['diagnosis']."</td>
                                            <td>".$value['status']."</td>
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