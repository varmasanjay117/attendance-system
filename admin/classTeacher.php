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
    public $sGradeName;


    function __construct($iTeacherId = NULL){
        if($iTeacherId !==NULL){
            $this->iTeacherId = $iTeacherId;
            $DBMan = new DBConnManager();
            $conn =  $DBMan->getConnInstance();
            $sQuery = "SELECT * FROM `tbl_teacher` INNER JOIN `tbl_grade` ON `tbl_grade`.`grade_id` = `tbl_teacher`.`teacher_grade_id` 
            WHERE `tbl_teacher`.`status` = 1  AND `tbl_teacher`.`teacher_id`='{$this->iTeacherId}' ";
            $sSQueryResult = $conn->query($sQuery);
            if($sSQueryResult !== FALSE){
                $aRow = $sSQueryResult->fetch_assoc();
            }
            $this->sTeacherName = $aRow['teacher_name'];
            $this->sTeacherAddress =   $aRow['teacher_address'];
            $this->sTeacherQulification = $aRow['teacher_qualification'];
            $this->dTeacherDoj =   $aRow['teacher_doj'];
            $this->sTeacherImage = $aRow['teacher_image'];
            $this->iTeacherGradeId = $aRow['teacher_grade_id'];
            $this->sTeacherEmailid = $aRow['teacher_emailid'];
            $this->sGradeName = $aRow['grade_name'];
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

    public function fGetAllTeacher($iStart = 0, $iLength = 1000, $aFilters = array() , $bCountOnly = false, $aSort = array()){

        $aData = array();
        $DBMan = new DBConnManager();
        $conn = $DBMan->getConnInstance();

        $sWhereClause = "";
        //! Filter by product id..
        // if(isset($aFilters['iProductID']) && $aFilters['iProductID'] > 0){
        //  $sWhereClause .= " AND B.`product_id` = '{$aFilters['iProductID']}'";
        // }
        //! Sorting...
        if ($iLength > 0) {
            if (!empty($aSort)) {
                $sSortColum = $aSort['sColumn'];
                $sOrderByColumn = "";
                if ($sSortColum == "sTeacherName") {
                    $sOrderByColumn = " `tbl_teacher`.`teacher_name`";
                }else if ($sSortColum == "sEmailid"){
                    $sOrderByColumn = " `tbl_teacher`.`teacher_emailid`";
                }else if($sSortColum == "sGrade"){
                    $sOrderByColumn = " `tbl_grade`.`grade_name`";
                }
                $sWhereClause .= " ORDER BY {$sOrderByColumn} {$aSort['sOrder']}";
            }
            else {
                $sWhereClause .= " ORDER BY  tbl_teacher.`teacher_name` DESC";
            }
        }

        //! Pagination..
        if ($iLength > 0) {
            $sWhereClause .= " LIMIT {$iStart},{$iLength}";
        }

        if ($bCountOnly == true) {
            $sQuery = "SELECT count(*) FROM `tbl_teacher` INNER JOIN `tbl_grade` ON `tbl_grade`.`grade_id` = `tbl_teacher`.`teacher_grade_id` 
                WHERE `tbl_teacher`.`status` = 1 AND `tbl_grade`.`status`=1  {$sWhereClause}";

            $rResult = $conn->query($sQuery);
            if ($rResult) {
                $aRow = $rResult->fetch_array();
                return $aRow[0];
            }
            else {
                return $conn->error;
            }
        }

        $sSQuery = "SELECT * FROM `tbl_teacher` INNER JOIN `tbl_grade` ON `tbl_grade`.`grade_id` = `tbl_teacher`.`teacher_grade_id` 
            WHERE `tbl_teacher`.`status` = 1 AND `tbl_grade`.`status`=1  {$sWhereClause}";

        if ($conn != false) {
            $sSQueryR = $conn->query($sSQuery);
            if ($sSQueryR !== false) {
                while ($aRow = $sSQueryR->fetch_assoc()) {
                    $aData[] = $aRow;
                }
            }
        }
        return $aData;
    }
}
?>