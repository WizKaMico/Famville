<!-- Modal -->
<div class="modal fade" id="datePickerModal" tabindex="-1" aria-labelledby="datePickerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Flatpickr calendar will render here -->
                <div id="flatpickr-container"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->


<!-- Reschedule -->
<div class="modal fade mt-5" id="edit_<?php echo $value['aid']; ?>" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" action="?view=HOME&action=HOMEAPPOINTMENT">
                        <input type="hidden" class="form-control" name="id" value="<?php echo $value['aid']; ?>">
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label class="control-label modal-label">Reschedule Appointment:</label>
                            </div>
                            <div class="col-sm-12">
                                <input type="date" name="doa" value="<?php echo $value['schedule_date']; ?>"
                                    class="form-control" id="doa"
                                    min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span
                        class="glyphicon glyphicon-remove"></span> Cancel</button>
                <button type="submit" name="edit" class="btn btn-success"><span
                        class="glyphicon glyphicon-check"></span> Update</a>
                    </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade mt-5" id="information_<?php echo $value['aid']; ?>" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" action="?view=HOME&action=HOMEAPPOINTMENTINFORMATION">
                        <input type="hidden" class="form-control" name="id" value="<?php echo $value['aid']; ?>">
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label class="control-label modal-label">Patient:</label>
                            </div>
                            <div class="col-sm-12">
                                <input type="text" name="fullname" value="<?php echo $value['fullname']; ?>"
                                    class="form-control" required="">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label class="control-label modal-label">Birthdate:</label>
                            </div>
                            <div class="col-sm-12">
                                <input type="date" name="date_birth" value="<?php echo $value['date_birth']; ?>"
                                    class="form-control" required="">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label class="control-label modal-label">Gender:</label>
                            </div>
                            <div class="col-sm-12">
                                <select name="gender" class="form-control" required="">
                                    <option value="<?php echo $value['gender']; ?>"><?php echo $value['gender']; ?>
                                        (CURRENT)</option>
                                    <option value="MALE">MALE</option>
                                    <option value="FEMALE">FEMALE</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label class="control-label modal-label">Purpose:</label>
                            </div>
                            <div class="col-sm-12">
                                <select name="purpose" class="form-control" required="">
                                    <option value="<?php echo $value['purpose']; ?>"><?php echo $value['purpose']; ?>
                                        (CURRENT)</option>
                                    <option value="CHECKUP">CHECKUP</option>
                                </select>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span
                        class="glyphicon glyphicon-remove"></span> Cancel</button>
                <button type="submit" name="edit" class="btn btn-success"><span
                        class="glyphicon glyphicon-check"></span> Update</a>
                    </form>
            </div>

        </div>
    </div>
</div>


<div class="modal fade mt-5" id="assistantinformation_<?php echo $value['aid']; ?>" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" action="?view=HOME&action=HOMEAPPOINTMENTINFORMATION">
                        <input type="hidden" class="form-control" name="id" value="<?php echo $value['aid']; ?>">
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label class="control-label modal-label">Patient:</label>
                            </div>
                            <div class="col-sm-12">
                                <input type="text" name="fullname" value="<?php echo $value['fullname']; ?>"
                                    class="form-control" required="" readonly="">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label class="control-label modal-label">Birthdate:</label>
                            </div>
                            <div class="col-sm-12">
                                <input type="date" name="date_birth" value="<?php echo $value['date_birth']; ?>"
                                    class="form-control" required="" readonly="">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label class="control-label modal-label">Gender:</label>
                            </div>
                            <div class="col-sm-12">
                                <input type="text" name="gender" value="<?php echo $value['gender']; ?>"
                                    class="form-control" required="" readonly="">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label class="control-label modal-label">Purpose:</label>
                            </div>
                            <div class="col-sm-12">
                                <input type="text" name="purpose" value="<?php echo $value['purpose']; ?>"
                                    class="form-control" required="" readonly="">
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                    </form>
            </div>

        </div>
    </div>
</div>




<!-- Delete -->
<div class="modal fade mt-5" id="delete_<?php echo $value['aid']; ?>" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <h5 class="text-center">Are you sure you want to cancel the appointment for
                    <?php echo $value['purpose']; ?></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span
                        class="glyphicon glyphicon-remove"></span> Cancel</button>
                <a href="?view=BOOK&action=CANCEL&id=<?php echo $value['aid']; ?>" class="btn btn-danger"><span
                        class="glyphicon glyphicon-trash"></span> Yes</a>
            </div>

        </div>
    </div>
</div>