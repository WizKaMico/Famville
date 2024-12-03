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
       $query = "SELECT * FROM fam_user FU LEFT JOIN fam_role FR ON FU.role = FR.role_id WHERE user_id = ?";
        
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
         $query = "INSERT INTO fam_inquiry (name, email, subject, message, date_created) VALUES ( ?, ?, ?, ?, ?)";
        
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
            ),
            array(
                "param_type" => "s",
                "param_value" => date("Y-m-d")
            )
        );
        
        $contact_id = $this->insertDB($query, $params);
        return $contact_id;
    }
    
    function aAddAccount($username, $email, $password, $fullname, $address, $contact)
    {
        $query = "INSERT INTO fam_user (username, email, password, unhashed, role, fullname, address, phone, status, code, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            ),
            array(
                "param_type" => "s",
                "param_value" => date("Y-m-d")
            )
        );
        
        $register_id = $this->insertDB($query, $params);
        return $register_id;
    }


    function lSearchAccountVerification($code, $email)
    {
        $query = "SELECT * FROM fam_user WHERE code=? AND email = ?";
        
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
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function iNoticeShow()
    {
        $query = "SELECT * FROM fam_notice WHERE status='ACTIVE'";   
        $AccountNotice = $this->getDBResult($query);
        return $AccountNotice;
    }

     function showStatsAppointmentCancelled()
    {
        $query = "SELECT COUNT(*) as total FROM fam_appointment WHERE status='CANCELLED' AND schedule_date = CURDATE()";   
        $AccountNotice = $this->getDBResult($query);
        return $AccountNotice;
    }


     function showStatsAppointmentRescheduled()
    {
        $query = "SELECT COUNT(*) as total FROM fam_appointment WHERE status='RESCHEDULED'";   
        $AccountNotice = $this->getDBResult($query);
        return $AccountNotice;
    }

     function showStatsAppointmentBooked()
    {
        $query = "SELECT COUNT(*) as total FROM fam_appointment WHERE status='BOOKED' AND schedule_date = CURDATE()";   
        $AccountNotice = $this->getDBResult($query);
        return $AccountNotice;
    }


     function showStatsAppointmentBookedList()
    {
        $query = "SELECT * FROM fam_appointment WHERE status='BOOKED' AND schedule_date = CURDATE()";   
        $AccountNotice = $this->getDBResult($query);
        return $AccountNotice;
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
         $query = "SELECT * FROM fam_user WHERE email = ?";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $email
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function AccountNewPasswordUpdate($password, $email)
    {
        $query = "UPDATE fam_user SET  password = ?, unhashed = ? WHERE  email = ?";
        
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
            )
        );
        
        $this->updateDB($query, $params);
    }

    function lSearchAccountLogin($username, $password)
    {
        $query = "SELECT * FROM fam_user WHERE username = ? AND password = ? AND status = 'VERIFIED'";
        
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
         $query = "SELECT * FROM fam_appointment WHERE uid = ? AND schedule_date >= CURDATE() AND status = 'BOOKED'";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $uid
            )
        );
        
        $AccountResult = $this->getDBResult($query, $params);
        return $AccountResult;
    }

    function getAllUpcomingAppointmentA()
    {
        $query = "SELECT * FROM fam_appointment WHERE schedule_date >= CURDATE() AND status = 'BOOKED'";
        $AccountResult = $this->getDBResult($query);
        return $AccountResult;
    }

    function getAllPastAppointment($uid)
    {
         $query = "SELECT * FROM fam_appointment WHERE uid = ? AND schedule_date < CURDATE() AND status = 'DONE'";
        
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

    function updateBooking($id, $doa)
    {
        $query = "UPDATE fam_appointment SET  schedule_date = ?, status = 'RESCHEDULED' WHERE  aid = ?";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $doa
            ),
            array(
                "param_type" => "s",
                "param_value" => $id
            )
        );
    
        $register_id = $this->updateDB($query, $params);
        return $register_id;
    }

    function updateInformationBooking($id, $fullname, $date_birth, $gender, $purpose, $age)
    {
        $query = "UPDATE fam_appointment SET  fullname = ?, date_birth = ?, age = ?, purpose = ?, gender = ? WHERE  aid = ?";
        
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $fullname
            ),
            array(
                "param_type" => "s",
                "param_value" => $date_birth
            ),
            array(
                "param_type" => "s",
                "param_value" => $age
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
                "param_type" => "s",
                "param_value" => $id
            )
        );
    
        $register_id = $this->updateDB($query, $params);
        return $register_id;
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

    function activityLog($uid,$activity)
    {
         $query = "INSERT INTO fam_user_activity (uid, activity, date_created) VALUES (?, ?, ?)";
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $uid
            ),
            array(
                "param_type" => "s",
                "param_value" => $activity
            ),
            array(
                "param_type" => "s",
                "param_value" => date("Y-m-d")
            )
        );
        
        $appointment_id = $this->insertDB($query, $params);
        return $appointment_id;
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

    function acceptBooking($fullname, $dob, $age, $purpose, $purpose_description, $gender, $doa, $uid, $pid, $fromIns)
    {
        $query = "INSERT INTO fam_appointment (pid, uid, date_birth, age, fullname, purpose, purpose_description, gender, schedule_date, status, fromIns, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,'BOOKED', ?, ?)";
        $code = rand(666666,999999);
        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $pid
            ),
            array(
                "param_type" => "s",
                "param_value" => $uid
            ),
            array(
                "param_type" => "s",
                "param_value" => $dob
            ),
            array(
                "param_type" => "s",
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
                "param_value" => date("Y-m-d")
            )
        );
        
        $appointment_id = $this->insertDB($query, $params);
        return $appointment_id;
    }
 
}


$portCont = new famVille();