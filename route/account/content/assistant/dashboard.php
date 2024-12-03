<div class="row">
    <div class="col-md-4">
        <div class="main-card mb-3 card">
            <div class="card-header">Cancelled Appointments : <?php echo $statCancelled[0]['total']; ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="main-card mb-3 card">
            <div class="card-header">Appointments Today : <?php echo $statBooked[0]['total']; ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="main-card mb-3 card">
            <div class="card-header">Reschedule Appointments :  <?php echo $statResched[0]['total']; ?></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Upcoming Appointments</div>
            <div class="table-responsive">
                <div class="col-md-12 mt-2">
                    <table id="pastTable" class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <th>ID</th>
                            <th>PID</th>
                            <th>PATIENT NAME</th>
                            <th>PURPOSE</th>
                            <th>DATE OF APPOINTMENT</th>
                        </thead>
                        <tbody>
                            <?php
                            $uid = $account[0]['user_id'];
                            $appointment = $portCont->showStatsAppointmentBookedList();
                            if (!empty($appointment)) {
                                foreach ($appointment as $key => $value) {
                                    echo 
                                    "<tr>
                                        <td>".$value['aid']."</td>   
                                        <td>".$value['pid']."</td>
                                        <td>".$value['fullname']."</td>
                                        <td>".$value['purpose']."</td>
                                        <td>".$value['schedule_date']."</td>
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