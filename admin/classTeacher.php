<?php
include_once 'class.DBConnManager.php';

class Teacher{
    public $iTeacherId;
    public $sTeacherName;
    public $sTeacherAddress;
    public $sTeacherEmailid;
    public $sTeacherPassword;
    public $sTeacherQulification;
    public $dTeacherDoj;
    public $sTeacherImage;
    public $iTeacherGradeId;


    function __construct($iTeacherId = NULL){
        if($iTeacherId !==NULL){
            $this->iTeacherId = $iTeacherId;
            $DBMan = new DBConnManager();
            $conn =  $DBMan->getConnInstance();
            $sQuery = "SELECT * FROM `tbl_teacher` ";
            $sSQueryResult = $conn->query($sQuery);
            if($sSQueryResult !== FALSE){
                $aRow = $sSQueryResult->fetch_assoc();
            }
            $this->sTeacherName = $aRow['teacher_name'];
            $this->sTeacherAddress =   $aRow['teacher_address'];
            $this->sTeacherQulification = $aRow['teacher_qulification'];
            $this->dTeacherDoj =   $aRow['teacher_doj'];
            $this->sTeacherImage = $aRow['teacher_image'];
            $this->iTeacherGradeId = $aRow['teacher_grade_id'];
            $this->sTeacherEmailid = $aRow['teacher_emailid'];
        }
    }

    public function fAddTeacher($oTeacher){

        $DBMan = new DBConnManager();
        $conn =  $DBMan->getConnInstance();

        $this->sTeacherName         = $oTeacher['sTeacherName'];
        $this->sTeacherAddress      = $oTeacher['sTeacherAddress'];
        $this->sTeacherPassword     = $oTeacher['sTeacherPassword'];
        $this->sTeacherQulification = $oTeacher['sTeacherQulification'];
        $this->dTeacherDoj          = $oTeacher['dTeacherDoj'];
        $this->sTeacherImage        = $oTeacher['sTeacherImage'];
        $this->iTeacherGradeId      = $oTeacher['iTeacherGradeId'];
        $this->sTeacherEmailid      = $oTeacher['sTeacherEmailid'];

        $sIQuery = " INSERT INTO `tbl_teacher` 
                    (`teacher_name`, `teacher_address`, `teacher_emailid`, `teacher_password`, `teacher_qualification`, `teacher_doj`, `teacher_image`, `teacher_grade_id`) 
                    SELECT * FROM (SELECT '{$this->sTeacherName }','{$this->sTeacherAddress}','{$this->sTeacherEmailid}','{$this->sTeacherPassword }','{$this->sTeacherQulification}','{$this->dTeacherDoj }','{$this->sTeacherImage}','{$this->iTeacherGradeId }' ) as temp 
                    WHERE 
                    NOT EXISTS (
                     SELECT `teacher_emailid` FROM `tbl_teacher` WHERE `teacher_emailid` = '{$this->sTeacherEmailid}'
                    ) LIMIT 1 ";

        $sIQueryResult = $conn->query($sIQuery);

        if($sIQueryResult != FALSE){
            $this->iTeacherId = $conn->insert_id;
        }
        return $this->iTeacherId;
    }
}
?>