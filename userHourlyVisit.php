
<?php

include '../DB/DBConnection.php';


$sql = "select A.hour, ifnull(B.count,0) as count
        from hour_table A
        left outer join (select DATE_FORMAT(date, '%H') as hour, count(DATE_FORMAT(date, '%H')) as count  from userLog
                        where DATE_FORMAT(date, '%Y %m %d') = DATE_FORMAT(now(), '%Y %m %d')
                        group by hour) B
        on A.hour = B.hour
        order by hour asc";

$result = $conn->query($sql);


$array_list = [];
while($row = $result->fetch_assoc()) {
    $array = [];
    $array['hour'] = $row['hour'];
    $array['count'] = $row['count'];
    $array_list[] = $array;
}

echo(json_encode($array_list));

$conn->close();


?>


