<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                Doctors & Service
            </div>
            <div class="table-responsive">
             <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 mt-2 mb-2">
                        <a href='#addDoctor' class='btn btn-warning btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-user'></i></span> Create Doctor</a>
                        <hr />
                        <table id="upcomingTableDoctors"
                                class="align-middle mb-0 table table-borderless table-striped table-hover">
                                <thead>
                                    <th>DOCID</th>
                                    <th>DOCTOR</th>
                                    <th>DATE CREATED</th>
                                    <th>ACTION</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $doctors = $portCont->showDoctors();
                                    if (!empty($doctors)) {
                                        foreach ($doctors as $key => $value) {
                                            // ?view=DOCTORHISTORY=".$value['docid']."
                                                echo 
                                                "<tr>
                                                    <td>".$value['docid']."</td>
                                                    <td>".$value['doctor']."</td>
                                                    <td>".$value['date_created']."</td>
                                                    <td>
                                                    
                                                    <a href='?view=DOCTORSAPPOINTMENT&docid=".$value['docid']."' class='btn btn-success btn-sm'> <i class='fa fa-info-circle'></i></span> Appointments</a>
                                                    <a href='#doctor_".$value['docid']."' class='btn btn-warning btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-user'></i></span> Edit</a>
                                                    </td>
                                                </tr>";
                                                include('../route/account/modal/appointment_modal.php');
                                        }
                                    }

                                    include('../route/account/modal/appointment_modal.php');
                                    ?>
                                </tbody>
                            </table>
                    </div> 
                    <div class="col-md-6 mt-2 mb-2">
                        <a href='#addPurpose' class='btn btn-warning btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-tv'></i></span> Create Purpose</a>
                        <hr />
                        <table id="upcomingTablePurpose"
                                class="align-middle mb-0 table table-borderless table-striped table-hover">
                                <thead>
                                    <th>ID</th>
                                    <th>PURPOSE</th>
                                    <th>DATE CREATED</th>
                                    <th>ACTION</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $purpose = $portCont->showPurpose();
                                    if (!empty($purpose)) {
                                        foreach ($purpose as $key => $value) {
                                                echo 
                                                "<tr>
                                                    <td>".$value['purpose_id']."</td>
                                                    <td>".$value['purpose']."</td>
                                                    <td>".$value['date_created']."</td>
                                                    <td>
                                                          <a href='#editPurpose_".$value['purpose_id']."' class='btn btn-success btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-tv'></i></span> Edit</a>
                                                          <a href='#deletePurpose_".$value['purpose_id']."' class='btn btn-danger btn-sm' data-toggle='modal' data-backdrop='false'> <i class='fa fa-close'></i></span> Cancel</a>
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
    </div>
</div>