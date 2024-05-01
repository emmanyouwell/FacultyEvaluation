<?php
include 'db_connect.php';
$qry = $conn->query("SELECT faculty_list.*, credentials.email FROM faculty_list 
JOIN credentials ON faculty_list.credentials_id = credentials.id 
where faculty_list.id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
    $$k = $v;
}
include 'new_faculty.php';
?>