<?php

    $con=mysqli_connect("localhost","jakitran_ojol","jakitrans95#@!","jakitran_ojol");

    $sql="SELECT * FROM 'driver'";
    $result=mysqli_query($con,$sql);

    $data=array();
    while($row=mysqli_fetch_assoc($result)){
    $response=array();
    while($row=mysqli_fetch_array($result)){
    array_push($response,array('job'=>$row['job']));

}
}
echo json_encode($response);
?>
