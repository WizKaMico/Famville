-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2025 at 07:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `famville`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `accountHistory` ()   BEGIN
SELECT FU.*,(SELECT FA.fullname FROM fam_appointment FA WHERE FA.uid = FU.user_id) as patient FROM fam_user FU;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `appointment_reports` (IN `date_appointment` VARCHAR(50))   BEGIN
DECLARE isAppointment INT DEFAULT 0;
SELECT COUNT(*) INTO isAppointment FROM fam_appointment WHERE schedule_date = date_appointment;
IF isAppointment > 0 THEN
SELECT COUNT(*) as total,status,schedule_date FROM fam_appointment WHERE schedule_date = date_appointment GROUP BY schedule_date;
ELSE 
SELECT COUNT(*) as total,status,schedule_date FROM fam_appointment WHERE schedule_date = date_appointment GROUP BY schedule_date;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_accountAppointmentBooking` (IN `pid` VARCHAR(50), IN `uid` INT(10), IN `dob` DATE, IN `age` INT(10), IN `fullname` VARCHAR(100), IN `purpose` VARCHAR(100), IN `purpose_description` TEXT, IN `gender` VARCHAR(50), IN `doa` DATE, IN `fromIns` VARCHAR(50), IN `activity` VARCHAR(255))   BEGIN
INSERT INTO fam_appointment (pid, uid, date_birth, age, fullname, purpose, purpose_description, gender, schedule_date, status, fromIns, date_created) VALUES (pid, uid, dob, age, fullname, purpose, purpose_description, gender, doa,'BOOKED', fromIns, CURRENT_DATE());
INSERT INTO fam_user_activity (uid,activity,date_created) VALUES (uid,activity,CURRENT_DATE());
INSERT INTO fam_user_activity_logs (aid,status,date_created) SELECT aid,status,date_created FROM fam_appointment WHERE pid = pid;
SELECT FA.* FROM fam_appointment FA WHERE FA.pid = pid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_accountAppointmentCancelUpdateActivity` (IN `aid` INT(11), IN `uid` INT(11), IN `activity` VARCHAR(255))   BEGIN 
DECLARE isAppointmentExist INT DEFAULT 0;
SELECT COUNT(*) INTO isAppointmentExist FROM fam_appointment WHERE aid = aid;
IF isAppointmentExist > 0 THEN
UPDATE fam_appointment FA SET FA.status = 'CANCELLED' WHERE FA.aid = aid;
INSERT INTO fam_user_activity (uid, activity, date_created) VALUES (uid, activity,CURRENT_DATE()); 
INSERT INTO fam_user_activity_logs (aid,status,date_created) SELECT aid,status,CURRENT_DATE() FROM fam_appointment WHERE aid = aid LIMIT 1;
SELECT FA.* FROM fam_appointment FA WHERE FA.aid = aid; 
ELSE
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Existing Appointment Not Valid';
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_accountAppointmentInformationUpdateActivity` (IN `id` INT(11), IN `date_birth` DATE, IN `age` INT(10), IN `fullname` VARCHAR(100), IN `purpose` VARCHAR(100), IN `gender` VARCHAR(50), IN `uid` INT(11), IN `activity` VARCHAR(255))   BEGIN 
DECLARE userAppointmentExistence INT DEFAULT 0; 
SELECT COUNT(*) INTO userAppointmentExistence FROM fam_appointment FA WHERE FA.aid = id;
IF userAppointmentExistence > 0 THEN 
UPDATE fam_appointment SET fullname = fullname, date_birth = date_birth, gender = gender, purpose = purpose, age = age  WHERE aid = id; 
INSERT INTO fam_user_activity (uid, activity, date_created) VALUES (uid, activity, CURRENT_DATE()); 
SELECT FA.* FROM fam_appointment FA WHERE FA.aid = id; 
ELSE 
SIGNAL SQLSTATE '45000' 
SET MESSAGE_TEXT = 'Schedule cant be seen'; 
END IF; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_accountAppointmentUpdateActivity` (IN `id` INT(11), IN `doa` DATE, IN `uid` INT(11), IN `activity` VARCHAR(255))   BEGIN
DECLARE userAppointmentExistence INT DEFAULT 0;
SELECT COUNT(*) INTO userAppointmentExistence FROM fam_appointment FA WHERE FA.aid = id; 
IF userAppointmentExistence > 0 THEN
UPDATE fam_appointment SET  schedule_date = doa, status = 'RESCHEDULED' WHERE  aid = id;
INSERT INTO fam_user_activity (uid, activity, date_created) VALUES (uid, activity, CURRENT_DATE());
INSERT INTO fam_user_activity_logs (aid,status,date_created) SELECT aid,status,CURRENT_DATE() FROM fam_appointment WHERE aid = id;
SELECT FA.* FROM fam_appointment FA WHERE FA.aid = id;
ELSE 
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Schedule cant be seen';
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_accountLogin` (IN `user_id` INT(11))   BEGIN
DECLARE isUserAccountExisting INT DEFAULT 0;
SELECT COUNT(*) INTO isUserAccountExisting FROM fam_user FU LEFT JOIN fam_role FR ON FU.role = FR.role_id WHERE FU.user_id = user_id; 
IF isUserAccountExisting > 0 THEN
SELECT * FROM fam_user FU LEFT JOIN fam_role FR ON FU.role = FR.role_id WHERE FU.user_id = user_id; 
ELSE
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'No user existing';
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_accountLoginValidation` (IN `username` VARCHAR(150), IN `password` VARCHAR(150))   BEGIN 
DECLARE isAccountExist INT DEFAULT 0;
SELECT COUNT(*) INTO isAccountExist FROM fam_user FU WHERE FU.username = username AND FU.password = password AND FU.status = 'VERIFIED';
IF isAccountExist > 0 THEN
SELECT FU.* FROM fam_user FU WHERE FU.username = username AND FU.password = password AND FU.status = 'VERIFIED';
ELSE
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Account is not existing or not valid';
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_accountNewPasswordUpdate` (IN `password` VARCHAR(150), IN `unhashed` VARCHAR(150), IN `email` VARCHAR(150), IN `code` VARCHAR(50))   BEGIN 
DECLARE isAccountExisting INT DEFAULT 0;
DECLARE isAccountExistingForgot INT DEFAULT 0;
SELECT COUNT(*) INTO isAccountExisting FROM fam_user FU WHERE FU.email = email; 
SELECT COUNT(*) INTO isAccountExistingForgot FROM fam_user_forgot_account FUFA WHERE FUFA.email = email AND FUFA.code = code;
IF isAccountExisting > 0 AND isAccountExistingForgot > 0 THEN
UPDATE fam_user SET password = password, unhashed = unhashed WHERE email = email; 
UPDATE fam_user_forgot_account SET status = 'USED' WHERE email = email;
INSERT INTO fam_user_logs (user_id,username,email,password,unhashed,role,fullname,address,phone,status,code,date_created) SELECT user_id,username,email,password,unhashed,role,fullname,address,phone,status,code,CURRENT_DATE() FROM fam_user WHERE email = email;
SELECT FU.* FROM fam_user FU WHERE FU.email = email;
ELSE 
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Account unexisting';
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_accountPastAppointment` (IN `uid` INT(11))   BEGIN
DECLARE isUserHasAppointment INT DEFAULT 0; 
SELECT COUNT(*) INTO isUserHasAppointment FROM fam_appointment FA WHERE FA.uid = uid AND FA.status = 'COMPLETED';
IF isUserHasAppointment > 0 THEN
SELECT * FROM fam_appointment FA LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid WHERE FA.status = 'COMPLETED' AND FA.uid = uid;
ELSE 
SELECT * FROM fam_appointment FA LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid WHERE FA.status = 'COMPLETED' AND FA.uid = uid;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_accountSearchViaEmail` (IN `email` VARCHAR(150), IN `code` VARCHAR(50))   BEGIN 
 DECLARE isAccount INT DEFAULT 0;
 SELECT COUNT(*) INTO isAccount FROM fam_user FU WHERE FU.email = email; 
 IF isAccount > 0 THEN
 INSERT INTO fam_user_forgot_account (email,code,status,date_created) VALUES (email,code,'UNUSED',CURRENT_DATE());
 SELECT FU.* FROM fam_user FU WHERE FU.email = email;
 ELSE 
 SIGNAL SQLSTATE '45000'
 SET MESSAGE_TEXT = 'Account is not existing';
 END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_accountVerification` (IN `email` VARCHAR(150), IN `code` VARCHAR(50))   BEGIN
