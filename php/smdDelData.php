<?php
header("content-type:text/html;charset=utf-8");         //设置编码
$server="localhost";
$user="root";
$password="123456";
$db='smd';
$mysqlconn=new mysqli($server,$user,$password,$db);
$model=$_POST["model"];
$date=$_POST["date"];
$hour=$_POST["hour"];
$time=$_POST['time'];
$num=$_POST['num'];


if($mysqlconn->connect_error)
{
    die("删除数据失败!请检查网络!");
}
else
{
    $sql='delete from smdProductionData where model=\''.$model.'\' and date=\''.$date.'\' and hour=\''.$hour.'\' and insertTime=\''.$time.'\' and productionNum=\''.$num.'\'';

    // $sql='delete from smdProductionData where hour=\''.$hour.'\'';
    // $sql='truncate smdProductionData;';
    // die($sql);
    $mysqlconn->query($sql);
    echo "<script>alert('数据删除成功！');</script>";
    echo "<script>history.back();</script>";
}
?>