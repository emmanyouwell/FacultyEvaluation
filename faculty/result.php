<?php $faculty_id = $_SESSION['login_id'] ?>
<?php
function ordinal_suffix($num)
{
	$num = $num % 100; // protect against large numbers
	if ($num < 11 || $num > 13) {
		switch ($num % 10) {
			case 1:
				return $num . 'st';
			case 2:
				return $num . 'nd';
			case 3:
				return $num . 'rd';
		}
	}
	return $num . 'th';
}
$ay = $conn->query("SELECT * FROM academic_list ORDER BY year ASC");
$ay_arr = array();

while ($row = $ay->fetch_assoc()) {
	$ay_arr[] = $row['year'];
}
$aid = isset($_GET['aid']) ? $_GET['aid'] : '';
?>
<div class="col-lg-12">
<div class="row">
		<div class="col-md-3">
			<div class="row">
				<div class="col-md-12">
					<div class="callout callout-info">
						<p><b>Rating Legend</b></p>
						<ul class="rating-legend">
							<li><b>5</b> - Very Good</li>
							<li><b>4</b> - Good</li>
							<li><b>3</b> - Neutral</li>
							<li><b>2</b> - Bad</li>
							<li><b>1</b> - Very Bad</li>
						</ul>
					</div>
				</div>
			</div>

		</div>
		<div class="col-md-9">
			<div class="callout callout-info">
				<canvas id="myChart"></canvas>
				<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
				<script>
					var ctx = document.getElementById('myChart').getContext('2d');
					var years = <?php echo json_encode($ay_arr) ?>;
					var values = [];
					var myChart = new Chart(ctx, {
						type: 'line',
						data: {
							labels: years,
							datasets: [{
								label: 'Ratings',
								data: values,
								fill: false,
								borderColor: 'rgb(75, 192, 192)',
								tension: 0.1
							}]
						},
						options: {
							scales: {
								y: {
									min: 1, // minimum will be 1
									max: 5, // maximum will be 5
									ticks: {
										stepSize: 1,
										padding: 10,
									}
								},
								x: {
									ticks: {
										padding: 20
									}
								}
							},
						}
					});
				</script>
			</div>

		</div>
	</div>
	<div class="row">
		<div class="col-md-12 mb-1">
			<div class="d-flex justify-content-end w-100">

				<div class=" mx-2 col-md-1">
					<select name="" id="academic_year" class="form-control form-control-sm select2">
						<option value=""></option>
						<?php
						$academic_year = $conn->query("SELECT * FROM academic_list ORDER BY year ASC");
						$academic_year_arr = array();
						$aname = array();
						while ($row = $academic_year->fetch_assoc()):

							$academic_year_arr[] = $row;
							$aname[$row['id']] = ucwords($row['year']);
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($aid) && $aid == $row['id'] ? "selected" : "" ?>><?php echo ucwords($row['year']) ?>
							</option>
						<?php endwhile; ?>
					</select>
				</div>


			</div>
		</div>

	</div>
	<div class="row">
		<div class="col-md-3">
			<!-- <div class="row">
				<div class="col-md-12">
				<div class="callout callout-info">
					<div class="list-group" id="class-list">
						
					</div>
				</div>
				</div>
			</div> -->

			<div class="row">
				<div class="col-md-12">
					<div class="callout callout-info">
						<p><b>Criteria Rating Legend</b></p>
						<ul class="rating-legend">
							<li><b>5</b> - Very Good</li>
							<li><b>4</b> - Good</li>
							<li><b>3</b> - Neutral</li>
							<li><b>2</b> - Bad</li>
							<li><b>1</b> - Very Bad</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="callout callout-info">
						<p><b>Question Rating Legend</b></p>
						<ul class="rating-legend">
							<li><b>5</b> - Strongly Agree</li>
							<li><b>4</b> - Agree</li>
							<li><b>3</b> - Uncertain</li>
							<li><b>2</b> - Disagree</li>
							<li><b>1</b> - Strongly Disagree</li>
						</ul>
					</div>
				</div>
			</div>


		</div>
		<div class="col-md-9">
			<div class="callout callout-info" id="printable">
				<div>
					<h3 class="text-center">Evaluation Report</h3>
					<hr>
					<table width="100%">
						<tr>

							<td width="50%">
								<p><b>Academic Year: <span id="ay">
										</span></b></p>
							</td>
						</tr>
					</table>
					<p class=""><b>Total Student Evaluated: <span id="tse"></span></b></p>
				</div>

				<?php
				$q_arr = array();

				$criteria = $conn->query("SELECT * FROM criteria_list where id in (SELECT criteria_id FROM question_list where academic_id = {$_SESSION['academic']['id']} ) order by abs(order_by) asc ");

				?>
				<table class="table table-condensed wborder q-table">
					<thead>
						<tr class="bg-gradient-info">
							<th colspan="6"><b>Criteria & Questions</b></th>

							<th width="5%" class="text-center">Results</th>
						</tr>

					</thead>
					<?php while ($crow = $criteria->fetch_assoc()): ?>
						<tbody class="tr-sortable">
							<tr class="bg-gradient-white">
								<td colspan="6"><b><?php echo $crow['criteria'] ?></b></td>

								<th width="5%" class="text-center criteria_<?php echo $crow['id'] ?>"></th>
							</tr>
							<?php

							$questions = $conn->query("SELECT * FROM question_list where criteria_id = {$crow['id']} and academic_id = {$_SESSION['academic']['id']} order by abs(order_by) asc ");
							while ($row = $questions->fetch_assoc()):
								$q_arr[$row['id']] = $row;
								?>
								<tr class="bg-white">
									<td class="p-1" width="40%" colspan="6">
										<?php echo $row['question'] ?>
									</td>

									<td class="text-center">
										<span class="rate_result<?php echo '_' . $row['id']; ?>  rates"></span>

									</td>


								</tr>
							<?php endwhile; ?>
						</tbody>
					<?php endwhile; ?>
					<tfoot>
						<tr class="bg-gradient-info">
							<th colspan="6">Overall Remarks</th>
							<th id="overall-remarks" class="text-center"></th>
						</tr>
					</tfoot>
				</table>
				<div>
					<table class="table table-condensed wborder c-table">
						<thead>
							<th colspan="7" class="bg-gradient-info">Comments</th>
						</thead>
						<tbody>


							<tr>
								<td></td>
							</tr>


						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