DECLARE famville_verifyAccountCode INT DEFAULT 0;
SELECT COUNT(*) INTO famville_verifyAccountCode FROM fam_user FU WHERE FU.email = email AND FU.code = code; 
IF famville_verifyAccountCode > 0 THEN
UPDATE fam_user SET status = 'VERIFIED' WHERE email = email AND code = code;
INSERT INTO fam_user_logs (user_id,username,email,password,unhashed,role,fullname,address,phone,status,code,date_created) SELECT user_id,username,email,password,unhashed,role,fullname,address,phone,status,code,CURRENT_DATE() FROM fam_user WHERE email = email AND code = code;
SELECT FU.* FROM fam_user FU WHERE FU.email = email AND FU.code = code;
ELSE
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Account is verified';
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_completedBookingAndActivityLog` (IN `aid` INT(11))   BEGIN
DECLARE isAppointmentExist INT DEFAULT 0;
SELECT COUNT(*) INTO isAppointmentExist FROM fam_appointment WHERE aid = aid; 
IF isAppointmentExist > 0 THEN
UPDATE fam_appointment SET status = 'COMPLETED' WHERE aid = aid; 
INSERT INTO fam_user_activity_logs (aid,status,date_created) SELECT aid,status,CURRENT_DATE() FROM fam_appointment WHERE aid = aid;
SELECT FA.fullname as patientname,FA.age as patientage, FA.gender as patientgender, FA.schedule_date as patientcheckup,FU.email as email,FU.phone as contact,FD.doctor as doctor, FAD.diagnosis as diagnosis FROM fam_appointment FA LEFT JOIN fam_user FU ON FA.uid = FU.user_id LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid LEFT JOIN fam_doctors FD ON FAD.attending_physician = FD.docid WHERE FA.aid = aid;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_createAccount` (IN `username` VARCHAR(150), IN `email` VARCHAR(150), IN `password` VARCHAR(150), IN `unhashed` VARCHAR(150), IN `role` INT(20), IN `fullname` VARCHAR(50), IN `address` TEXT, IN `phone` VARCHAR(150), IN `status` VARCHAR(50), IN `code` VARCHAR(50))   BEGIN 
DECLARE isAccountAvailable INT DEFAULT 0;
SELECT COUNT(*) INTO isAccountAvailable FROM fam_user FU WHERE FU.email = email; 
IF isAccountAvailable = 0 THEN
INSERT INTO fam_user (username, email, password, unhashed, role, fullname, address, phone, status, code, date_created) VALUES (username, email, password, unhashed, role, fullname, address, phone, status, code, CURRENT_DATE()); 
INSERT INTO fam_user_logs (user_id ,username, email, password, unhashed, role, fullname, address, phone, status, code, date_created) SELECT user_id ,username, email, password, unhashed, role, fullname, address, phone, status, code, date_created FROM fam_user WHERE email = email;
SELECT FU.* FROM fam_user FU WHERE FU.email = email;
ELSE 
SIGNAL SQLSTATE '45000' 
SET MESSAGE_TEXT = 'Account is already existing';
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_createDoctor` (IN `doctor` VARCHAR(50))   BEGIN 
INSERT INTO fam_doctors (doctor,date_created) VALUES (doctor,CURRENT_DATE());
SELECT * FROM fam_doctors;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_createPurpose` (IN `purpose` VARCHAR(50))   BEGIN 
INSERT INTO fam_purpose (purpose,date_created) VALUES (purpose,CURRENT_DATE());
SELECT * FROM fam_purpose;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_deletePurpose` (IN `purpose_id` INT(11))   BEGIN 
DELETE FROM fam_purpose WHERE purpose_id = purpose_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_doctorAccountHistory` (IN `docid` INT(11))   BEGIN 
DECLARE isDoctorAppointmentExist INT DEFAULT 0;
SELECT COUNT(*) INTO isDoctorAppointmentExist FROM fam_appointment FA LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid LEFT JOIN fam_doctors FD ON FAD.attending_physician = FD.docid WHERE FD.docid = docid;
IF isDoctorAppointmentExist > 0 THEN
SELECT * FROM fam_appointment FA LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid LEFT JOIN fam_doctors FD ON FAD.attending_physician = FD.docid WHERE FD.docid = docid;
ELSE 
SELECT * FROM fam_appointment FA LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid LEFT JOIN fam_doctors FD ON FAD.attending_physician = FD.docid WHERE FD.docid = docid;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_insertInquiry` (IN `name` VARCHAR(150), IN `email` VARCHAR(150), IN `subject` VARCHAR(150), IN `message` TEXT)   BEGIN 
INSERT INTO fam_inquiry (name, email, subject, message, date_created) VALUES (name, email, subject, message,CURRENT_DATE());
SELECT FI.* FROM fam_inquiry FI WHERE FI.email = email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_listDoctors` ()   BEGIN
SELECT FD.* FROM fam_doctors;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_notice` ()   BEGIN 
SELECT FN.* FROM fam_notice FN WHERE FN.status='ACTIVE';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_notifyAccountForAppointment` (IN `aid` INT(11))   BEGIN
DECLARE isAppointmentExist INT DEFAULT 0; 
SELECT COUNT(*) INTO isAppointmentExist FROM fam_appointment FA WHERE FA.aid = aid; 
IF isAppointmentExist > 0 THEN 
SELECT * FROM fam_appointment FA LEFT JOIN fam_user FU ON FA.uid = FU.user_id WHERE FA.aid = aid;
ELSE 
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Error in finding the account corresponding';
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_overallBookingDay` (IN `appointment_date` VARCHAR(50))   BEGIN
DECLARE isReportExisting INT DEFAULT 0;
SELECT COUNT(*) INTO isReportExisting FROM fam_appointment FA WHERE FA.schedule_date = appointment_date; 
IF isReportExisting > 0 THEN
SELECT * FROM fam_appointment FA LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid LEFT JOIN fam_doctors FD ON FD.docid = FAD.attending_physician WHERE FA.schedule_date = appointment_date;
ELSE
SELECT * FROM fam_appointment FA LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid LEFT JOIN fam_doctors FD ON FD.docid = FAD.attending_physician WHERE FA.schedule_date = appointment_date;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_patientAccountAppointment` (IN `uid` INT(10))   BEGIN 
DECLARE userAppointment INT DEFAULT 0;
SELECT COUNT(*) INTO userAppointment FROM fam_appointment FA WHERE FA.uid = uid; 
IF userAppointment > 0 THEN
SELECT FA.* FROM fam_appointment FA WHERE FA.uid = uid AND (FA.status != 'CANCELLED' AND FA.status != 'COMPLETED');
ELSE
SELECT FA.* FROM fam_appointment FA WHERE FA.uid = uid AND (FA.status != 'CANCELLED' AND FA.status != 'COMPLETED');
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_patientAccountHistory` (IN `uid` INT(11))   BEGIN 
DECLARE isAppointmentExist INT DEFAULT 0;
SELECT COUNT(*) INTO isAppointmentExist FROM fam_appointment FA WHERE FA.uid = uid; 
IF isAppointmentExist > 0 THEN
SELECT * FROM fam_appointment FA LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid LEFT JOIN fam_doctors FD ON FAD.attending_physician = FD.docid WHERE FA.uid = uid;
ELSE 
SELECT * FROM fam_appointment FA LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid LEFT JOIN fam_doctors FD ON FAD.attending_physician = FD.docid WHERE FA.uid = uid;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_patientAppointmentDoctor` (IN `aid` INT(11), IN `attending_physician` INT(11), IN `diagnosis` TEXT)   BEGIN 
DECLARE isAppointmentToGainDoctorExisting INT DEFAULT 0;
SELECT COUNT(*) INTO isAppointmentToGainDoctorExisting FROM fam_appointment_diagnosis FAD WHERE FAD.aid = aid;
IF isAppointmentToGainDoctorExisting > 0 THEN 
UPDATE fam_appointment_diagnosis SET attending_physician = attending_physician, diagnosis = diagnosis WHERE aid = aid;
SELECT FA.fullname as patientname,FA.age as patientage, FA.gender as patientgender, FA.schedule_date as patientcheckup,FU.email as email,FU.phone as contact,FD.doctor as doctor, FAD.diagnosis as diagnosis FROM fam_appointment FA LEFT JOIN fam_user FU ON FA.uid = FU.user_id LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid LEFT JOIN fam_doctors FD ON FAD.attending_physician = FD.docid WHERE FA.aid = aid;
ELSE 
INSERT INTO fam_appointment_diagnosis (aid,diagnosis,attending_physician) VALUES (aid,diagnosis,attending_physician);
SELECT FA.fullname as patientname,FA.age as patientage, FA.gender as patientgender, FA.schedule_date as patientcheckup,FU.email as email,FU.phone as contact,FD.doctor as doctor, FAD.diagnosis as diagnosis FROM fam_appointment FA LEFT JOIN fam_user FU ON FA.uid = FU.user_id LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid LEFT JOIN fam_doctors FD ON FAD.attending_physician = FD.docid WHERE FA.aid = aid;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_showDoctors` ()   BEGIN 
SELECT FD.* FROM fam_doctors FD;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_showStatsAppointmentBooked` ()   BEGIN 
SELECT COUNT(*) as total FROM fam_appointment WHERE status='BOOKED' AND schedule_date = CURRENT_DATE();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_showStatsAppointmentBookedList` ()   BEGIN 
SELECT *,FA.aid as aid FROM fam_appointment FA LEFT JOIN fam_appointment_diagnosis FAD ON FA.aid = FAD.aid LEFT JOIN fam_doctors FD ON FAD.attending_physician = FD.docid WHERE FA.status IN ('BOOKED','RESCHEDULED','COMPLETED') AND FA.schedule_date = CURRENT_DATE();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_showStatsAppointmentCancelled` ()   BEGIN 
SELECT COUNT(*) as total FROM fam_appointment WHERE status='CANCELLED' AND schedule_date = CURRENT_DATE();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_showStatsAppointmentCompleted` ()   BEGIN 
SELECT COUNT(*) as total FROM fam_appointment WHERE status='COMPLETED' AND schedule_date = CURRENT_DATE();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_showStatsAppointmentRescheduled` ()   BEGIN 
SELECT COUNT(*) as total FROM fam_appointment WHERE status='RESCHEDULED' AND schedule_date = CURRENT_DATE();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_updateDoctor` (IN `docid` INT(11), IN `doctor` VARCHAR(50))   BEGIN
DECLARE isDoctorIdExist INT DEFAULT 0;
SELECT COUNT(*) INTO isDoctorIdExist FROM fam_doctors FD WHERE FD.docid = docid;
IF isDoctorIdExist > 0 THEN
UPDATE fam_doctors FD SET FD.doctor = doctor WHERE FD.docid = docid;
SELECT FD.* FROM fam_doctors FD WHERE FD.docid = docid;
ELSE
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Not visible';
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_updatePurpose` (IN `purpose_id` INT(11), IN `purpose` VARCHAR(50))   BEGIN 
DECLARE isPurposeExist INT DEFAULT 0;
SELECT COUNT(*) INTO isPurposeExist FROM fam_purpose FP WHERE FP.purpose_id = purpose_id;
IF isPurposeExist > 0 THEN
UPDATE fam_purpose FP SET FP.purpose = purpose WHERE FP.purpose_id = purpose_id;
SELECT FP.* FROM fam_purpose FP WHERE FP.purpose_id = purpose_id;
ELSE 
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Purpose is not existing';
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `famville_viewPurpose` ()   BEGIN
SELECT * FROM fam_purpose;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `fam_appointment`
--

