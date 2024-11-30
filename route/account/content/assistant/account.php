 <div class="row">
     <div class="col-md-8">
         <div class="main-card mb-3 card">
             <div class="card-header">Account Activity</div>
             <div class="table-responsive">
                 <div class="col-md-12 mt-2">
                     <table id="activitiyTable"
                         class="align-middle mb-0 table table-borderless table-striped table-hover">
                         <thead>
                             <th>HID</th>
                             <th>ACTIVITY</th>
                             <th>DATE</th>
                         </thead>
                         <tbody>
                             <?php
                            $uid = $account[0]['user_id'];
                            $activity = $portCont->getAaccountActivity($uid);
                            if (!empty($activity)) {
                                foreach ($activity as $key => $value) {
                                    echo 
                                    "<tr>
                                        <td>".$value['hid']."</td>   
                                        <td>".$value['activity']."</td>
                                        <td>".$value['date_created']."</td>
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
     <div class="col-md-4">
         <div class="main-card mb-3 card">
             <div class="card-header">Account Information</div>
             <div class="table-responsive">
                 <div class="col-md-12 mt-2">
                     <div class="tab">
                         <button class="tablinks active"
                             onclick="openSetting(event, 'Information')">Information</button>
                         <button class="tablinks" onclick="openSetting(event, 'Security')">Security</button>
                     </div>

                     <div id="Information" class="tabcontent mb-5" style="display: block;">
                         <h3>Information</h3>
                         <form action="?view=ACCOUNT&action=ACCOUNT" method="POST">
                             <div class="mb-3 mt-3">
                                 <label for="account_name" class="form-label">Account Name:</label>
                                 <input type="text" class="form-control" value="<?php echo $account[0]['username']; ?>"
                                     name="username" required="">
                             </div>
                             <div class="mb-3 mt-3">
                                 <label for="email" class="form-label">Email:</label>
                                 <input type="email" class="form-control" value="<?php echo $account[0]['email']; ?>"
                                     name="email" required="">
                             </div>
                             <div class="mb-3 mt-3">
                                 <label for="contact" class="form-label">Contact:</label>
                                 <input type="number" class="form-control" value="<?php echo $account[0]['phone']; ?>"
                                     name="contact" required="">
                             </div>
                             <div class="mb-3 mt-3">
                                 <label for="address" class="form-label">Address:</label>
                                 <input type="text" class="form-control" value="<?php echo $account[0]['address']; ?>"
                                     name="address" required="">
                             </div>
                             <div class="mb-3">
                                 <button type="submit" name="submit" class="btn btn-primary w-100">Update</button>
                             </div>
                         </form>
                     </div>

                     <div id="Security" class="tabcontent mb-5" style="display: none;">
                         <h3>Security</h3>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>

 <script>
function openSetting(evt, setting) {
    var i, tabcontent, tablinks;

    // Hide all tab content
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Remove 'active' class from all tab links
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the clicked tab and add 'active' class to its button
    document.getElementById(setting).style.display = "block";
    evt.currentTarget.className += " active";
}
 </script>


 <style>
/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
 </style>