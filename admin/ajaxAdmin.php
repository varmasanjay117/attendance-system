<?php
include_once 'functionAdminAttendance.php';
include_once 'class.DBConnManager.php';

$aFunctionMap = array(
    'allStudentAttendance' => "ajax_allStudentAttendance",
    'getAllGrade' => "ajax_getAllGrade",
    'addUpdateGrade' => 'ajax_addUpdateGrade',
    'addDeleteGrade'=> 'ajax_addDeleteGrade'
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

function ajax_allStudentAttendance($aRequestData) {

}

function ajax_getAllGrade($aRequestData) {
    $iStartLimit = isset($_GET['start']) ? $_GET['start'] : 0;
    $iLength = isset($_GET['length']) ? $_GET['length'] : 0;
    // $iEntityTypeID = isset($_GET['iEntityTypeID']) ? $_GET['iEntityTypeID'] : 0;
    

    $aSort = array();

    if (isset($_GET['order'][0]['column']) && isset($_GET['order'][0]['dir'])) {
        $iIndexColum = $_GET['order'][0]['column'];
        $aSort['sOrder'] = $_GET['order'][0]['dir'];
        if ($iIndexColum == 1) {
            $aSort['sColumn'] = "sCategory";
        }
    }

    $aFilters = array(
        //If anay filter
        // 'iEntityTypeID' => $iEntityTypeID
        
    );

    $aActualData = fgetAllGrades($iStartLimit, $iLength, $aFilters, false, $aSort);

    //! Fetching count data..
    $iFilterCount = fgetAllGrades($iStartLimit, 0, $aFilters, true, $aSort);

    $iSrNo = $iStartLimit + 1;
    foreach ($aActualData as $aDetails) {

        $sEdit = '<button type="button" id="idBtnUpdate_' . $aDetails['grade_id'] . '" class="btn btn-info btn-small classUpdateGrade" data-grade-name="'.$aDetails['grade_name'].'">Update</button>';
        $sDelete = '<button type="button" id="idDelete_' . $aDetails['grade_id'] . '" class="btn btn-danger btn-small ClassDeleteGrade" >Delete</button>';
        $aGradeData[] = array(
            $iSrNo,
            'sgradename' => $aDetails['grade_name'],
            'sEdit' => $sEdit,
            'sDelete' => $sDelete
        );
        $iSrNo++;
    }

    $aData = array(
        'data' => array() ,
        'draw' => $_GET['draw'],
        "recordsTotal" => $iFilterCount,
        "recordsFiltered" => $iFilterCount
    );

    foreach ($aGradeData as $colData) {
        $aData['data'][] = array_values($colData);
    }

    return $aData;
}

function ajax_addUpdateGrade($aRequestData){

	$sAction = isset($aRequestData['action'])?$aRequestData['action'] : '';
	$sGradeName = isset($aRequestData['grade_name'])?$aRequestData['grade_name'] : '';
	$iGradeId = isset($aRequestData['grade_id'])?$aRequestData['grade_id'] : '';

	//! Define Variables for Error and success
	$grade_name = '';
	$error_grade_name = '';
	$error =0;

	if ($sGradeName ==''){
		$error_grade_name = "Grade Name Required";
		$error ++;
	}else{
		$grade_name =	$sGradeName;
	}

	if($error >0){
		$output=array(
			'error' => true,
			'error_grade_name' =>$error_grade_name
		);
	}else{
		if($sAction == 'Add'){
			$iAddGrade = fAddGrade($sGradeName);
			if($iAddGrade > 0){
				$output =array(
					'success' => 'Grade Added Successfully'
				);
			}else{
				$output=array(
					'error' => true,
					'error_grade_name' =>'Grade Name Already Exists'
				);
			}
		}
		if($sAction == 'Update'){
			$bUpdateGrade = fUpdateGrade($iGradeId,$sGradeName);
			if($bUpdateGrade){
				$output =array(
					'success' => 'Update Added Successfully'
				);
			}
		}
	}
	return $output;
}


function ajax_addDeleteGrade($aRequestData){

	$iGradeId = isset($aRequestData['grade_id'])?$aRequestData['grade_id'] : 0;
	if($iGradeId > 0){
		$bUpdateGrade = fDeleteGrade($iGradeId);
		if($bUpdateGrade){
			$output =array(
					'success' => 'Deleted Successfully'
			);
		}else{
			$output =array(
					'error' => 'Failed to delete'
			);
		}
	}
	return $output;
}
?>
