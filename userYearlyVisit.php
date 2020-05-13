
<?php

include '../DB/DBConnection.php';


$sql = "select DATE_FORMAT(date, '%Y') as year, count(DATE_FORMAT(date, '%Y')) as count  from userLog
        group by year";

$result = $conn->query($sql);


$array_list = [];
while($row = $result->fetch_assoc()) {
    $array = [];
    $array['year'] = $row['year'];
    $array['count'] = $row['count'];
    $array_list[] = $array;
}

echo(json_encode($array_list));

$conn->close();


?>


