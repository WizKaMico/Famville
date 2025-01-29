 <div class="row">
     <div class="col-md-12">
         <div class="main-card mb-3 card">
             <div class="card-header">Fill out the following details for your appointment</div>
             <div class="table-responsive">
                 <div class="col-md-12">
                     <form action="?view=SCHEDULING&action=BOOK" method="POST">
                         <div class="mb-3 mt-3">
                             <label for="fullname" class="form-label">Patient Name:</label>
                             <input type="text" class="form-control" id="fullname" placeholder="Enter Patient Name"
                                 name="fullname">
                         </div>
                         <!-- <div class="mb-3 mt-3">
                             <label for="fullname" class="form-label">Contact:</label>
                             <input type="number" class="form-control" id="contact" name="contact" readonly="">
                         </div> -->
                         <div class="mb-3">
                             <label for="dob" class="form-label">Date of Birth:</label>
                             <input type="date" class="form-control" id="dob" placeholder="Enter Date of Birth"
                                 name="dob">
                         </div>
                         <div class="mb-3">
                             <label for="gender" class="form-label">Gender:</label>
                             <select name="gender" class="form-control" required="">
                                 <option value="">CHOOSE GENDER</option>
                                 <option value="MALE">MALE</option>
                                 <option value="FEMALE">FEMALE</option>
                             </select>
                         </div>
                         <div class="mb-3">
                             <label for="purpose" class="form-label">Purpose of Visit:</label>
                             <select name="purpose" class="form-control" required="">
                                 <option value="">CHOOSE PURPOSE</option>
                                 <?php 
                                     $purpose = $portCont->showPurpose();
                                     if (!empty($purpose)) {
                                         foreach ($purpose as $key => $value) {
                                   ?>
                                   <option value="<?php echo $value['purpose']; ?>"><?php echo $value['purpose']; ?></option>
                                   <?php } } ?>
                             </select>
                         </div>
                         <div class="mb-3">
                             <label for="fromIns" class="form-label">From:</label>
                             <select name="fromIns" class="form-control" required="">
                                 <option value="">CHOOSE APPOINTMENT LOG</option>
                                 <option value="WALKIN">WALKIN</option>
                             </select>
                         </div>
                         <div class="mb-3">
                             <label for="description" class="form-label">Purpose of Description:</label>
                             <textarea cols="5" rows="10" class="form-control" name="purpose_description"></textarea>
                         </div>
                         <div class="mb-3">
                             <label for="doa" class="form-label">Date of Appointment:</label>
                             <input type="text" name="doa" class="form-control" id="doa" readonly
                                 placeholder="Enter Date of Appointment">
                         </div>
                         <div class="mb-3">
                             <button type="submit" name="submit" class="btn btn-primary w-100">Submit</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 </div>