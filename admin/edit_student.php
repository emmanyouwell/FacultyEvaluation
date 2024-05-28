<?php
include 'db_connect.php';
$qry = $conn->query("SELECT student_list.*, credentials.email FROM student_list 
JOIN credentials ON student_list.credentials_id = credentials.credID
where student_list.id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
include 'new_student.php';
?>