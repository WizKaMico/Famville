<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                Reports
            </div>
            <div class="table-responsive">
                <div class="col-md-12 mt-2">
                <form action="?view=REPORTS&action=REPORTS" method="POST">
                  <div class="mb-3">
                     <label for="doa" class="form-label">Date of Report:</label>
                     <input type="date" name="date_appointment" class="form-control">
                  </div>
                   <div class="mb-3">
                      <button type="submit" name="generate" class="btn btn-primary w-100">GENERATE</button>
                    </div>
                </form>    
                <table id="upcomingTable"
                        class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <th>STATUS</th>
                            <th>COUNT</th>
                            <th>VIEW</th>
                        </thead>
                        <tbody>
                            <?php
                            date_default_timezone_set('Asia/Manila');
                            $date_appointment = $_GET['date_appointment'] ?? date('Y-m-d');
                            if(!empty($date_appointment))
                            {
                                $appointment = $portCont->getReports($date_appointment);
                            }
                            else
                            {   
                                $appointment = $portCont->getReports($date_appointment);
                            }
                            if (!empty($appointment)) {
                                foreach ($appointment as $key => $value) {
                                        echo 
                                        "<tr>
                                            <td>".$value['total']."</td>
                                            <td>".$value['status']."</td>
                                            <td>
                                               <a href='?view=DATEBOOKINGSPECIFIC&schedule_date=".$value['schedule_date']."' class='btn btn-success btn-sm'> <i class='fa fa-info-circle'></i></span> View Record</a>
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