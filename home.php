<?php include('connection/home_connection.php');  ?>
<?php include('connection/session.php'); ?>

<?php 
//ACCOUNT
$account = $portCont->myAccount($session_id); 
//NOTICE
$notice = $portCont->iNoticeShow();
//STATS 
$statCancelled = $portCont->showStatsAppointmentCancelled();
$statResched = $portCont->showStatsAppointmentRescheduled();
$statBooked = $portCont->showStatsAppointmentBooked();
//STATS


if(!empty($_GET['action']))
{
  switch($_GET['action'])
  {
      case "BOOK":
        if(isset($_POST['submit']))
        {
            $fullname = $_POST['fullname'];
            $contact = $_POST['contact'];
            $dob = $_POST['dob'];

            function calculateAge($dob) {
                $dobDate = new DateTime($dob);
                $currentDate = new DateTime(); 
                $age = $dobDate->diff($currentDate)->y; 
                return $age;
            }

            $age = calculateAge($dob);
            $purpose = $_POST['purpose'];
            $purpose_description = $_POST['purpose_description'];
            $gender = $_POST['gender'];
            $doa = $_POST['doa'];
            $uid = $account[0]['user_id'];
            $pid = date('ymd').'-'.rand(66666,99999);
            $fromIns = $_POST['fromIns'];
            if(!empty($fullname) && !empty($contact) && !empty($dob) && !empty($age) && !empty($purpose) && !empty($purpose_description) && !empty($gender) && !empty($doa))
            {
                $activity = 'ADD BOOKING '.$pid;
                $result = $portCont->acceptBooking($fullname, $dob, $age, $purpose, $purpose_description, $gender, $doa, $uid, $pid, $fromIns);
                $activity = $portCont->activityLog($uid,$activity);
                if(!empty($result) && !empty($activity))
                {
                    header('Location: ?view=BOOK&pid='.$pid.'&message=success');
                    exit;
                }
                else
                {
                    header('Location: ?view=BOOK&message=failed');
                    exit;
                }
            }
        }
            break;
        case "HOMEAPPOINTMENT":
            if(isset($_POST['edit']))
            {
                $id = $_POST['id']; 
                $doa = $_POST['doa'];
                $uid = $account[0]['user_id']; 
                if(!empty($id) && !empty($doa))
                {
                    $activity = 'UPDATED BOOKING '.$id.' FROM DATE.'.$doa;
                    $portCont->updateBooking($id, $doa);
                    $portCont->activityLog($uid,$activity);
                    header('Location: ?view=HOME&message=success');
                    exit;
                }
            }
            break;
        case "CANCEL":
            if(!empty($_GET['id']))
            {
                $aid = $_GET['id'];
                $uid = $account[0]['user_id']; 
                if(!empty($aid))
                {
                    $activity = 'CANCELLED BOOKING '.$aid;
                    $portCont->activityLog($uid,$activity);
                    $portCont->deleteBooking($aid);
                    header('Location: ?view=HOME&message=success');
                    exit;
                }
            }
            break;
        case "HOMEAPPOINTMENTINFORMATION":
             if(isset($_POST['edit']))
                {
                    $id = $_POST['id']; 
                    $fullname = $_POST['fullname']; 
                    $date_birth = $_POST['date_birth']; 
                    $gender = $_POST['gender']; 
                    $purpose = $_POST['purpose']; 
                    function calculateAge($date_birth) {
                        $dobDate = new DateTime($date_birth);
                        $currentDate = new DateTime(); 
                        $age = $dobDate->diff($currentDate)->y; 
                        return $age;
                    }

                    $age = calculateAge($date_birth);
                    $uid = $account[0]['user_id']; 
                    if(!empty($id) && !empty($fullname)  && !empty($date_birth)  && !empty($gender)  && !empty($purpose))
                    {
                        $activity = 'UPDATED BOOKING INFORMATION '.$id;
                        $portCont->activityLog($uid,$activity);
                        $portCont->updateInformationBooking($id, $fullname, $date_birth, $gender, $purpose, $age);
                        header('Location: ?view=HOME&message=success');
                        exit;
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
    <link rel="stylesheet" href="assets/css/style.css">

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
        <?php include('component/alert/alert.php'); ?>



    </head>

    <body>
        <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
            <?php include('route/account/header/header.php'); ?>
            <?php include('route/account/layout/layout.php'); ?>
            <div class="app-main">
                <?php include('route/account/sidebar/sidebar.php'); ?>
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
                                include('route/account/content/doctor/dashboard.php');
                                break;
                              case "PATIENT":
                                include('route/account/content/doctor/patient.php');
                                break;
                              case "SCHEDULING":
                                include('route/account/content/doctor/schedule.php');
                                include('assets/css/book.php');
                                break;
                              case "REPORTS":
                                include('route/account/content/doctor/reports.php');
                                break;
                              case "ACCOUNT":
                                include('route/account/content/doctor/account.php');
                                break;
                              case "LOGOUT":
                                include('route/account/content/doctor/dashboard.php');
                                break;
                              default:
                                include('route/account/notfound/notfound.php');
                                break;
                           } 
                        }
                        else if($role == 2)
                        {
                            switch($view)
                           {
                              case "HOME":
                                include('route/account/content/assistant/dashboard.php');
                                break;
                              case "PATIENT":
                                include('route/account/content/assistant/patient.php');
                                break;
                              case "SCHEDULING":
                                include('route/account/content/assistant/schedule.php');
                                include('assets/css/book.php');
                                break;
                              case "REPORTS":
                                include('route/account/content/assistant/reports.php');
                                break;
                              case "ACCOUNT":
                                include('route/account/content/assistant/account.php');
                                break;
                              case "LOGOUT":
                                include('route/account/content/assistant/dashboard.php');
                                break;
                              default:
                                include('route/account/notfound/notfound.php');
                                break;
                           }
                        }
                        else
                        {
                           switch($view)
                           {
                              case "HOME":
                                include('route/account/notice/notice.php');
                                include('route/account/content/patient/dashboard.php');
                                break;
                              case "BOOK":
                                include('route/account/content/patient/book.php');
                                include('assets/css/book.php');
                                break;
                              case "ACCOUNT":
                                include('route/account/content/patient/account.php');
                                break;
                             case "LOGOUT":
                                include('route/account/notice/notice.php');
                                include('route/account/content/patient/dashboard.php');
                                break;
                              default:
                                include('route/account/notfound/notfound.php');
                                break;
                           }
                        }

                        ?>
                    </div>
                    <?php include('route/account/footer/footer.php'); ?>
                </div>
                <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
            </div>
        </div>
        <!-- Modal -->
        <?php include('route/account/modal/appointment_modal.php'); ?>
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
                    loadScript('assets/js/dt.js');
                    loadScript('assets/js/md.js');
                    break;
                case 'BOOK':
                    loadScript('assets/js/ap.js');
                    break;
                case 'PATIENT':
                    loadScript('assets/js/dt.js');
                    break;
                case 'SCHEDULING':
                    loadScript('assets/js/ap.js');
                    break;
                case 'ACCOUNT':
                    loadScript('assets/js/dt.js');
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