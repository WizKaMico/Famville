<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Todays Patient Management</div>
            <div class="table-responsive">
                <div class="col-md-12 mt-2">
                    <table id="upcomingTable"
                        class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <th>ID</th>
                            <th>PID</th>
                            <th>PATIENT NAME</th>
                            <th>PURPOSE</th>
                            <th>DATE OF APPOINTMENT</th>
                            <th>ACTION</th>
                        </thead>
                        <tbody>
                            <?php
                            $appointment = $portCont->getAllUpcomingAppointmentA();
                            if (!empty($appointment)) {
                                foreach ($appointment as $key => $value) {
                                    if($value['status'] != 'CANCELLED')
                                    {
                                        echo 
                                        "<tr>
                                            <td>".$value['aid']."</td>
                                            <td>".$value['pid']."</td>
                                            <td>".$value['fullname']."</td>
                                            <td>".$value['purpose']."</td>
                                            <td>".$value['schedule_date']."</td>
                                            <td>
                                                <a href='#edit_".$value['aid']."' class='btn btn-success btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-calendar'></i></span> Reschedule</a>
                                                <a href='#assistantinformation_".$value['aid']."' class='btn btn-warning btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-user'></i></span> Information</a>
                                                <a href='#delete_".$value['aid']."' class='btn btn-danger btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-close'></i></span> Cancel</a>
                                            </td>
                                        </tr>";
                                        include('route/account/modal/appointment_modal.php');
                                    }
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