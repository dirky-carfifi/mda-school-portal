<?php

    include_once "../config/dbconnect.php";
    
    $p_id=$_POST['record'];
    $query="DELETE FROM event_list where id='$p_id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"Event Item Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
?>