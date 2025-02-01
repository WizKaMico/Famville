<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class DBController
{

    // private $host = "localhost";
    // private $user = "root";
    // private $password = "";
    // private $database = "famville";


    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "famville";

    private $conn;

    function __construct()
    {
        $this->conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);
    }

    public static function getConnection()
    {
        if (empty($this->conn)) {
            new Database();
        }
    }

    function getDBResult($query, $params = array())
    {
        $sql_statement = $this->conn->prepare($query);
        if (! empty($params)) {
            $this->bindParams($sql_statement, $params);
        }
        $sql_statement->execute();
        $result = $sql_statement->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }
        
        if (! empty($resultset)) {
            return $resultset;
        }
    }

    function insertDB($query, $params = array())
    {
        $sql_statement = $this->conn->prepare($query);
        if (! empty($params)) {
            $this->bindParams($sql_statement, $params);
        }
        $sql_statement->execute();
        
        $id = mysqli_insert_id ( $this->conn );
        return $id;
    }
    
    function updateDB($query, $params = array())
    {
        $sql_statement = $this->conn->prepare($query);
        if (! empty($params)) {
            $this->bindParams($sql_statement, $params);
        }
        $sql_statement->execute();
    }

    function bindParams($sql_statement, $params)
    {
        $param_type = "";
        foreach ($params as $query_param) {
            $param_type .= $query_param["param_type"];
        }
        
        $bind_params[] = & $param_type;
        foreach ($params as $k => $query_param) {
            $bind_params[] = & $params[$k]["param_value"];
        }
        
        call_user_func_array(array(
            $sql_statement,
            'bind_param'
        ), $bind_params);
    }
}


class famVille extends DBController
{

