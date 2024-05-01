<?php
include '../db_connect.php';

?>
<div class="container-fluid">
	<form action="" id="manage-restriction">
		<div class="row">
			<div class="col-md-4 border-right">
				<input type="hidden" name="academic_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
				<div id="msg" class="form-group"></div>
				<div class="form-group">
					<label for="" class="control-label">Faculty</label>
					<select name="" id="faculty_id" class="form-control form-control-sm select2">
						<option value=""></option>
						<?php
						$faculty = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM faculty_list order by concat(firstname,' ',lastname) asc");
						$f_arr = array();
						while ($row = $faculty->fetch_assoc()):
							$f_arr[$row['id']] = $row;
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($faculty_id) && $faculty_id == $row['id'] ? "selected" : "" ?>><?php echo ucwords($row['name']) ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="" class="control-label">Class</label>
					<select name="" id="class_id" class="form-control form-control-sm select2">
						<option value=""></option>
						<?php
						$classes = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as class FROM class_list");
						$c_arr = array();
						while ($row = $classes->fetch_assoc()):
							$c_arr[$row['id']] = $row;
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($class_id) && $class_id == $row['id'] ? "selected" : "" ?>><?php echo $row['class'] ?></option>
						<?php endwhile; ?>
					</select>
				</div>

				<div class="form-group">
					<div class="d-flex w-100 justify-content-center">
						<button class="btn btn-sm btn-flat btn-primary bg-gradient-primary" id="add_to_list"
							type="button">Add to List</button>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<table class="table table-condensed" id="r-list">
					<thead>
						<tr>
							<th>Faculty</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$restriction = $conn->query("SELECT * FROM restriction_list where academic_id = {$_GET['id']} order by id asc");
						$grouped = array();
						while ($row = $restriction->fetch_assoc()) {
							$faculty_id = $row['faculty_id'];
							$class_id = $row['class_id'];
							$rid = $row['id'];

							if (!isset($grouped[$faculty_id])) {
								$grouped[$faculty_id] = array('class_id' => array(), 'rid' => array());
							}
							$grouped[$faculty_id]['class_id'][] = $class_id;
							$grouped[$faculty_id]['rid'][] = $rid;
						}

						foreach ($grouped as $faculty_id => $items):
							?>
							<tr>
								<td>
									<div class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">
											<b><?php echo isset($f_arr[$faculty_id]) ? $f_arr[$faculty_id]['name'] : '' ?></b>
										</a>
										<div class="dropdown-menu">
											<?php
											foreach ($items['class_id'] as $index => $class_id) {
												$rid = $items['rid'][$index];
												echo '<a class="dropdown-item d-flex justify-content-between align-items-center" href="#" data-class-id="' . $class_id . '">' . (isset($c_arr[$class_id]) ? $c_arr[$class_id]['class'] : '') . '<i class="fa fa-trash delete-icon text-danger"></i><input type="hidden" name="class_id[]" value="' . $class_id . '"><input type="hidden" name="rid[]" value="' . $rid . '"><input type="hidden" name="faculty_id[]" value="' . $faculty_id . '"> </a>';
											}
											?>
										</div>
									</div>
								</td>

								<td class="text-center">
									<button class="btn btn-sm btn-outline-danger" onclick="$(this).closest('tr').remove()"
										type="button"><i class="fa fa-trash"></i></button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>
<script>
	$(document).ready(function () {
		$('.select2').select2({
			placeholder: "Please select here",
			width: "100%"
		});
		$('#manage-restriction').submit(function (e) {
			console.log($(this).serialize());
			e.preventDefault();
			start_load()
			$('#msg').html('')
			$.ajax({
				url: 'ajax.php?action=save_restriction',
				method: 'POST',
				data: $(this).serialize(),
				success: function (resp) {
					if (resp == 1) {
						alert_toast("Data successfully saved.", "success");
						setTimeout(function () {
							location.reload()
						}, 1750)
					} else if (resp == 2) {
						$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Class already exist.</div>')
						end_load()
					}
				}
			})
		})


		$('#add_to_list').click(function () {
			start_load()
			var frm = $('#manage-restriction')
			var cid = frm.find('#class_id').val()
			var fid = frm.find('#faculty_id').val()

			
			var isDuplicate = false;
			var facultyExists = false;
			$('#r-list tbody tr').each(function () {
				var rowFid = $(this).find('input[name="faculty_id[]"]').val();
				if (rowFid == fid) {
					facultyExists = true;
					
					var dropdownMenu = $(this).find('.dropdown-menu');
					dropdownMenu.find('.dropdown-item').each(function () {
						console.log($(this).data('classId'))
						if ($(this).data('classId') == cid) {
							isDuplicate = true;
							return false; 
						}
					});
					if (!isDuplicate) {
						
						dropdownMenu.append('<a class="dropdown-item d-flex justify-content-between align-items-center" href="#" data-class-id="' + cid + '">' + $('#class_id option:selected').text() + ' <i class="fa fa-trash delete-icon text-danger"></i><input type="hidden" name="class_id[]" value="' + cid + '"><input type="hidden" name="rid[]" value=""><input type="hidden" name="faculty_id[]" value="' + fid + '"></a>');
						return false;
					}
				}
			});

			if (isDuplicate) {
				alert_toast("Faculty and class already exist in the list.", "warning")
				end_load()
				return;
			}

			if (!facultyExists) {
				
				var tr = $("<tr></tr>")
				tr.append('<td><div class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>' + $('#faculty_id option:selected').text() + '</b></a><div class="dropdown-menu"><a class="dropdown-item d-flex justify-content-between align-items-center" href="#" data-class-id="' + cid + '">' + $('#class_id option:selected').text() + ' <i class="fa fa-trash delete-icon text-danger"></i><input type="hidden" name="faculty_id[]" value="' + fid + '"><input type="hidden" name="class_id[]" value="' + cid + '"><input type="hidden" name="rid[]" value=""></a></div></div></td>')
				tr.append('<td class="text-center"><button class="btn btn-sm btn-outline-danger" onclick="$(this).closest(\'tr\').remove()" type="button"><i class="fa fa-trash"></i></button></td>')
				$('#r-list tbody').append(tr)
			}

			frm.find('#class_id').val('').trigger('change')
			frm.find('#faculty_id').val('').trigger('change')

			end_load()
		})

		$(document).on('click', '.delete-icon', function (e) {
			e.preventDefault();
			var dropdownMenu = $(this).closest('.dropdown-menu');
			$(this).closest('.dropdown-item').remove();

			
			if (dropdownMenu.children().length === 0) {
				dropdownMenu.closest('tr').remove();
			}
		});

		$('.delete-icon').click(function (e) {
			e.preventDefault();
			var dropdownMenu = $(this).closest('.dropdown-menu');
			$(this).closest('.dropdown-item').remove();

			
			if (dropdownMenu.children().length === 0) {
				dropdownMenu.closest('tr').remove();
			}
		});
	})

</script>