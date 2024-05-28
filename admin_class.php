<?php
session_start();
ini_set('display_errors', 1);
require_once 'vendor/autoload.php';
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{
		extract($_POST);
		$type = array("", "users", "faculty_list", "student_list");
		$type2 = array("", "admin", "faculty", "student");
		if ($type[$login] == 'users')
			$qry = $this->db->query("SELECT * FROM {$type[$login]} where email = '" . $email . "' and password = '" . md5($password) . "'  ");
		else
			$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM {$type[$login]} JOIN credentials ON {$type[$login]}.credentials_id = credentials.credID where credentials.email = '" . $email . "' and credentials.password = '" . md5($password) . "'  ");
		// $qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM {$type[$login]} JOIN credentials ON {$type[$login]}.credentials_id = credentials.id where credentials.email = '" . $email . "' and credentials.password = '" . md5($password) . "'  ");

		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			$_SESSION['login_type'] = $login;
			$_SESSION['login_view_folder'] = $type2[$login] . '/';
			$academic = $this->db->query("SELECT * FROM academic_list where is_default = 1 ");
			if ($academic->num_rows > 0) {
				foreach ($academic->fetch_array() as $k => $v) {
					if (!is_numeric($k))
						$_SESSION['academic'][$k] = $v;
				}
			}
			return 1;
		} else {
			return 2;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function login2()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '" . $student_code . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['rs_' . $key] = $value;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function save_user()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set $data");
		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function signup()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass')) && !is_numeric($k)) {
				if ($k == 'password') {
					if (empty($v))
						continue;
					$v = md5($v);

				}
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set $data");

		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			if (empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if (!in_array($key, array('id', 'cpass', 'password')) && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			$_SESSION['login_id'] = $id;
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
				$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}

	function update_user()
	{
		extract($_POST);
		$data = "";
		$type = array("", "users", "faculty_list", "student_list");
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'table', 'password')) && !is_numeric($k)) {

				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM {$type[$_SESSION['login_type']]} where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (!empty($password))
			$data .= " ,password=md5('$password') ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO {$type[$_SESSION['login_type']]} set $data");
		} else {
			echo "UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id";
			$save = $this->db->query("UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id");
		}

		if ($save) {
			foreach ($_POST as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
				$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}
	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_system_settings()
	{
		extract($_POST);
		$data = '';
		foreach ($_POST as $k => $v) {
			if (!is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if ($_FILES['cover']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'], '../assets/uploads/' . $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set $data where id =" . $chk->fetch_array()['id']);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if ($save) {
			foreach ($_POST as $k => $v) {
				if (!is_numeric($k)) {
					$_SESSION['system'][$k] = $v;
				}
			}
			if ($_FILES['cover']['tmp_name'] != '') {
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image()
	{
		extract($_FILES['file']);
		if (!empty($tmp_name)) {
			$fname = strtotime(date("Y-m-d H:i")) . "_" . (str_replace(" ", "-", $name));
			$move = move_uploaded_file($tmp_name, 'assets/uploads/' . $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path = explode('/', $_SERVER['PHP_SELF']);
			$currentPath = '/' . $path[1];
			if ($move) {
				return $protocol . '://' . $hostName . $currentPath . '/assets/uploads/' . $fname;
			}
		}
	}

	function save_class()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM class_list where (" . str_replace(",", 'and', $data) . ") and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		if (isset($user_ids)) {
			$data .= ", user_ids='" . implode(',', $user_ids) . "' ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO class_list set $data");
		} else {
			$save = $this->db->query("UPDATE class_list set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}
	function delete_class()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM class_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_academic()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM academic_list where (" . str_replace(",", 'and', $data) . ") and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		$hasDefault = $this->db->query("SELECT * FROM academic_list where is_default = 1")->num_rows;
		if ($hasDefault == 0) {
			$data .= " , is_default = 1 ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO academic_list set $data");
		} else {
			$save = $this->db->query("UPDATE academic_list set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}
	function delete_academic()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM academic_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function make_default()
	{
		extract($_POST);
		$update = $this->db->query("UPDATE academic_list set is_default = 0");
		$update1 = $this->db->query("UPDATE academic_list set is_default = 1 where id = $id");
		$qry = $this->db->query("SELECT * FROM academic_list where id = $id")->fetch_array();
		if ($update && $update1) {
			foreach ($qry as $k => $v) {
				if (!is_numeric($k))
					$_SESSION['academic'][$k] = $v;
			}

			return 1;
		}
	}
	function save_criteria()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM criteria_list where (" . str_replace(",", 'and', $data) . ") and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}

		if (empty($id)) {
			$lastOrder = $this->db->query("SELECT * FROM criteria_list order by abs(order_by) desc limit 1");
			$lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
			$data .= ", order_by='$lastOrder' ";
			$save = $this->db->query("INSERT INTO criteria_list set $data");
		} else {
			$save = $this->db->query("UPDATE criteria_list set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}
	function delete_criteria()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM criteria_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_criteria_order()
	{
		extract($_POST);
		$data = "";
		foreach ($criteria_id as $k => $v) {
			$update[] = $this->db->query("UPDATE criteria_list set order_by = $k where id = $v");
		}
		if (isset($update) && count($update)) {
			return 1;
		}
	}

	function save_question()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}

		if (empty($id)) {
			$lastOrder = $this->db->query("SELECT * FROM question_list where academic_id = $academic_id order by abs(order_by) desc limit 1");
			$lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
			$data .= ", order_by='$lastOrder' ";
			$save = $this->db->query("INSERT INTO question_list set $data");
		} else {
			$save = $this->db->query("UPDATE question_list set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}
	function delete_question()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM question_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_question_order()
	{
		extract($_POST);
		$data = "";
		foreach ($qid as $k => $v) {
			$update[] = $this->db->query("UPDATE question_list set order_by = $k where id = $v");
		}
		if (isset($update) && count($update)) {
			return 1;
		}
	}
	function save_faculty()
	{
		extract($_POST);
		$data = "";
		$credentials_data = "";
		$check = $this->db->query("SELECT * FROM credentials where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		// Prepare data for credentials table
		if (!empty($email)) {
			$credentials_data .= " email='$email' ";
		}
		if (!empty($password)) {
			$credentials_data .= ", password=md5('$password') ";
		}
		// Insert into credentials table
		$save_credentials = $this->db->query("INSERT INTO credentials set $credentials_data");
		// Check if credentials were saved successfully
		if ($save_credentials) {
			// Get the last inserted ID
			$credentials_id = $this->db->insert_id;

			// Prepare data for faculty_list table
			foreach ($_POST as $k => $v) {
				if (!in_array($k, array('id', 'cpass', 'password', 'email')) && !is_numeric($k)) {
					if (empty($data)) {
						$data .= " $k='$v' ";
					} else {
						$data .= ", $k='$v' ";
					}
				}
			}
			$data .= ", credentials_id='$credentials_id' ";
		}




		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO faculty_list set $data");
		} else {
			$save = $this->db->query("UPDATE faculty_list set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function delete_faculty()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM faculty_list where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_student()
	{
		extract($_POST);
		$data = "";
		$credentials_data = "";
		$check = $this->db->query("SELECT * FROM credentials where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		// Prepare data for credentials table
		if (!empty($email)) {
			$credentials_data .= " email='$email' ";
		}
		if (!empty($password)) {
			$credentials_data .= ", password=md5('$password') ";
		}
		// Insert into credentials table
		$save_credentials = $this->db->query("INSERT INTO credentials set $credentials_data");
		// Check if credentials were saved successfully
		if ($save_credentials) {
			// Get the last inserted ID
			$credentials_id = $this->db->insert_id;

			// Prepare data for faculty_list table
			foreach ($_POST as $k => $v) {
				if (!in_array($k, array('id', 'cpass', 'password', 'email')) && !is_numeric($k)) {
					if (empty($data)) {
						$data .= " $k='$v' ";
					} else {
						$data .= ", $k='$v' ";
					}
				}
			}
			$data .= ", credentials_id='$credentials_id' ";
		}

		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO student_list set $data");
		} else {
			$save = $this->db->query("UPDATE student_list set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function delete_student()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM student_list where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_task()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k)) {
				if ($k == 'description')
					$v = htmlentities(str_replace("'", "&#x2019;", $v));
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO task_list set $data");
		} else {
			$save = $this->db->query("UPDATE task_list set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}
	function delete_task()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_progress()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k)) {
				if ($k == 'progress')
					$v = htmlentities(str_replace("'", "&#x2019;", $v));
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!isset($is_complete))
			$data .= ", is_complete=0 ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO task_progress set $data");
		} else {
			$save = $this->db->query("UPDATE task_progress set $data where id = $id");
		}
		if ($save) {
			if (!isset($is_complete))
				$this->db->query("UPDATE task_list set status = 1 where id = $task_id ");
			else
				$this->db->query("UPDATE task_list set status = 2 where id = $task_id ");
			return 1;
		}
	}
	function delete_progress()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_progress where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_restriction()
	{
		try {
			extract($_POST);
			$filtered = implode(",", array_filter($rid));
			if (!empty($filtered))
				$this->db->query("DELETE FROM restriction_list where id not in ($filtered) and academic_id = $academic_id");
			else
				$this->db->query("DELETE FROM restriction_list where  academic_id = $academic_id");

			foreach ($rid as $k => $v) {
				error_log($k);
				error_log($faculty_id[$k]);
				$data = " academic_id = $academic_id ";
				$data .= ", faculty_id = {$faculty_id[$k]} ";
				$data .= ", class_id = {$class_id[$k]} ";

				if (empty($v)) {
					$save[] = $this->db->query("INSERT INTO restriction_list set $data ");
				} else {
					$save[] = $this->db->query("UPDATE restriction_list set $data where id = $v ");
				}
			}
			return 1;
		} catch (Exception $e) {
			error_log($e->getMessage());
			return 0; 
		}
	}
	
	function save_evaluation()
	{
		extract($_POST);
		$data = " student_id = {$_SESSION['login_id']} ";
		$data .= ", academic_id = $academic_id ";

		$data .= ", class_id = $class_id ";
		$data .= ", restriction_id = $restriction_id ";
		$data .= ", faculty_id = $faculty_id ";
		$save = $this->db->query("INSERT INTO evaluation_list set $data");
		if ($save) {
			$eid = $this->db->insert_id;
			foreach ($qid as $k => $v) {
				$data = " evaluation_id = $eid ";
				$data .= ", question_id = $v ";
				$data .= ", rate = {$rate[$v]} ";

				$ins[] = $this->db->query("INSERT INTO evaluation_answers set $data ");
			}
			// Insert the comment into the comment table
			$comment = $this->db->real_escape_string($comment);

			$sentimeter = new \PHPInsight\Sentiment();

			$scores = $sentimeter->score($comment);
			$class = $sentimeter->categorise($comment);
			$type;
			if ($class == "pos"){
				$type="POSITIVE";
			}
			if ($class == "neg"){
				$type="NEGATIVE";
			}
			

			$this->db->query("INSERT INTO comment (evaluation_id, comment, type) VALUES ($eid, '$comment', '$type')");
			if (isset($ins))
				return 1;
		}
	}
	function get_class()
	{
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT c.id,concat(c.level,' - ',c.section) as class FROM restriction_list r inner join class_list c on c.id = r.class_id where r.faculty_id = {$fid} and academic_id = {$_SESSION['academic']['id']} ");
		while ($row = $get->fetch_assoc()) {
			$data[] = $row;
		}
		return json_encode($data);

	}
	
	function get_report()
	{
		extract($_POST);
		// $academic_year = $academic_year != '' ? $academic_year : $_SESSION['academic']['id'];
		$data = array();
		$get = $this->db->query("SELECT * FROM evaluation_answers where evaluation_id in (SELECT evaluation_id FROM evaluation_list where academic_id = $academic_year and faculty_id = $faculty_id ) ");
		if ($get == false) {
			throw new Exception("SQL Error: " . $this->db->error);
		}
		$answered = $this->db->query("SELECT * FROM evaluation_list where academic_id = $academic_year and faculty_id = $faculty_id");
		$criteria_score = $this->db->query("SELECT criteria_id, AVG(question_average) as criteria_average FROM ( SELECT ql.criteria_id, AVG(ea.rate) as question_average FROM evaluation_answers ea INNER JOIN evaluation_list el ON ea.evaluation_id = el.evaluation_id INNER JOIN question_list ql ON ea.question_id = ql.id WHERE el.academic_id = $academic_year AND el.faculty_id = $faculty_id GROUP BY ql.criteria_id, ql.id ) as subquery GROUP BY criteria_id;");
		$rate = array();
		$total = array();
		while ($row = $get->fetch_assoc()) {
			if (!isset($rate[$row['question_id']]))
				$rate[$row['question_id']] = 0;
			if (!isset($total[$row['question_id']]))
				$total[$row['question_id']] = 0;
			$rate[$row['question_id']] += $row['rate'];
			$total[$row['question_id']] += 1;
		}
		$ta = $answered->num_rows;
		$r = array();
		foreach ($rate as $qk => $qv) {
			$r[$qk] = $rate[$qk] / $total[$qk];
		}
		while ($row = $criteria_score->fetch_assoc()) {
			$data['criteria'][$row['criteria_id']] = floatval($row['criteria_average']);
		}
		$data['tse'] = $ta;
		$data['data'] = $r;

		return json_encode($data);
	}

	function get_ratings()
	{

		if (isset($_POST['fid'])) {
			$fid = $_POST['fid'];
			$data = array();
			$query = $this->db->query("SELECT ac.year, e.faculty_id, AVG(a.rate) as average_rating FROM evaluation_list e JOIN evaluation_answers a ON e.evaluation_id = a.evaluation_id JOIN academic_list ac ON e.academic_id = ac.id WHERE e.faculty_id = $fid GROUP BY ac.year, e.faculty_id;");

			if ($query === false) {
				error_log("SQL Error: " . $this->db->error);
				return json_encode(array('error' => 'Database error'));
			}

			while ($row = $query->fetch_assoc()) {
				$data[$row["year"]] = $row["average_rating"];
				error_log("Data: " . $data[$row["year"]]);
			}

			if (empty($data)) {
				error_log("No data returned for fid: " . $fid);
				return json_encode(array('error' => 'No data returned'));
			}

			return json_encode($data);
		} else {
			error_log("No fid provided");
			return json_encode(array('error' => 'No fid provided'));
		}
	}
	function populate_table()
	{
		extract($_POST);

		$acad_id = $aid != '' ? $aid : $_SESSION['academic']['id'];
		$criteria = $this->db->query("SELECT * FROM criteria_list where id in (SELECT criteria_id FROM question_list where academic_id = {$acad_id} ) order by abs(order_by) asc ");
		if ($criteria === false) {
			error_log("SQL Error: " . $this->db->error);
			return json_encode(array('error' => 'Database error'));
		} else {
			ob_start();
			echo '<table class="table table-condensed wborder q-table">';
			echo '<thead><tr class="bg-gradient-info"><th colspan="6"><b>Criteria & Questions</b></th><th width="5%" class="text-center">Results</th></tr></thead>';
			while ($crow = $criteria->fetch_assoc()):
				echo '<tbody class="tr-sortable">';
				echo '<tr class="bg-gradient-white">';
				echo '<td colspan="6"><b>' . $crow['criteria'] . '</b></td>';
				echo '<th width="5%" class="text-center criteria_' . $crow['id'] . '"></th>';
				echo '</tr>';
				$acad_id = $aid != '' ? $aid : $_SESSION['academic']['id'];
				$questions = $this->db->query("SELECT * FROM question_list where criteria_id = {$crow['id']} and academic_id = {$acad_id} order by abs(order_by) asc ");
				while ($row = $questions->fetch_assoc()):
					$q_arr[$row['id']] = $row;
					echo '<tr class="bg-white">';
					echo '<td class="p-1" width="40%" colspan="6">' . $row['question'] . '</td>';
					echo '<td class="text-center">';
					echo '<span class="rate_result_' . $row['id'] . ' rates"></span>';
					echo '</td>';
					echo '</tr>';
				endwhile;
				echo '</tbody>';
			endwhile;
			echo '<tfoot>';
			echo '<tr class="bg-gradient-info">';
			echo '<th colspan="6">Overall Remarks</th>';
			echo '<th id="overall-remarks" class="text-center"></th>';
			echo '</tr>';
			echo '</tfoot>';
			echo '</table>';
			$html = ob_get_clean();
			return $html;
		}

	}
	function get_positive_comments()
	{
		extract($_POST);
		if ($aid != '') {
			$comment = $this->db->query("SELECT comment.comment, comment.type FROM comment JOIN evaluation_list ON comment.evaluation_id = evaluation_list.evaluation_id WHERE evaluation_list.faculty_id = $fid AND evaluation_list.academic_id = $aid;");
			ob_start();
			echo '<table class="table table-condensed wborder p-table"><thead><th colspan="7" class="bg-gradient-info">Positive Comments</th></thead><tbody>';

			while ($row = $comment->fetch_assoc()):
				if ($row['type'] == 'POSITIVE'){
					echo '<tr>';
					echo '<td>' . $row['comment'] . '</td>';
					echo '</tr>';
				}
			endwhile;

			echo '</tbody></table>';

			
			$html = ob_get_clean();
			return $html;
		} else {
			return "";
		}

	}
	function get_negative_comments()
	{
		extract($_POST);
		if ($aid != '') {
			$comment = $this->db->query("SELECT comment.comment, comment.type FROM comment JOIN evaluation_list ON comment.evaluation_id = evaluation_list.evaluation_id WHERE evaluation_list.faculty_id = $fid AND evaluation_list.academic_id = $aid;");
			ob_start();
			echo '<table class="table table-condensed wborder n-table"><thead><th colspan="7" class="bg-gradient-info">Negative Comments</th></thead><tbody>';

			while ($row = $comment->fetch_assoc()):
				if ($row['type'] == 'NEGATIVE'){
					echo '<tr>';
					echo '<td>' . $row['comment'] . '</td>';
					echo '</tr>';
				}
			endwhile;

			echo '</tbody></table>';

			
			$html = ob_get_clean();
			return $html;
		} else {
			return "";
		}

	}


}