    function myAccount($session_id)
    {
       $query = "CALL famville_accountLogin(?)";
        
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $session_id
            )
        );
        
        $account = $this->getDBResult($query, $params);
        return $account;
    }
    
    function aAddInquiry($name, $email, $subject, $message)
    {
        date_default_timezone_set('Asia/Manila');
         $query = "CALL famville_insertInquiry(?,?,?,?)";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $name
            ),
            array(
                "param_type" => "s",
                "param_value" => $email
            ),
            array(
                "param_type" => "s",
                "param_value" => $subject
            ),
            array(
                "param_type" => "s",
                "param_value" => $message
            )
        );
        
        $inquiryResult = $this->getDBResult($query, $params);
        return $inquiryResult;
    }
    
    function aAddAccount($username, $email, $password, $fullname, $address, $contact)
    {
        $query = "CALL famville_createAccount (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $code = rand(666666,999999);
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $username
            ),
            array(
                "param_type" => "s",
                "param_value" => $email
            ),
            array(
                "param_type" => "s",
                "param_value" => md5($password)
            ),
            array(
                "param_type" => "s",
                "param_value" => $password
            ),
            array(
                "param_type" => "i",
                "param_value" => 3
            ),
            array(
                "param_type" => "s",
                "param_value" => $fullname
            ),
            array(
                "param_type" => "s",
                "param_value" => $address
            ),
            array(
                "param_type" => "s",
                "param_value" => $contact
            ),
             array(
                "param_type" => "s",
                "param_value" => 'UNVERIFIED'
            ),
             array(
                "param_type" => "s",
                "param_value" => $code 
            )
        );
        
        $account = $this->getDBResult($query, $params);
        return $account;
    }


    function lSearchAccountVerification($code, $email)
    {
        $query = "CALL famville_accountVerification (?,?)";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $email
            ),
            array(
                "param_type" => "s",
                "param_value" => $code
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function iNoticeShow()
    {
        $query = "CALL famville_notice;";   
        $AccountNotice = $this->getDBResult($query);
        return $AccountNotice;
    }

     function showStatsAppointmentCancelled()
    {
        $query = "CALL famville_showStatsAppointmentCancelled;";   
        $AccountNotice = $this->getDBResult($query);
        return $AccountNotice;
    }


     function showStatsAppointmentRescheduled()
    {
        $query = "CALL famville_showStatsAppointmentRescheduled;";   
        $AccountNotice = $this->getDBResult($query);
        return $AccountNotice;
    }

     function showStatsAppointmentBooked()
    {
        $query = "CALL famville_showStatsAppointmentBooked;";   
        $AccountNotice = $this->getDBResult($query);
        return $AccountNotice;
    }

    function showStatsAppointmentCompleted()
    {
        $query = "CALL famville_showStatsAppointmentCompleted;";   
        $AccountNotice = $this->getDBResult($query);
        return $AccountNotice;
    }


     function showStatsAppointmentBookedList()
    {
        $query = "CALL famville_showStatsAppointmentBookedList;";   
        $AccountNotice = $this->getDBResult($query);
        return $AccountNotice;
    }

    function showDoctors()
    {
        $query = "CALL famville_showDoctors;";   
        $doctorsResult = $this->getDBResult($query);
        return $doctorsResult;
    }

    function updateDoctor($docid, $doctor)
    {
        $query = "CALL famville_updateDoctor(?,?)";
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $docid
            ),
            array(
                "param_type" => "s",
                "param_value" => $doctor
            )
        );
        
        $DoctorResult = $this->getDBResult($query, $params);
        return $DoctorResult;
    }

    function updatePurpose($purpose_id, $purpose)
    {
        $query = "CALL famville_updatePurpose(?,?)";
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $purpose_id
            ),
            array(
                "param_type" => "s",
                "param_value" => $purpose
            )
        );
        
        $PurposeResult = $this->getDBResult($query, $params);
        return $PurposeResult;
    }

    function deletePurpose($purpose_id)
    {
        $query = "DELETE FROM fam_purpose WHERE purpose_id = ?";
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $purpose_id
            )
        );
        
        $PurposeResult = $this->getDBResult($query, $params);
        return $PurposeResult;
    }

    function showPurpose()
    {
        $query = "CALL famville_viewPurpose;";   
        $purposeResult = $this->getDBResult($query);
        return $purposeResult;
    }

    function AssignDoctor($aid,$attending_physician,$diagnosis)
    {
        $query = "CALL famville_patientAppointmentDoctor(?,?,?)";
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $aid
            ),
            array(
                "param_type" => "i",
                "param_value" => $attending_physician
            ),
            array(
                "param_type" => "s",
                "param_value" => $diagnosis
            )
        );
        
        $AppointmentResult = $this->getDBResult($query, $params);
        return $AppointmentResult;
    }

    function completedBookingAndActivityLog($aid)
    {
        $query = "CALL famville_completedBookingAndActivityLog(?)";
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $aid
            )
        );
        
        $AppointmentResult = $this->getDBResult($query, $params);
        return $AppointmentResult;
    }

    function notifyAccountForAppointment($aid)
    {
        $query = "CALL famville_notifyAccountForAppointment(?)";
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $aid
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function createDoctor($doctor)
    {
        $query = "CALL famville_createDoctor(?)";
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $doctor
            )
        );
        
        $DoctorResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function createPurpose($purpose)
    {
        $query = "CALL famville_createPurpose(?)";
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $purpose
            )
        );
        
        $PurposeResult = $this->getDBResult($query, $params);
        return $PurposeResult;
    }

    function AccountVerificationCompleted($code, $email)
    {
        $query = "UPDATE fam_user SET  status = 'VERIFIED' WHERE  code=? AND email = ?";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $code
            ),
            array(
                "param_type" => "s",
                "param_value" => $email
            )
        );
        
        $this->updateDB($query, $params);
    }

    function lSearchAccount($email)
    {
        $query = "CALL famville_accountSearchViaEmail(?,?)";
        $code = rand(666666,999999);
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $email
            ),
            array(
                "param_type" => "s",
                "param_value" => $code
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function AccountNewPasswordUpdate($password, $email, $code)
    {
        $query = "CALL famville_accountNewPasswordUpdate(?,?,?,?)";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => md5($password)
            ),
            array(
                "param_type" => "s",
                "param_value" => $password
            ),
            array(
                "param_type" => "s",
                "param_value" => $email
            ),
            array(
                "param_type" => "s",
                "param_value" => $code
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function lSearchAccountLogin($username, $password)
    {
        $query = "CALL famville_accountLoginValidation(?,?)";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $username
            ),
            array(
                "param_type" => "s",
                "param_value" => $password
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function getAllUpcomingAppointment($uid)
    {
         $query = "CALL famville_patientAccountAppointment(?)";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $uid
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function getAllUpcomingAppointmentHistoryForPatient($uid)
    {
        $query = "CALL famville_getAllUpcomingAppointmentHistoryForPatient(?)";
        
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $uid
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function patientAccountHistory($uid)
    {
        $query = "CALL famville_patientAccountHistory(?)";
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $uid
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function doctorAccountHistory($docid)
    {
        $query = "CALL famville_doctorAccountHistory(?)";
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $docid
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function patientAccount()
    {
        $query = "CALL accountHistory";
        $AccountResult = $this->getDBResult($query);
        return $AccountResult;
    }

    function getAllPastAppointment($uid)
    {
        $query = "CALL famville_accountPastAppointment(?)";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $uid
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function getAaccountActivity($uid)
    {
         $query = "SELECT * FROM fam_user_activity WHERE uid = ? ORDER BY hid DESC";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $uid
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function getReports($date_appointment)
    {
        $query = "CALL appointment_reports(?);";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $date_appointment
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function getReportOnDay($date_appointment)
    {
        $query = "CALL famville_overallBookingDay(?)";
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $date_appointment
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function famville_accountAppointmentInformationUpdateActivity($id, $date_birth, $age, $fullname, $purpose, $gender, $uid, $activity)
    {
        $query = "CALL famville_accountAppointmentInformationUpdateActivity (?,?,?,?,?,?,?,?)";
        
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $id
            ),
            array(
                "param_type" => "s",
                "param_value" => $date_birth
            ),
            array(
                "param_type" => "i",
                "param_value" => $age
            ),
            array(
                "param_type" => "s",
                "param_value" => $fullname
            ),
            array(
                "param_type" => "s",
                "param_value" => $purpose
            ),
            array(
                "param_type" => "s",
                "param_value" => $gender
            ),
            array(
                "param_type" => "i",
                "param_value" => $uid
            ),
            array(
                "param_type" => "s",
                "param_value" => $activity
            )
        );
        
        $AppointmentResult = $this->getDBResult($query, $params);
        return $AppointmentResult;
    }

    function cancelBookingAndActivityLog($aid,$uid,$activity)
    {
        $query = "CALL famville_accountAppointmentCancelUpdateActivity (?,?,?)";
        
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $aid
            ),
            array(
                "param_type" => "i",
                "param_value" => $uid
            ),
            array(
                "param_type" => "s",
                "param_value" => $activity
            )
        );
        
        $AppointmentResult = $this->getDBResult($query, $params);
        return $AppointmentResult;
    }


    function deleteBooking($aid)
    {
         $query = "UPDATE fam_appointment SET  status = 'CANCELLED' WHERE  aid = ?";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $aid
            )
        );
    
        $register_id = $this->updateDB($query, $params);
        return $register_id;
    }

    function lSearchAccountAppointment($pid)
    {
        $query = "SELECT * FROM fam_appointment WHERE pid = ?";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $pid
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    
    function updateBookingAndActivityLog($id,$doa,$uid,$activity)
    {
        $query = "CALL famville_accountAppointmentUpdateActivity(?,?,?,?)";
        
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $id
            ),
            array(
                "param_type" => "s",
                "param_value" => $doa
            ),
            array(
                "param_type" => "i",
                "param_value" => $uid
            ),
            array(
                "param_type" => "s",
                "param_value" => $activity
            )

        );

        $AppointmentResult = $this->getDBResult($query, $params);
        return $AppointmentResult;
    }

    function updateInformationAccount($username, $email, $contact, $address, $uid)
    {
        $query = "UPDATE fam_user SET  username = ?, email = ?, address = ?, phone = ? WHERE  user_id = ?";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $username
            ),
            array(
                "param_type" => "s",
                "param_value" => $email
            ),
            array(
                "param_type" => "s",
                "param_value" => $address
            ),
            array(
                "param_type" => "s",
                "param_value" => $contact
            ),
            array(
                "param_type" => "s",
                "param_value" => $uid
            )
        );
    
        $register_id = $this->updateDB($query, $params);
        return $register_id;
    }

    function acceptBooking($fullname, $dob, $age, $purpose, $purpose_description, $gender, $doa, $uid, $pid, $fromIns, $activity)
    {
        date_default_timezone_set('Asia/Manila');
        $query = "CALL famville_accountAppointmentBooking (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $pid
            ),
            array(
                "param_type" => "i",
                "param_value" => $uid
            ),
            array(
                "param_type" => "s",
                "param_value" => $dob
            ),
            array(
                "param_type" => "i",
                "param_value" => $age
            ),
            array(
                "param_type" => "s",
                "param_value" => $fullname
            ),
            array(
                "param_type" => "s",
                "param_value" => $purpose
            ),
            array(
                "param_type" => "s",
                "param_value" => $purpose_description
            ),
            array(
                "param_type" => "s",
                "param_value" => $gender
            ),
            array(
                "param_type" => "s",
                "param_value" => $doa
            ),
            array(
                "param_type" => "s",
                "param_value" => $fromIns
            ),
            array(
                "param_type" => "s",
                "param_value" => $activity
            )
        );
        
        $AppointmentResult = $this->getDBResult($query, $params);
        return $AppointmentResult;
    }
 
}


$portCont = new famVille();