CREATE TABLE `fam_appointment` (
  `aid` int(11) NOT NULL,
  `pid` varchar(50) NOT NULL,
  `uid` int(10) NOT NULL,
  `date_birth` date NOT NULL,
  `age` int(10) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `purpose` varchar(100) NOT NULL,
  `purpose_description` text NOT NULL,
  `gender` varchar(50) NOT NULL,
  `schedule_date` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `fromIns` varchar(50) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fam_appointment`
--

INSERT INTO `fam_appointment` (`aid`, `pid`, `uid`, `date_birth`, `age`, `fullname`, `purpose`, `purpose_description`, `gender`, `schedule_date`, `status`, `fromIns`, `date_created`) VALUES
(1, '250126-79310', 1, '2016-06-26', 8, 'Abegail', 'CHECKUP', 'TEST', 'FEMALE', '2025-01-28', 'COMPLETED', 'WEB', '2025-01-26');

-- --------------------------------------------------------

--
-- Table structure for table `fam_appointment_diagnosis`
--

CREATE TABLE `fam_appointment_diagnosis` (
  `faid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `attending_physician` int(11) DEFAULT NULL,
  `date_checked` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fam_appointment_diagnosis`
--

INSERT INTO `fam_appointment_diagnosis` (`faid`, `aid`, `diagnosis`, `attending_physician`, `date_checked`) VALUES
(1, 1, 'Diagnosis is tuberculusis', 1, '2025-01-27');

-- --------------------------------------------------------

--
-- Table structure for table `fam_doctors`
--

CREATE TABLE `fam_doctors` (
  `docid` int(11) NOT NULL,
  `doctor` varchar(50) DEFAULT NULL,
  `date_created` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fam_doctors`
--

INSERT INTO `fam_doctors` (`docid`, `doctor`, `date_created`) VALUES
(1, 'Jimmy Neutron', '2025-01-27'),
(2, 'Isaan Neutron', '2025-01-27'),
(3, 'Hue Neutron', '2025-01-27'),
(4, 'Gerald Neutron 2', '2025-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `fam_inquiry`
--

CREATE TABLE `fam_inquiry` (
  `cid` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fam_inquiry`
--

INSERT INTO `fam_inquiry` (`cid`, `name`, `email`, `subject`, `message`, `date_created`) VALUES
(1, 'GERALD', 'gmfacistol@outlook.com', 'TEST', 'TEST', '2024-11-13'),
(2, 'Gerald Mico Facistol', 'gmfacistol@outlook.com', 'test', 'test', '2025-01-26');

-- --------------------------------------------------------

--
-- Table structure for table `fam_notice`
--

CREATE TABLE `fam_notice` (
  `nid` int(11) NOT NULL,
  `notice` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fam_notice`
--

INSERT INTO `fam_notice` (`nid`, `notice`, `status`, `date_created`) VALUES
(1, 'Appointments are limited to 9-11 AM. Limited slots available testdaily. Please book early.', 'ACTIVE', '2024-11-16');

-- --------------------------------------------------------

--
-- Table structure for table `fam_purpose`
--

CREATE TABLE `fam_purpose` (
  `purpose_id` int(11) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `date_created` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fam_purpose`
--

INSERT INTO `fam_purpose` (`purpose_id`, `purpose`, `date_created`) VALUES
(1, 'CHECKUP', '2025-01-29'),
(3, 'PEDIA CHECKUP 2', '2025-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `fam_role`
--

CREATE TABLE `fam_role` (
  `role_id` int(11) NOT NULL,
  `designation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fam_role`
--

INSERT INTO `fam_role` (`role_id`, `designation`) VALUES
(1, 'DOCTOR'),
(2, 'ASSISTANT DOCTOR'),
(3, 'PATIENT');

-- --------------------------------------------------------

--
-- Table structure for table `fam_user`
--

CREATE TABLE `fam_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `unhashed` varchar(150) NOT NULL,
  `role` int(20) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(150) NOT NULL,
  `status` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fam_user`
--

INSERT INTO `fam_user` (`user_id`, `username`, `email`, `password`, `unhashed`, `role`, `fullname`, `address`, `phone`, `status`, `code`, `date_created`) VALUES
(1, 'gmfacistol', 'gmfacistol@outlook.com', '5d41402abc4b2a76b9719d911017c592', 'hello', 3, 'Gerald Mico', '10 U206 Tarraville Subdivision, Santa Lucia, Novaliches', '0916653189', 'VERIFIED', '810110', '2025-01-26');

-- --------------------------------------------------------

--
-- Table structure for table `fam_user_activity`
--

CREATE TABLE `fam_user_activity` (
  `hid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fam_user_activity`
--

INSERT INTO `fam_user_activity` (`hid`, `uid`, `activity`, `date_created`) VALUES
(1, 1, 'ADD BOOKING 250126-79310', '2025-01-26'),
(2, 1, 'UPDATED BOOKING 1 FROM DATE.2025-01-28', '2025-01-26'),
(3, 1, 'UPDATED BOOKING INFORMATION 1', '2025-01-26'),
(4, 1, 'UPDATED BOOKING INFORMATION 1', '2025-01-26'),
(5, 1, 'CANCELLED BOOKING 1', '2025-01-26');

-- --------------------------------------------------------

--
-- Table structure for table `fam_user_activity_logs`
--

CREATE TABLE `fam_user_activity_logs` (
  `faid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `date_created` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fam_user_activity_logs`
--

INSERT INTO `fam_user_activity_logs` (`faid`, `aid`, `status`, `date_created`) VALUES
(1, 1, 'BOOKED', '2025-01-26'),
(2, 1, 'RESCHEDULED', '2025-01-26'),
(3, 1, 'CANCELLED', '2025-01-26'),
(4, 1, 'COMPLETED', '2025-01-28');

-- --------------------------------------------------------

--
-- Table structure for table `fam_user_forgot_account`
--

CREATE TABLE `fam_user_forgot_account` (
  `fid` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `code` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `date_created` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fam_user_forgot_account`
--

INSERT INTO `fam_user_forgot_account` (`fid`, `email`, `code`, `status`, `date_created`) VALUES
(1, 'gmfacistol@outlook.com', '699779', 'USED', '2025-01-26');

-- --------------------------------------------------------

--
-- Table structure for table `fam_user_logs`
--

CREATE TABLE `fam_user_logs` (
  `lid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `unhashed` varchar(150) DEFAULT NULL,
  `role` int(20) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(150) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `date_created` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fam_user_logs`
--

INSERT INTO `fam_user_logs` (`lid`, `user_id`, `username`, `email`, `password`, `unhashed`, `role`, `fullname`, `address`, `phone`, `status`, `code`, `date_created`) VALUES
(1, 1, 'gmfacistol', 'gmfacistol@outlook.com', '21232f297a57a5a743894a0e4a801fc3', 'admin', 3, 'Gerald Mico', '10 U206 Tarraville Subdivision, Santa Lucia, Novaliches', '0916653189', 'UNVERIFIED', '810110', '2025-01-26'),
(2, 1, 'gmfacistol', 'gmfacistol@outlook.com', '21232f297a57a5a743894a0e4a801fc3', 'admin', 3, 'Gerald Mico', '10 U206 Tarraville Subdivision, Santa Lucia, Novaliches', '0916653189', 'UNVERIFIED', '810110', '2025-01-26'),
(3, 1, 'gmfacistol', 'gmfacistol@outlook.com', '21232f297a57a5a743894a0e4a801fc3', 'admin', 3, 'Gerald Mico', '10 U206 Tarraville Subdivision, Santa Lucia, Novaliches', '0916653189', 'VERIFIED', '810110', '2025-01-26'),
(4, 1, 'gmfacistol', 'gmfacistol@outlook.com', '5d41402abc4b2a76b9719d911017c592', 'hello', 3, 'Gerald Mico', '10 U206 Tarraville Subdivision, Santa Lucia, Novaliches', '0916653189', 'VERIFIED', '699779', '2025-01-26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fam_appointment`
--
ALTER TABLE `fam_appointment`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `fam_appointment_diagnosis`
--
ALTER TABLE `fam_appointment_diagnosis`
  ADD PRIMARY KEY (`faid`);

--
-- Indexes for table `fam_doctors`
--
ALTER TABLE `fam_doctors`
  ADD PRIMARY KEY (`docid`);

--
-- Indexes for table `fam_inquiry`
--
ALTER TABLE `fam_inquiry`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `fam_notice`
--
ALTER TABLE `fam_notice`
  ADD PRIMARY KEY (`nid`);

--
-- Indexes for table `fam_purpose`
--
ALTER TABLE `fam_purpose`
  ADD PRIMARY KEY (`purpose_id`);

--
-- Indexes for table `fam_role`
--
ALTER TABLE `fam_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `fam_user`
--
ALTER TABLE `fam_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `fam_user_activity`
--
ALTER TABLE `fam_user_activity`
  ADD PRIMARY KEY (`hid`);

--
-- Indexes for table `fam_user_activity_logs`
--
ALTER TABLE `fam_user_activity_logs`
  ADD PRIMARY KEY (`faid`);

--
-- Indexes for table `fam_user_forgot_account`
--
ALTER TABLE `fam_user_forgot_account`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `fam_user_logs`
--
ALTER TABLE `fam_user_logs`
  ADD PRIMARY KEY (`lid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fam_appointment`
--
ALTER TABLE `fam_appointment`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fam_appointment_diagnosis`
--
ALTER TABLE `fam_appointment_diagnosis`
  MODIFY `faid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fam_doctors`
--
ALTER TABLE `fam_doctors`
  MODIFY `docid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fam_inquiry`
--
ALTER TABLE `fam_inquiry`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fam_notice`
--
ALTER TABLE `fam_notice`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fam_purpose`
--
ALTER TABLE `fam_purpose`
  MODIFY `purpose_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fam_role`
--
ALTER TABLE `fam_role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fam_user`
--
ALTER TABLE `fam_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fam_user_activity`
--
ALTER TABLE `fam_user_activity`
  MODIFY `hid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fam_user_activity_logs`
--
ALTER TABLE `fam_user_activity_logs`
  MODIFY `faid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fam_user_forgot_account`
--
ALTER TABLE `fam_user_forgot_account`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fam_user_logs`
--
ALTER TABLE `fam_user_logs`
  MODIFY `lid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