</div>
</div>

<style>
	.list-group-item:hover {
		color: black !important;
		font-weight: 700 !important;
	}

	.rating-legend {
		list-style-type: none;
		/* Remove default bullets */

	}

	.rating-legend li::before {
		content: "★ ";
		/* Add a star before each list item */

	}
</style>
<noscript>
	<style>
		table {
			width: 100%;
			border-collapse: collapse;
		}

		table.wborder tr,
		table.wborder td,
		table.wborder th {
			border: 1px solid gray;
			padding: 3px
		}

		table.wborder thead tr {
			background: #6c757d linear-gradient(180deg, #828a91, #6c757d) repeat-x !important;
			color: #fff;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
		}

		.text-left {
			text-align: left;
		}
	</style>
</noscript>
<script>
	$(document).ready(function () {
		console.log("ready")

		$('#academic_year').change(function () {
			console.log("change")
			if ($(this).val() > 0)
				window.history.pushState({}, null, './index.php?page=result&aid=' + $(this).val());
			load_table();
			load_comments();
			load_report(<?php echo $faculty_id ?>, $(this).val());
			$('#ay').text($('#academic_year option[value="' + $(this).val() + '"]').text())
		})

	})
	function load_comments() {
		$acad_id = $('#academic_year').val() > 0 ? $('#academic_year').val() : '';
		$.ajax({
			url: 'ajax.php?action=get_comments',
			type: 'POST',
			data: { fid: <?php echo $faculty_id ?>, aid: $acad_id },
			error: function (err) {
				console.log(err)
				alert_toast("An error occured", 'error')
			},
			success: function (resp) {
				if (resp != ''){
					$(".c-table").empty();
				$(".c-table").html(resp);
				}
				
			}
		})
	}
	function load_table() {
		$acad_id = $('#academic_year').val() > 0 ? $('#academic_year').val() : '';
		$.ajax({
			url: "ajax.php?action=populate_table",
			type: 'POST',
			data: { aid: $acad_id },
			error: function (err) {
				console.log(err)
				alert_toast("An error occured", 'error')
			},
			success: function (resp) {
				if (resp) {
					$('.q-table').empty()
					$('.q-table').html(resp)
				}
			}
		})
	}


	$.ajax({
		url: "ajax.php?action=get_ratings",
		type: 'POST',
		data: { fid: <?php echo $faculty_id ?> },
		error: function (err) {
			console.log(err)
			alert_toast("An error occured", 'error')

		},
		success: function (resp) {
			try {
				console.log('Response:', resp);
				if (resp) {
					resp = JSON.parse(resp);
					var labels = Object.keys(resp);
					var data = Object.values(resp);

					// Assuming you have a Chart.js chart instance stored in a variable named 'chart'
					myChart.data.labels = labels;
					myChart.data.datasets[0].data = data;
					myChart.update();
				}
			} catch (err) {
				console.log('Error in success callback:', err);
			}
		}
	})
	function load_rating() {

		$.ajax({
			url: "ajax.php?action=get_ratings",
			type: 'POST',
			data: { fid: <?php echo $faculty_id?> },
			error: function (err) {
				console.log(err)
				alert_toast("An error occured", 'error')

			},
			success: function (resp) {
				try {
					console.log('Response:', resp);
					if (resp) {
						resp = JSON.parse(resp);
						var labels = Object.keys(resp);
						var data = Object.values(resp);

						// Assuming you have a Chart.js chart instance stored in a variable named 'chart'
						myChart.data.labels = labels;
						myChart.data.datasets[0].data = data;
						myChart.update();
					}
				} catch (err) {
					console.log('Error in success callback:', err);
				}
			}
		})
	}
	function load_class() {
		start_load()

		$.ajax({
			url: "ajax.php?action=get_class",
			method: 'POST',
			data: { fid: <?php echo $faculty_id ?> },
			error: function (err) {
				console.log(err)
				alert_toast("An error occured", 'error')
				end_load()
			},
			success: function (resp) {
				if (resp) {
					resp = JSON.parse(resp)
					if (Object.keys(resp).length <= 0) {
						$('#class-list').html('<a href="javascript:void(0)" class="list-group-item list-group-item-action disabled">No data to be display.</a>')
					} else {
						$('#class-list').html('')
						Object.keys(resp).map(k => {
							$('#class-list').append('<a href="javascript:void(0)" data-json=\'' + JSON.stringify(resp[k]) + '\' data-id="' + resp[k].id + '" class="list-group-item list-group-item-action show-result">' + resp[k].class + ' </a>')
						})

					}
				}
			},
			complete: function () {
				end_load()
				load_report(<?php echo $faculty_id ?>, $('#academic_year').val());

			}
		})
	}
	function anchor_func() {
		$('.show-result').click(function () {
			var vars = [], hash;
			var data = $(this).attr('data-json')
			data = JSON.parse(data)
			var _href = location.href.slice(window.location.href.indexOf('?') + 1).split('&');
			for (var i = 0; i < _href.length; i++) {
				hash = _href[i].split('=');
				vars[hash[0]] = hash[1];
			}
			window.history.pushState({}, null, './index.php?page=report&fid=' + vars.fid + '&rid=' + data.id);
			load_report(vars.fid);

			$('#classField').text(data.class)
			$('.show-result.active').removeClass('active')
			$(this).addClass('active')
		})
	}
	function load_report($faculty_id, $academic_year) {
		if ($('#preloader2').length <= 0)
			start_load()
		$acad_id = $academic_year > 0 ? $academic_year : '';
		$.ajax({
			url: 'ajax.php?action=get_report',
			method: "POST",
			data: { faculty_id: $faculty_id, academic_year: $acad_id },
			error: function (err) {
				console.log(err)
				alert_toast("An Error Occured.", "error");
				end_load()
			},
			success: function (resp) {
				console.log(resp)
				if (resp) {

					resp = JSON.parse(resp)
					if (Object.keys(resp).length <= 0) {
						$('.rates').text('')
						$('#tse').text('')
						$('#print-btn').hide()
					} else {
						$('#print-btn').show()
						$('#tse').text(resp.tse)
						$('.rates').text('-')
						if (resp.data) {
							var data = resp.data
							Object.keys(data).map(q => {
								console.log(data)
								console.log("resp", q)
								$('.rate_result_' + q).text((data[q]).toFixed(2))
							})
						}
						if (resp.criteria) {
							var criteria = resp.criteria
							var total = 0;
							var length = Object.keys(criteria).length;
							Object.keys(criteria).map(c => {
								console.log(criteria)
								total += parseFloat(criteria[c]);
								$('.criteria_' + c).text((criteria[c]).toFixed(2))
							});
						}

						var average = (total / length).toFixed(0);
						var remark = '';

						if (average == 5) {
							remark = 'Very Good';
						} else if (average == 4) {
							remark = 'Good';
						} else if (average == 3) {
							remark = 'Neutral';
						} else if (average == 2) {
							remark = 'Bad';
						} else if (average == 1) {
							remark = 'Very Bad';
						}
						$('#overall-remarks').text(remark)

					}

				}
			},
			complete: function () {
				end_load()
			}
		})
	}

</script>