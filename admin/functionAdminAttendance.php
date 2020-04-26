<?php
include_once 'class.DBConnManager.php';

function fgetAllGrades($iStart = 0, $iLength = 1000, $aFilters = array() , $bCountOnly = false, $aSort = array()) {

    $aData = array();
    $DBMan = new DBConnManager();
    $conn = $DBMan->getConnInstance();

    $sWhereClause = "";
    //! Filter by product id..
    // if(isset($aFilters['iProductID']) && $aFilters['iProductID'] > 0){
    // 	$sWhereClause .= " AND B.`product_id` = '{$aFilters['iProductID']}'";
    // }
    //! Sorting...
    if ($iLength > 0) {
        if (!empty($aSort)) {
            $sSortColum = $aSort['sColumn'];
            $sOrderByColumn = "";
            if ($sSortColum == "sCategory") {
                $sOrderByColumn = " `tbl_grade`.`grade_name`";
            }
            $sWhereClause .= " ORDER BY {$sOrderByColumn} {$aSort['sOrder']}";
        }
        else {
            $sWhereClause .= " ORDER BY  tbl_grade.`grade_name` DESC";
        }
    }

    //! Pagination..
    if ($iLength > 0) {
        $sWhereClause .= " LIMIT {$iStart},{$iLength}";
    }

    if ($bCountOnly == true) {
        $sQuery = " SELECT count(*) FROM `tbl_grade`  WHERE `status` = 1  {$sWhereClause}";

        $rResult = $conn->query($sQuery);
        if ($rResult) {
            $aRow = $rResult->fetch_array();
            return $aRow[0];
        }
        else {
            return $conn->error;
        }
    }

    $sSQuery = "SELECT * FROM `tbl_grade`  WHERE `status` = 1  {$sWhereClause}";

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

function fAddGrade($sGradeName){
	$DBMan = new DBConnManager();
    $conn = $DBMan->getConnInstance();

    if($sGradeName != ''){
    	$iQuery = "INSERT INTO tbl_grade (grade_name) SELECT * FROM (SELECT  '{$sGradeName}') AS temp  
    				WHERE
    				NOT EXISTS (
     				SELECT grade_name FROM tbl_grade WHERE grade_name = '{$sGradeName}' AND status = 1) LIMIT 1";
    }

	if($conn != false){
		$sQuery = $conn->query($iQuery);
		if($sQuery != false){
			$iInsertID = $conn->insert_id;
		}
	}
	return $iInsertID;
}

function fUpdateGrade($iGradeId,$sGradeName){
	$sStatus = false;
	$DBMan = new DBConnManager();
    $conn =  $DBMan->getConnInstance();
	$uQuery = "UPDATE `tbl_grade` SET `grade_name` = '{$sGradeName}' WHERE `grade_id` = '{$iGradeId}' ";

	if($conn != false) {
		$uQueryR = $conn->query($uQuery);
		if($uQueryR != false) {
			$sStatus = true;
		}
	}	
	return $sStatus;
}

function fDeleteGrade($iGradeId){
	$sStatus = false;
	$DBMan = new DBConnManager();
    $conn =  $DBMan->getConnInstance();
	$uQuery = "UPDATE `tbl_grade` SET `status` = 0 WHERE `grade_id` = '{$iGradeId}' ";

	if($conn != false) {
		$uQueryR = $conn->query($uQuery);
		if($uQueryR != false) {
			$sStatus = true;
		}
	}	
	return $sStatus;
}

function fGetGradeList(){
    $output = '';
    $aData=array();
    $DBMan = new DBConnManager();
    $conn =  $DBMan->getConnInstance();

    $query = "SELECT * FROM tbl_grade where `status` = 1 ORDER BY grade_name ASC";

    $sQuery = $conn->query($query);

    while ($aRow = $sQuery->fetch_assoc()) {
        $aData[] = $aRow;
    }

    foreach($aData as $row){
        $output .= '<option value="'.$row["grade_id"].'">'.$row["grade_name"].'</option>';
    }
    return $output;
}
?>
