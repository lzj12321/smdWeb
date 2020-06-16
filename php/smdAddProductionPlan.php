<?php
    header("content-type:text/html;charset=utf-8");         //设置编码
    $server="localhost";
    $user="root";
    $password="123456";
    $db='smd';
    $conn=new mysqli($server,$user,$password,$db);

    if($conn->connect_error)
{
    echo "写入数据失败!请通知管理员检查!";
}
else
{

    $model=$_POST["model"];
    $date=$_POST["time"];
    $num=$_POST['num'];
    $model=str_replace(' ','',$model);

    $sql1='select 1 from smdProductionPlan where model=\''.$model.'\' and time=\''.$date.'\';';
    $result=$conn->query($sql1);
    if($result->fetch_assoc())
    {
        $sql='update smdProductionPlan set productionNum='.(string)$num.' where model=\''.$model.'\' and time=\''.$date.'\';';
        $conn->query($sql);
        echo '已有该型号生产计划!';
    }
    else
    {
        $sql='insert into smdProductionPlan(model,time,productionNum) values(\''.$model.'\',\''.$date.'\','.$num.');';
        $conn->query($sql);
        echo '添加生产计划成功!';
    }
}
?>