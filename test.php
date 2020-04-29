<?php
    include '../DB/DBConnection.php';

    $select_query = "SELECT * FROM freeBoard";
    $result = mysqli_query($conn, $select_query);
    while ($row = mysqli_fetch_assoc($result)){
        echo '<br>idx : '.$row['idx'].', title : '.$row['title'];
    }
    $conn->close();
?>

