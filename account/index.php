<?php include('../connection/home_connection.php');  ?>
<?php include('../connection/session.php'); ?>

<?php 
//ACCOUNT
$account = $portCont->myAccount($session_id); 
//NOTICE
$notice = $portCont->iNoticeShow();
//STATS 
$statCancelled = $portCont->showStatsAppointmentCancelled();
$statResched = $portCont->showStatsAppointmentRescheduled();
$statBooked = $portCont->showStatsAppointmentBooked();
$statCompleted = $portCont->showStatsAppointmentCompleted();
//STATS


if(!empty($_GET['action']))
{
  switch($_GET['action'])
  {
      case "BOOK":
        if(isset($_POST['submit']))
        {
            $view = $_GET['view'];
            $fullname = filter_input(INPUT_POST,"fullname",FILTER_SANITIZE_STRING);
            $dob = filter_input(INPUT_POST,"dob",FILTER_SANITIZE_STRING);

            function calculateAge($dob) {
                $dobDate = new DateTime($dob);
                $currentDate = new DateTime(); 
                $age = $dobDate->diff($currentDate)->y; 
                return $age;
            }

            $age = calculateAge($dob);
            $purpose = filter_input(INPUT_POST,"purpose",FILTER_SANITIZE_STRING);
            $purpose_description = filter_input(INPUT_POST,"purpose_description",FILTER_SANITIZE_STRING);
            $gender = filter_input(INPUT_POST,"gender",FILTER_SANITIZE_STRING);
            $doa = filter_input(INPUT_POST,"doa",FILTER_SANITIZE_STRING);
            $uid = $account[0]['user_id'];
            $pid = date('ymd').'-'.rand(66666,99999);
            $fromIns = filter_input(INPUT_POST,"fromIns",FILTER_SANITIZE_STRING);
            $email = $account[0]['email'];
            if(!empty($fullname) && !empty($dob) && !empty($age) && !empty($purpose) && !empty($purpose_description) && !empty($gender) && !empty($doa))
            {
                try
                {
                    $activity = 'ADD BOOKING '.$pid;
                    $result = $portCont->acceptBooking($fullname, $dob, $age, $purpose, $purpose_description, $gender, $doa, $uid, $pid, $fromIns, $activity);
                    $appointmentPatient = strtoupper($result[0]["fullname"]);
                    $appointmentSchedule = $result[0]["schedule_date"];
                    $appointmentStatus = $result[0]["status"];
                    if(!empty($appointmentPatient) && !empty($appointmentSchedule) && !empty($appointmentStatus))
                    {
                        $activity = "${appointmentStatus} BOOKING HAS BEEN ADDED FOR PATIENT : ${appointmentPatient} | SCHEDULE : ${appointmentSchedule}";
                        require("../connection/mail/checkUpNotification.php");
                        header('Location: ?view=HOME&message=success');
                        exit;
                    }
                }
                catch(Exception $e)
                {
                    header('Location: ?view='.$view.'&message=failed');
                }
            }
        }
            break;
        case "HOMEAPPOINTMENT":
            if(isset($_POST['edit']))
            {
                $id = filter_input(INPUT_POST,"id",FILTER_VALIDATE_INT); 
                $doa = filter_input(INPUT_POST,"doa",FILTER_SANITIZE_STRING);
                $uid = $account[0]['user_id'];
                $email = $account[0]['email'];
                if(!empty($id) && !empty($doa))
                {
                    try
                    {
                        $activity = 'UPDATED BOOKING '.$id.' FROM DATE.'.$doa;
                        $result = $portCont->updateBookingAndActivityLog($id,$doa,$uid,$activity);
                        $appointmentPatient = strtoupper($result[0]["fullname"]);
                        $appointmentSchedule = $result[0]["schedule_date"];
                        $appointmentStatus = $result[0]["status"];
                        if(!empty($appointmentPatient) && !empty($appointmentSchedule) && !empty($appointmentStatus))
                        {
                            $activity = "${appointmentStatus} BOOKING FOR PATIENT : ${appointmentPatient} | SCHEDULE : ${appointmentSchedule}";
                            require("../connection/mail/checkUpNotification.php");
                            header('Location: ?view=HOME&message=success');
                            exit;
                        }
                    }
                    catch(Exception $e)
                    {
                        header('Location: ?view=HOME&message=failed');
                    }
                }
            }
            break;
        case "CANCEL":
            if(!empty($_GET['id']))
            {
                $aid = $_GET['id'];
                $uid = $account[0]['user_id']; 
                $email = $account[0]['email']; 
                if(!empty($aid))
                {
                    try
                    {
                        $activity = 'CANCELLED BOOKING '.$aid;
                        $result = $portCont->cancelBookingAndActivityLog($aid,$uid,$activity);
                        $appointmentStatus = $result[0]['status'];
                        $appointmentPatient = $result[0]['fullname'];
                        $appointmentSchedule = $result[0]['schedule_date'];
                        if(!empty($appointmentStatus) && !empty($appointmentPatient) && !empty($appointmentSchedule))
                        {
                            $activity = "${appointmentStatus} BOOKING FOR PATIENT : ${appointmentPatient} | SCHEDULE : ${appointmentSchedule}";
                            require("../connection/mail/checkUpNotification.php");
                            header('Location: ?view=HOME&message=success');
                            exit;
                        }
                    }
                    catch(Exception $e)
                    {
                       header('Location: ?view=HOME&message=failed');
                    }
                }
            }
            break;
        case "NOTIFY":
            if(!empty($_GET['id']))
            {
                $aid = $_GET['id'];
                if(!empty($aid))
                {
                    try
                    {
                        $result = $portCont->notifyAccountForAppointment($aid);
                        $accountName = $result[0]["email"];
                        $accountScheduleDate = $result[0]["schedule_date"];
                        $phone = $result[0]["phone"];
                        if(!empty($accountName) && !empty($accountScheduleDate) && !empty($phone))
                        {
                            $to = preg_replace('/^0/', '+63', $phone);
                            $message = "Appointment Reminder ${accountName} - ${accountScheduleDate}";
                            require("../connection/sms/api.php");
                            header('Location: ?view=HOME&message=success');
                            exit;
                        }
                    }
                    catch(Exception $e)
                    {
                        header('Location: ?view=HOME&message=failed');
                    }
                }
            }
            break;
        case "NOTIFYMAIL":
            if(!empty($_GET['id']))
            {
                $aid = $_GET['id'];
                if(!empty($aid))
                {
                    try
                    {
                        $result = $portCont->notifyAccountForAppointment($aid);
                        $accountName = $result[0]["email"];
                        $accountScheduleDate = $result[0]["schedule_date"];
                        $phone = $result[0]["phone"];
                        if(!empty($accountName) && !empty($accountScheduleDate) && !empty($phone))
                        {
                            $email = $accountName;
                            $activity = "APPOINTMENT REMINDER FOR BOOKING OF PATIENT : ${accountName} | SCHEDULE : ${accountScheduleDate}";
                            require("../connection/mail/checkUpNotification.php");
                            header('Location: ?view=HOME&message=success');
                            exit;
                        }
                    }
                    catch(Exception $e)
                    {
                        header('Location: ?view=HOME&message=failed');
                    }
                }
            }
            break;
        case "HOMEAPPOINTMENTINFORMATION":
             if(isset($_POST['edit']))
                {
                    $id = filter_input(INPUT_POST,"id",FILTER_VALIDATE_INT); 
                    $fullname = filter_input(INPUT_POST,"fullname",FILTER_SANITIZE_STRING); 
                    $date_birth = $_POST['date_birth'];
                    $gender = filter_input(INPUT_POST,"gender",FILTER_SANITIZE_STRING);  
                    $purpose = filter_input(INPUT_POST,"purpose",FILTER_SANITIZE_STRING); 

                    function calculateAge($date_birth) {
                        $dobDate = new DateTime($date_birth);
                        $currentDate = new DateTime(); 
                        $age = $dobDate->diff($currentDate)->y; 
                        return $age;
                    }

                    $age = calculateAge($date_birth);
                    $uid = $account[0]['user_id']; 
                    $email = $account[0]['email'];
                    if(!empty($id) && !empty($fullname)  && !empty($date_birth)  && !empty($gender)  && !empty($purpose))
                    {
                        try
                        {   
                            $activity = 'UPDATED BOOKING INFORMATION '.$id;
                            $result = $portCont->famville_accountAppointmentInformationUpdateActivity($id, $date_birth, $age, $fullname, $purpose, $gender, $uid, $activity);
                            $appointmentPatient = $result[0]["fullname"];
                            $appointmentSchedule = $result[0]["schedule_date"];
                            if(!empty($appointmentPatient))
                            {
                                $activity = "UPDATED BOOKING INFORMATION FOR PATIENT : ${appointmentPatient} | SCHEDULE : ${appointmentSchedule}";
                                require("../connection/mail/checkUpNotification.php");
                                header('Location: ?view=HOME&message=success');
                                exit;
                            }
                        }
                        catch(Exception $e)
                        {
                            header('Location: ?view=HOME&message=failed');
                        }
                    }
                }
            break;
            case "ASSIGNDOCTOR":
              if(isset($_POST['assign']))
              {
                $aid = filter_input(INPUT_POST,"id",FILTER_VALIDATE_INT);
                $attending_physician = filter_input(INPUT_POST,"attending_physician",FILTER_VALIDATE_INT);
                $diagnosis = filter_input(INPUT_POST,"diagnosis",FILTER_SANITIZE_STRING);
                if(!empty($aid) && !empty($attending_physician))
                {
                    try
                    {
                        $result = $portCont->AssignDoctor($aid,$attending_physician,$diagnosis);
                        $patientname = $result[0]['patientname'];
                        $patientage = $result[0]['patientage'];
                        $patientgender = $result[0]['patientgender'];
                        $patientcheckup = $result[0]['patientcheckup'];
                        $email = $result[0]['email'];
                        $contact = $result[0]['contact'];
                        $doctor = $result[0]['doctor'];
                        $diagnosis = $result[0]['diagnosis'];
                        if(!empty($diagnosis))
                        {
                            $activity = "BOOKING INFORMATION FOR PATIENT : ${patientname} | SCHEDULE : ${patientcheckup} | DOCTOR : ${doctor} | DIAGNOSIS : ${diagnosis}";
                            require("../connection/mail/checkUpNotification.php");
                            header('Location: ?view=HOME&message=success');
                            exit;
                        }
                        else
                        {
                            $activity = "BOOKING INFORMATION FOR PATIENT : ${patientname} | SCHEDULE : ${patientcheckup} | DOCTOR : ${doctor}";
                            require("../connection/mail/checkUpNotification.php");
                            header('Location: ?view=HOME&message=success');
                            exit;
                        }
                    }
                    catch(Exception $e)
                    {
                        header('Location: ?view=HOME&message=failed');
                    }
                }
              }
            break;
            case "ACCOUNT":
              if(isset($_POST['submit']))
                {
                    $uid = $account[0]['user_id']; 
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $contact = $_POST['contact'];
                    $address = $_POST['address'];
                    if(!empty($username) && !empty($email) && !empty($contact) && !empty($address))
                    {
                         $activity = 'UPDATED ACCOUNT INFORMATION';
                         $portCont->activityLog($uid,$activity);
                         $portCont->updateInformationAccount($username, $email, $contact, $address, $uid);
                         header('Location: ?view=ACCOUNT&message=success');
                         exit;
                    }
                }
              break;
              case "COMPLETEBOOKING":
                if(!empty($_GET['id']))
                {
                    $aid = $_GET['id'];
    
                    if(!empty($aid))
                    {
                        try
                        {
                            $activity = 'COMPLETED BOOKING '.$aid;
                            $result = $portCont->completedBookingAndActivityLog($aid);
                            $patientname = $result[0]['patientname'];
                            $patientage = $result[0]['patientage'];
                            $patientgender = $result[0]['patientgender'];
                            $patientcheckup = $result[0]['patientcheckup'];
                            $email = $result[0]['email'];
                            $contact = $result[0]['contact'];
                            $doctor = $result[0]['doctor'];
                            $diagnosis = $result[0]['diagnosis'];
                            if(!empty($diagnosis))
                            {
                                $activity = "BOOKING INFORMATION FOR PATIENT : ${patientname} | SCHEDULE : ${patientcheckup} | DOCTOR : ${doctor} | DIAGNOSIS : ${diagnosis} | COMPLETED";
                                require("../connection/mail/checkUpNotification.php");
                                header('Location: ?view=HOME&message=success');
                                exit;
                            }
                            else
                            {
                                $activity = "BOOKING INFORMATION FOR PATIENT : ${patientname} | SCHEDULE : ${patientcheckup} | DOCTOR : ${doctor}";
                                require("../connection/mail/checkUpNotification.php");
                                header('Location: ?view=HOME&message=success');
                                exit;
                            }
                        }
                        catch(Exception $e)
                        {
                            header('Location: ?view=HOME&message=failed');
                        }
                    }
                }
              break;

              case "REPORTS":
                if(isset($_POST['generate']))
                {
                    $date_appointment = filter_input(INPUT_POST,"date_appointment",FILTER_SANITIZE_STRING);
                    if(!empty($date_appointment))
                    {
                        header("Location: ?view=REPORTS&date_appointment=${date_appointment}&message=success");
                    }
                }
              break;
              
              case "DOCSERVEUPDATE":
                if(isset($_POST['edit']))
                {
                    $docid = filter_input(INPUT_POST,"docid",FILTER_VALIDATE_INT);
                    $doctor = filter_input(INPUT_POST,"doctor",FILTER_SANITIZE_STRING);
                    if(!empty($docid) && !empty($doctor))
                    {
                        try
                        {
                            $portCont->updateDoctor($docid, $doctor);
                            header('Location: ?view=DOCSERVE&message=success');
                        }
                        catch(Exception $e)
                        {
                            header('Location: ?view=DOCSERVE&message=failed');
                        }
                    }
                }
              break;

              case "DOCSERVEPURPOSEUPDATE":
                if(isset($_POST['edit']))
                {
                    $purpose_id = filter_input(INPUT_POST,"purpose_id",FILTER_VALIDATE_INT);
                    $purpose = filter_input(INPUT_POST,"purpose",FILTER_SANITIZE_STRING);
                    if(!empty($purpose_id) && !empty($purpose))
                    {
                        try
                        {
                            $portCont->updatePurpose($purpose_id, $purpose);
                            header('Location: ?view=DOCSERVE&message=success');
                        }
                        catch(Exception $e)
                        {
                            header('Location: ?view=DOCSERVE&message=failed');
                        }
                    }
                }
              break;
              case "REMOVEPURPOSE":
                if(!empty($_GET['purpose_id']))
                {
                    $purpose_id = filter_input(INPUT_GET,"purpose_id",FILTER_VALIDATE_INT);
                    if(!empty($purpose_id))
                    {
                        try
                        {
                            $portCont->deletePurpose($purpose_id);
                            header('Location: ?view=DOCSERVE&message=success');
                        }
                        catch(Exception $e)
                        {
                            header('Location: ?view=DOCSERVE&message=failed');
                        }
                    }
                }
              break;
              case "DOCSERVECREATEDOCTOR":
                if(isset($_POST['create']))
                {
                    $doctor = filter_input(INPUT_POST,"doctor",FILTER_SANITIZE_STRING);
                    if(!empty($doctor))
                    {
                        try
                        {
                            $portCont->createDoctor($doctor);
                            header('Location: ?view=DOCSERVE&message=success');
                        }
                        catch(Exception $e)
                        {
                            header('Location: ?view=DOCSERVE&message=failed');
                        }
                    }
                }
              break;
              case "DOCSERVECREATEPURPOSE":
                if(isset($_POST['create']))
                {
                    $purpose = filter_input(INPUT_POST,"purpose",FILTER_SANITIZE_STRING);
                    if(!empty($purpose))
                    {
                        try
                        {
                            $portCont->createPurpose($purpose);
                            header('Location: ?view=DOCSERVE&message=success');
                        }
                        catch(Exception $e)
                        {
                            header('Location: ?view=DOCSERVE&message=failed');
                        }   
                    }
                }
              break;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Family Vill Clinic</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <!-- partial:index.partial.html -->
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Language" content="en">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Family Vill Clinic</title>
        <meta name="viewport"
            content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
        <meta name="description" content="This is an example dashboard created using build-in elements and components.">
        <meta name="msapplication-tap-highlight" content="no">
        <link href="https://demo.dashboardpack.com/architectui-html-free/main.css" rel="stylesheet">
        <!-- Include Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Include Flatpickr CSS -->
        <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <?php include('../component/alert/alert.php'); ?>



    </head>

    <body>
        <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
            <?php include('../route/account/header/header.php'); ?>
            <?php include('../route/account/layout/layout.php'); ?>
            <div class="app-main">
                <?php include('../route/account/sidebar/sidebar.php'); ?>
                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <?php 
                        $role = $account[0]['role_id'];
                        $view = $_GET['view'];
                        if($role == 1)
                        {
                            switch($view)
                           {
                              case "HOME":
                                include('../route/account/content/doctor/dashboard.php');
                                break;
                              case "PATIENT":
                                include('../route/account/content/doctor/patient.php');
                                break;
                              case "ACCOUNTHISTORY":
                                 include('../route/account/content/assistant/patienthistory.php');
                                 break;
                              case "SCHEDULING":
                                include('../route/account/content/doctor/schedule.php');
                                include('../assets/css/book.php');
                                break;
                              case "REPORTS":
                                include('../route/account/content/doctor/reports.php');
                                break;
                              case "DATEBOOKINGSPECIFIC":
                                include('../route/account/content/assistant/specific_reports.php');
                                break;
                              case "ACCOUNT":
                                include('../route/account/content/doctor/account.php');
                                break;
                              case "LOGOUT":
                                include('../route/account/content/doctor/dashboard.php');
                                break;
                              default:
                                include('../route/account/notfound/notfound.php');
                                break;
                           } 
                        }
                        else if($role == 2)
                        {
                            switch($view)
                           {
                              case "HOME":
                                include('../route/account/content/assistant/dashboard.php');
                                break;
                              case "PATIENT":
                                include('../route/account/content/assistant/patient.php');
                                break;
                              case "ACCOUNTHISTORY":
                                include('../route/account/content/assistant/patienthistory.php');
                                break;
                              case "DOCSERVE":
                                include('../route/account/content/assistant/docserve.php');
                                break;
                              case "DOCTORSAPPOINTMENT":
                                include('../route/account/content/assistant/doctorsappointment.php');
                                break;
                              case "SCHEDULING":
                                include('../route/account/content/assistant/schedule.php');
                                include('../assets/css/book.php');
                                break;
                              case "REPORTS":
                                include('../route/account/content/assistant/reports.php');
                                break;
                              case "DATEBOOKINGSPECIFIC":
                                include('../route/account/content/assistant/specific_reports.php');
                                break;
                              case "ACCOUNT":
                                include('../route/account/content/assistant/account.php');
                                break;
                              case "LOGOUT":
                                include('../route/account/content/assistant/dashboard.php');
                                break;
                              default:
                                include('../route/account/notfound/notfound.php');
                                break;
                           }
                        }
                        else
                        {
                           switch($view)
                           {
                              case "HOME":
                                include('../route/account/notice/notice.php');
                                include('../route/account/content/patient/dashboard.php');
                                break;
                              case "HISTORY":
                                include('../route/account/notice/notice.php');
                                include('../route/account/content/patient/history.php');
                                break;
                              case "BOOK":
                                include('../route/account/content/patient/book.php');
                                include('../assets/css/book.php');
                                break;
                              case "ACCOUNT":
                                include('../route/account/content/patient/account.php');
                                break;
                             case "LOGOUT":
                                include('../route/account/notice/notice.php');
                                include('../route/account/content/patient/dashboard.php');
                                break;
                              default:
                                include('../route/account/notfound/notfound.php');
                                break;
                           }
                        }

                        ?>
                    </div>
                    <?php include('../route/account/footer/footer.php'); ?>
                </div>
                <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
            </div>
        </div>
        <!-- Modal -->
        <?php include('../route/account/modal/appointment_modal.php'); ?>
        <!-- Modal -->
        <script type="text/javascript"
            src="https://demo.dashboardpack.com/architectui-html-free/assets/scripts/main.js"></script>
        <!-- partial -->

        <!-- DataTables CSS for Bootstrap 5 -->
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

        <!-- DataTables JavaScript for Bootstrap 5 -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>


        <!-- Include Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Include Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <script>
        <?php 
    
        $view = isset($_GET['view']) ? $_GET['view'] : 'default'; 

        ?>
        document.addEventListener("DOMContentLoaded", function() {
            const view = "<?php echo $view; ?>";
            switch (view) {

                case 'HOME':
                    loadScript('../assets/js/dt.js');
                    loadScript('../assets/js/md.js');
                    break;
                case 'BOOK':
                    loadScript('../assets/js/ap.js');
                    break;
                case 'PATIENT':
                    loadScript('../assets/js/dt.js');
                    break;
                case 'HISTORY':
                    loadScript('../assets/js/dt.js');
                    break;
                case 'ACCOUNTHISTORY':
                    loadScript('../assets/js/dt.js');
                    break;
                case 'SCHEDULING':
                    loadScript('../assets/js/ap_ad.js');
                    break;
                case 'REPORTS':
                    loadScript('../assets/js/dt.js');
                    break;
                case 'DOCSERVE':
                    loadScript('../assets/js/dt.js');
                    break;
                case 'DOCTORSAPPOINTMENT':
                    loadScript('../assets/js/dt.js');
                    break;
                case 'DATEBOOKINGSPECIFIC':
                    loadScript('../assets/js/dt.js');
                    break;
                case 'ACCOUNT':
                    loadScript('../assets/js/dt.js');
                    break;

            }

        });

        function loadScript(src) {
            const script = document.createElement('script');
            script.src = src;
            document.head.appendChild(script);
        }
        </script>
        <!-- generate datatable on our table -->

    </body>

    </html>

</body>

</html>