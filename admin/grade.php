<?php

include_once 'header.php';

?>

<div class="container" style="margin-top:30px">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9">Grade List</div>
                <div class="col-md-3" align="right">
                    <button type="button" id="add_button" class="btn btn-info btn-sm">Add</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <span id="message_operation"></span>
                <table class="table table-striped table-bordered" id="grade_table">
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Grade Name</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Grade from Modal -->
<div class="modal" id="formModal">
    <div class="modal-dialog">
        <form method="post" id="grade_form">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Grade Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="grade_name" id="grade_name" class="form-control" /> <span id="error_grade_name" class="text-danger"></span> </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" name="grade_id" id="grade_id" />
                    <input type="hidden" name="action" id="action" value="Add" />
                    <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete grade -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Delete Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h3 align="center">Are you sure you want to remove this?</h3> </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <input type="hidden" name="grade_id" id="grade_id" />
                <button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">OK</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</body>

</html>

<script>
$(document).ready(function() {
    window.oTableUIDataTable = $('#grade_table').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "pageLength": 20,
        "order": [],
        "columnDefs": [{
            "aTargets": [-1,-2,0],
            "orderable": false
        }],
        "ajax": {
            "url": "ajaxAdmin.php?sFlag=getAllGrade",
        },
        "lengthChange": false
    });

    $('#add_button').click(function() {
        $('#modal_title').text("Add Grade");
        $('#button_action').val('Add');
        $('#action').val('Add');
        $('#formModal').modal('show');
        clear_field();
    });

    function clear_field() {
        $('#grade_form')[0].reset();
        $('#error_grade_name').text('');
    }

    $('#grade_form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: 'ajaxAdmin.php?sFlag=addUpdateGrade',
            method:"POST",
            data: $(this).serialize(),
            dataType:"json",
            beforeSend: function(){
                $('#button_action').attr('disabled', 'disabled');
                $('#button_action').val('Validate.....');
            },
            success: function(data){
                if(data.success){
                    pNotifyCustomAlert(data.success, 'success');
                    clear_field();
                    oTableUIDataTable.ajax.reload();
                    $('#formModal').modal('hide');
                    $('#button_action').attr('disabled', false);
                    $('#button_action').val('Add');
                }
                if(data.error){
                    pNotifyCustomAlert(data.error_grade_name, 'error');
                    $('#button_action').attr('disabled', false);
                    $('#button_action').val('Add');
                }else{
                    $('#error_grade_name').text('');
                }
            }
        });
    });

    var iGradeid = '';
    $(document).on('click','.classUpdateGrade', function(){
        var sUpdateId = $(this).attr('id');
        var temp = sUpdateId.split("_");
        var iGradeid =temp[1];
        var sGradeName = $(this).attr('data-grade-name');
        $('#modal_title').text("Update Grade");
        $('#button_action').val('Update');
        $('#action').val('Update');
        $('#formModal').modal('show');
        clear_field();
        $('#grade_id').val(iGradeid);
        $('#grade_name').val(sGradeName);
    });

    $(document).on('click', '.ClassDeleteGrade', function(){
        var sUpdateId = $(this).attr('id');
        var temp = sUpdateId.split("_");
        var iGradeid =temp[1];
        $('#deleteModal').modal('show');
        $('#grade_id').val(iGradeid);
    });

    $('#ok_button').click(function(){
        iGradeid = $('#grade_id').val();
        $.ajax({
            url: 'ajaxAdmin.php?sFlag=addDeleteGrade',
            method:"POST",
            data:{grade_id:iGradeid},
            success:function(data){
                console.log(data);
                if(data.success){
                    pNotifyCustomAlert(data.success, 'success');
                }
                if(data.error){
                    pNotifyCustomAlert(data.error, 'error');
                }
                $('#deleteModal').modal('hide');
                oTableUIDataTable.ajax.reload();
        }
    });
  });



    function pNotifyCustomAlert(sNotifyText, sNotifyType) {
        new PNotify({
            'text': sNotifyText,
            'type': sNotifyType,
            'animation': 'none',
            'delay': 8000,
            'buttons': {
                'sticker': false
            }
        });
    }
});
</script>