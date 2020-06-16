<?php
header("content-type:text/html;charset=utf-8");         //设置编码
$server="localhost";
$user="root";
$password="123456";
$db='smd';
$conn=new mysqli($server,$user,$password,$db);
$model=$_POST["model"];
$date=$_POST["date"];
$hour=$_POST["hour"];
$num=$_POST["num"];

if($conn->connect_error)
{
    echo "写入数据失败!请通知管理员检查!";
}
else
{
    $sql1='select 1 from smdProductionData where model=\''.$model.'\' and date=\''.$date.'\' and hour=\''.$hour.'\';';
    $result=$conn->query($sql1);
    if($result->fetch_assoc())
    {
        $sql='update smdProductionData set productionNum='.(string)$num.' where model=\''.$model.'\' and date=\''.$date.'\' and hour=\''.$hour.'\';';
        $conn->query($sql);
    }
    else{
        $sql='insert into smdProductionData(model,productionNum,date,hour) values(\''.$model.'\','.(string)$num.',\''.$date.'\',\''.$hour.'\');';
        $conn->query($sql);
    }
    echo "写入数据成功！";
}
?>
