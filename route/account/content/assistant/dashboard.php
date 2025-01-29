<div class="row">
    <div class="col-md-3">
        <div class="main-card mb-3 card">
            <div class="card-header"><i class="fa fa-calendar"></i>&nbsp;  Cancelled Appointments : <?php echo $statCancelled[0]['total']; ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="main-card mb-3 card">
            <div class="card-header"><i class="fa fa-calendar"></i>&nbsp;   Appointments Today : <?php echo $statBooked[0]['total']; ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="main-card mb-3 card">
            <div class="card-header"><i class="fa fa-calendar"></i>&nbsp;   Reschedule Appointments :  <?php echo $statResched[0]['total']; ?></div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="main-card mb-3 card">
            <div class="card-header"><i class="fa fa-calendar"></i>&nbsp;   Completed Appointments :  <?php echo $statCompleted[0]['total']; ?></div>
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
                            <th>AGE</th>
                            <th>PURPOSE</th>
                            <th>DATE OF APPOINTMENT</th>
                            <th>DOCTOR</th>
                            <th>DIAGNOSIS</th>
                            <th>STATUS</th>
                            <th>ALERT</th>
                            <th>ACTION</th>
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
                                        <td>".$value['age']."</td>
                                        <td>".$value['purpose']."</td>
                                        <td>".$value['schedule_date']."</td>
                                        <td>Dr. ".$value['doctor']."</td>
                                        <td>".$value['diagnosis']."</td>
                                        <td>".$value['status']."</td>
                                        <td>
                                        <a href='#notifysms_".$value['aid']."' class='btn btn-success btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-comment-alt'></i></span> SMS</a>
                                        <a href='#notifyemail_".$value['aid']."' class='btn btn-success btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-envelope-o'></i></span> EMAIL</a>
                                        </td>
                                        <td>
                                        <a href='#assigndoctor_".$value['aid']."' class='btn btn-success btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-user-plus'></i></span> Assign</a>
                                        <a href='#bookingComplete_".$value['aid']."' class='btn btn-success btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-calendar-check-o'></i></span> Complete</a>
                                        </td>
                                    </tr>";
                                    include('../route/account/modal/appointment_modal.php');
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