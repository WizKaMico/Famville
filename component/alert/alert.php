    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php
        if (!empty($_GET['message']) || $_GET['view'] == 'LOGOUT') {
            if ($_GET['message'] == 'success' || $_GET['view'] == 'LOGOUT') {
                if($_GET['view'] == 'BOOK')
                {
                    $pid = $_GET['pid'];
                    $result = $portCont->lSearchAccountAppointment($pid);
                     echo '
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "success",
                                title: "Appointment Successfully Booked!",
                                 html: `<div style="text-align: left;">
                                    <ul>
                                        <li>Patient Name: <b>' . $result[0]['fullname'] . '</b></li>
                                        <li>Purpose: <b>' . $result[0]['purpose'] . '</b></li>
                                        <li>Date: <b>' . $result[0]['schedule_date'] . '</b></li>
                                        <li>Patient Number: <b>' . $result[0]['pid'] . '</b></li>
                                    </ul>
                                </div>`
                            });
                        });
                    </script>';
                }
                else if($_GET['view'] == 'LOGOUT')
                {
                   echo '
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "warning",
                                title: "Are you sure you want to logout?",
                                showCancelButton: true,
                                confirmButtonText: "Yes, logout",
                                cancelButtonText: "Cancel",
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Redirect to logout.php
                                    window.location.href = "logout.php";
                                }
                            });
                        });
                    </script>';
                }
                else
                {
                    echo '
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "success",
                                title: "Submission request has been submitted",
                                text: ""
                            });
                        });
                    </script>';
                }
            } else {
                $reason = $_GET['reason'] ?? '';
                if(!empty($reason))
                {
                    echo '
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "error",
                                title: "An error occurred",
                                text: "'.strtoupper($reason).'"
                            });
                        });
                    </script>';
                }
                else
                {
                    echo '
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "error",
                                title: "An error occurred",
                                text: ""
                            });
                        });
                    </script>';
                }
            }
        }
    ?>

    </script>