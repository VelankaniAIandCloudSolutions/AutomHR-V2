					
<div class="content">
	<div class="page-header">
					<div class="row">
		<div class="col-12">
			<h4 class="page-title m-b-0">Parse Resumes</h4>
			<ul class="breadcrumb p-l-0" style="background:none; border:none;">
				<li  class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/dashboard"><?php echo lang('recruiting_process');?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>jobs/candidates">Candidates List</a></li>
				<li class="breadcrumb-item">Parse Resumes</li>
			</ul>
		</div>
	</div>
</div>
	  <?php //$this->load->view('sub_menus');?>
	<!--Canditates List-->
    <?php if (empty($resumes)): ?>

    <form action="<?php echo base_url(); ?>jobs/parse_resumes" method='post' enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="resumes">Upload Resumes</label>
                        <input class="form-control"  type="file" id="resumes" name="resumes[]" multiple>
                </div>
            </div>
        </div>
        <div class="submit-section">
        <input class="btn btn-primary submit-btn m-b-5" type="submit" value="Upload"">
            <a href="<?php echo base_url(); ?>jobs/manage_resumes" class="btn btn-danger submit-btn m-b-5"><?php echo lang('cancel');?></a>
        </div>
    </form>

    <?php else: ?>




		<div class="table-responsive">
        <div class="table-responsive">
    <table class="table table-striped custom-table mb-0" id="resumesTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Skills</th>
                <th>Designation</th>
                <th>Job Category</th>
                <th>Job Position</th>
                <th>Resume</th>
                <th>Save</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resumes as $key => $resume): ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td contenteditable="true"><?php echo $resume['name']; ?></td>
                    <td contenteditable="true"><?php echo $resume['email']; ?></td>
                    <td contenteditable="true"><?php echo $resume['mobile_number']; ?></td>
                    <td contenteditable="true"><?php echo $resume['skills']; ?></td>
                    <td contenteditable="true"><?php echo $resume['designation']; ?></td>
                    <td>
                        <select class="select required" id="department_<?php echo $key; ?>" required>
                            <option value="">Select Job Category</option>
                            <?php 
                            foreach($departments as $key1 => $depart)
                            {
                            ?><option value="<?php echo $depart->deptid;?>"><?php echo $depart->deptname;?></option><?php 
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select class="select required" id="position_<?php echo $key; ?>" required>
                            <option value="">Select Job Position</option>
                            <?php 
                                foreach($designations as $key2 => $desig){
                                    ?><option value="<?php echo $desig->id;?>"><?php echo $desig->designation;?></option><?php 
                                }
                            ?>
                        </select>
                    </td>
                    <td>
                        <a href="<?php echo $resume['get_resume']; ?>" target="_blank">View Resume</a>
                    </td>
                    <td>
                        <button class="save-row btn btn-primary" id="saveButton_<?php echo $key; ?>">Save</button>
                    </td>
                    <td>
                        <button class="delete-row btn btn-danger" id="deleteButton_<?php echo $key; ?>">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

        </div>

        <div class="submit-section">
            <a id='saveButton' class="btn btn-primary submit-btn btn-xs">Save</a>
            <a href="<?php echo base_url(); ?>jobs/candidates" class="btn btn-danger submit-btn btn-xs"><?php echo lang('cancel');?></a>
        </div>
    <?php endif; ?>
	
</div>

	<!--/Canditates List-->

    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.1/css/dataTables.dateTime.min.css"> -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>

<script>
$(document).ready(function() {

    var table  = $('#resumesTable').DataTable({
        dom: 'Bfrtip',
        buttons: [],
        select: true
    });
    
    if ($.fn.DataTable.isDataTable('#resumesTable')) {
        table.destroy();
    }

    $('[id^="saveButton_"]').click(function() {
        editedData = []
        var rowId = $(this).attr('id').split('_')[1]; 
        // Get the specific row in the table
        var $row = $(this).closest('tr');
        // Iterate over each table cell in the current row
        var rowData = {};
        $row.find('td').each(function(cellIndex) {
            console.log('run')

            var columnName = $('#resumesTable thead th').eq(cellIndex).text();
            var cellContent = $(this).text();

            if (columnName === "Save" || columnName === "Delete") {
                    
            }
            else if (columnName === "Job Category") {
                var selectedValue = $('#department_' + rowId).val();
                rowData['category'] = selectedValue;
            } 
            else if (columnName === "Job Position") {
                var selectedValue = $('#position_' + rowId).val();
                rowData['position'] = selectedValue;
            }
            else if (columnName === 'Resume') {
                    cellContent = $(this).find('a').attr('href');
                    rowData[columnName] = cellContent;
                }
            else {
                rowData[columnName] = cellContent;
            }
        });

        if (rowData['Name'] === '' || rowData['Email'] === '' || rowData['position'] === '' || rowData['category'] === '') {
            alert('Please fill in all the required fields of row number: ' + rowData['#']);
            return; 
        }

        console.log(rowData);
        editedData.push(rowData);

        var $saveButton = $(this);
        var $deleteButton = $row.find('.delete-row');

        $.ajax({
            url: '<?php echo base_url().'jobs/save_parsed_resumes'?>',
            type: 'POST',
            data: { editedData: editedData },
            success: function(response) {
                console.log(response);
                
                alert('Candidate Saved Successfully');
                $saveButton.prop('disabled', true);
                $deleteButton.prop('disabled', true);
                $saveButton.text('Saved');

            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });

    $('[id^="deleteButton_"]').click(function() {
        var rowId = $(this).attr('id').split('_')[1]; 
        var $row = $(this).closest('tr');
        var confirmed = confirm('Are you sure you want to delete this row?');
        if (confirmed) {
            $row.remove();
        }

    });

    $('#saveButton').click(function() {
        var editedData = [];
        var fieldsValid = true
        $('#resumesTable tbody tr').each(function(index) {
            var rowData = {};
            var $row = $(this);
            $(this).find('td').each(function(cellIndex) {
                var columnName = $('#resumesTable thead th').eq(cellIndex).text();

                if (columnName === "Save" || columnName === "Delete") {
                    
                } 
                else if (columnName === "Job Category") {
                    var selectedValue = $('#department_' + index).val();
                    cellContent = selectedValue;
                    rowData['category'] = cellContent;

                } 
                else if (columnName === "Job Position") {
                    var selectedValue = $('#position_' + index).val();
                    cellContent = selectedValue;
                    rowData['position'] = cellContent;

                } 
                else if (columnName === 'Resume') {
                    cellContent = $(this).find('a').attr('href');
                    rowData[columnName] = cellContent;
                }
                else{
                    var cellContent = $(this).text();
                    rowData[columnName] = cellContent;
                }

                // rowData[columnName] = cellContent;
            });

            var $saveButton = $row.find('.save-row');
            if ($saveButton.text().trim() === 'Saved') {
                return; 
            }

            if (rowData['Name'] === '' || rowData['Email'] === '' || rowData['position'] === '' || rowData['category'] === '') {
                alert('Please fill in all the required fields of row number: ' + rowData['#']);
                fieldsValid = false;
                return; 
            }

            editedData.push(rowData);
        });
        
        if(fieldsValid && editedData.length!=0){
            $.ajax({
                url: '<?php echo base_url().'jobs/save_parsed_resumes'?>',
                type: 'POST',
                data: { editedData: editedData },
                success: function(response) {
                    console.log(response);
                    alert('Candidates Saved Successfully');
                    window.location.href = "<?php echo base_url(); ?>jobs/candidates"
                    
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
        else{
            alert('All Candidates are already saved, You can view them in the candidates list page.')
        }


    });

    $('select[id^="department_"]').on('change', function() {
    var departmentId = $(this).val();
    var positionsSelect = $(this).closest('td').next('td').find('select[id^="position_"]');
    
    positionsSelect.empty();
    
    var positions = <?php echo json_encode($designations); ?>;
    
    var filteredPositions = positions.filter(function(position) {
      return position.department_id == departmentId;
    });
    
    // Append positions as options
    if (filteredPositions.length > 0) {
      $.each(filteredPositions, function(index, position) {
        positionsSelect.append('<option value="' + position.id + '">' + position.designation + '</option>');
      });
    }
  });
});
</script>