<?php
include_once 'classTeacher.php';
include_once 'class.DBConnManager.php';

$aFunctionMap = array(
    'addUpdateTeacher' => "ajax_addUpdateTeacher",
);

$iFunc = $_REQUEST['sFlag'];

if (isset($aFunctionMap[$iFunc])) {
    $method = $aFunctionMap[$iFunc];
    $aResponse = $method($_REQUEST);
    header("Content-Type: application/json");
    echo json_encode($aResponse);
}
else {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

function ajax_addUpdateTeacher($aRequestData) {

    $sAction = isset($aRequestData['action']) ? $aRequestData['action'] : '';
    $sTeacherName = isset($aRequestData['teacher_name']) ? $aRequestData['teacher_name'] : '';
    $sTeacherAddress = isset($aRequestData['teacher_address']) ? $aRequestData['teacher_address'] : '';
    $sTeacherPassword = isset($aRequestData['teacher_password']) ? $aRequestData['teacher_password'] : '';
    $sTeacherQulification = isset($aRequestData['teacher_qualification']) ? $aRequestData['teacher_qualification'] : '';
    $iTeacherGradeId = isset($aRequestData['teacher_grade_id']) ? $aRequestData['teacher_grade_id'] : '';
    $iTeacherId = isset($aRequestData['teacher_id']) ? $aRequestData['teacher_id'] : '';
    $sTeacherImage = isset($aRequestData['hidden_teacher_image']) ? $aRequestData['hidden_teacher_image'] : '';
    $sTeacherEmailid = isset($aRequestData['teacher_emailid']) ? $aRequestData['teacher_emailid'] : '';
    $dTeacherDoj = isset($aRequestData['teacher_doj']) ? $aRequestData['teacher_doj'] : '';

    //! Define Variables for Error and success
    $teacher_name = '';
    $teacher_address = '';
    $teacher_emailid = '';
    $teacher_password = '';
    $teacher_grade_id = '';
    $teacher_qualification = '';
    $teacher_doj = '';
    $teacher_image = '';
    $error_teacher_name = '';
    $error_teacher_address = '';
    $error_teacher_emailid = '';
    $error_teacher_password = '';
    $error_teacher_grade_id = '';
    $error_teacher_qualification = '';
    $error_teacher_doj = '';
    $error_teacher_image = '';
    $error = 0;
    $aTeacher = array();
    if ($_FILES["teacher_image"]["name"] != '') {
        $file_name = $_FILES["teacher_image"]["name"];
        $tmp_name = $_FILES["teacher_image"]['tmp_name'];
        $extension_array = explode(".", $file_name);
        $extension = strtolower($extension_array[1]);
        $allowed_extension = array(
            'jpg',
            'png'
        );
        if (!in_array($extension, $allowed_extension)) {
            $error_teacher_image = 'Invalid Image Format';
            $error++;
        }
        else {
            $teacher_image = uniqid() . '.' . $extension;
            $upload_path = 'teacher_image/' . $teacher_image;
            $Upload = move_uploaded_file($tmp_name, $upload_path);

        }
    }
    else {
        if ($teacher_image == '') {
            $error_teacher_image = $teacher_image;
            $error++;
        }
    }

    if (empty($sTeacherName)) {
        $error_teacher_name = 'Teacher Name is required';
        $error++;
    }
    else {
        $teacher_name = $sTeacherName;
    }
    if (empty($sTeacherAddress)) {
        $error_teacher_address = 'Teacher Address is required';
        $error++;
    }
    else {
        $teacher_address = $sTeacherAddress;
    }

    if ($sAction == "Add") {
        if (empty($sTeacherAddress)) {
            $error_teacher_emailid = 'Email Address is required';
            $error++;
        }
        else {
            if (!filter_var($sTeacherEmailid, FILTER_VALIDATE_EMAIL)) {
                $error_teacher_emailid = "Invalid email format";
                $error++;
            }
            else {
                $teacher_emailid = $sTeacherEmailid;
            }
        }
        if (empty($sTeacherPassword)) {
            $error_teacher_password = 'Password is required';
            $error++;
        }
        else {
            $teacher_password = $sTeacherPassword;
        }
    }
    if (empty($iTeacherGradeId)) {
        $error_teacher_grade_id = 'Grade is required';
        $error++;
    }
    else {
        $teacher_grade_id = $iTeacherGradeId;
    }
    if (empty($sTeacherQulification)) {
        $error_teacher_qualification = 'Qualification Field is required';
        $error++;
    }
    else {
        $teacher_qualification = $sTeacherQulification;
    }
    if (empty($dTeacherDoj)) {
        $error_teacher_doj = 'Date of Join Field is required';
        $error++;
    }
    else {
        $teacher_doj = $dTeacherDoj;
    }

    if ($error > 0) {
        $output = array(
            'error' => true,
            'error_teacher_name' => $error_teacher_name,
            'error_teacher_address' => $error_teacher_address,
            'error_teacher_emailid' => $error_teacher_emailid,
            'error_teacher_password' => $error_teacher_password,
            'error_teacher_grade_id' => $error_teacher_grade_id,
            'error_teacher_qualification' => $error_teacher_qualification,
            'error_teacher_doj' => $error_teacher_doj,
            'error_teacher_image' => $error_teacher_image
        );
    }
    else {
        if ($sAction == "Add") {
            $aTeacher['sTeacherName'] = $teacher_name;
            $aTeacher['sTeacherAddress'] = $teacher_address;
            $aTeacher['sTeacherEmailid'] = $teacher_emailid;
            $aTeacher['sTeacherPassword'] = password_hash($teacher_password, PASSWORD_DEFAULT);
            $aTeacher['iTeacherGradeId'] = $teacher_grade_id;
            $aTeacher['dTeacherDoj'] = $teacher_doj;
            $aTeacher['sTeacherImage'] = $teacher_image;
            $aTeacher['sTeacherQulification'] = $teacher_qualification;

            $oTeacher = new Teacher();
            $iTeacherid = $oTeacher->fAddTeacher($aTeacher);
            if ($iTeacherid > 0) {
                $output = array(
                    'success' => 'Grade Added Successfully'
                );
            }else{
                $output = array(
                    'error' => true,
                    'error_teacher_emailid' => 'This Email Is Already Registered',
                );
            }
        }
    }
    return $output;
}

