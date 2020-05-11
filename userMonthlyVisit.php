
<?php

include '../DB/DBConnection.php';


$sql = "select A.month, ifnull(B.count,0) as count
        from month_table A
        left outer join (select DATE_FORMAT(date, '%m') as month, count(DATE_FORMAT(date, '%m')) as count  from userLog
                        where DATE_FORMAT(date, '%Y') = DATE_FORMAT(now(), '%Y')
                        group by month) B
        on A.month = B.month";

$result = $conn->query($sql);


$array_list = [];
while($row = $result->fetch_assoc()) {
    $array = [];
    $array['month'] = $row['month'];
    $array['count'] = $row['count'];
    $array_list[] = $array;
}

echo(json_encode($array_list));

$conn->close();


?>


