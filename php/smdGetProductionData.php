<?php
    $server='localhost';
    $db='smd';
    $user='root';
    $password='123456';
    $conn=new mysqli($server,$user,$password,$db);
    if($conn->connect_error){
        die("连接服务器失败,请检查网络连接!");
    }

    $currDate=date("Y-m-d");
    $currHour=date('H');
    $queryResult='';
    if($currHour>=8 && $currHour<20){
        $sql='select model,productionNum from smdProductionPlan where time=\''.$currDate.'\';';
        $result=$conn->query($sql);
        while($row=$result->fetch_assoc()){
            $model=$row['model'];
            $planNum=$row['productionNum'];
            $queryResult=$queryResult.$model.' '.$planNum.':';
            $_hour=8;
            while($_hour>=8&&$_hour<20){
                $productNum=0;
                $_sql='select productionNum from smdProductionData where model=\''.$model.'\' and date=\''.$currDate.'\' and hour=\''.$_hour.'\';';
                $_result=$conn->query($_sql);
                if($_row=$_result->fetch_assoc())
                {
                    $productNum=$_row['productionNum'];
                }
                else
                {
                    $productNum=0;
                }
                $queryResult=$queryResult.$productNum.',';
                $_hour=$_hour+1;
            }
            $queryResult=$queryResult."\n";
            }
        echo $queryResult;
    }
    else
    {
        if($currHour<9)
        {
            $_date=date('Y-m-d',strtotime("-1 day"));
        }
        else{
            $_date=$currDate;
        }
        $sql='select model,productionNum,time from smdProductionPlan where time=\''.$_date.'\';';
        $result=$conn->query($sql);
        while($row=$result->fetch_assoc()){
            $model=$row['model'];
            $planNum=$row['productionNum'];
            $planTime=$row['time'];
            $queryResult=$queryResult.$model.' '.$planNum.':';
            $_hour=20;
            while($_hour<32){
                $t_hour=$_hour%24;
                $productNum=0;

                if($currHour>8&&$t_hour<8)
                {
                    $_date=date('Y-m-d',strtotime($planTime)+3600*24);
                }
                elseif ($currHour<8&&$t_hour>=20) {
                    $_date=date('Y-m-d',strtotime($planTime)-3600*24);
                }
                else{
                    $_date=date('Y-m-d');
                }

                $_sql='select productionNum from smdProductionData where model=\''.$model.'\' and date=\''.$_date.'\' and hour=\''.$t_hour.'\';';
                $_result=$conn->query($_sql);
                if($_row=$_result->fetch_assoc())
                {
                    $productNum=$_row['productionNum'];
                }
                else
                {
                    $productNum=0;
                }
                $queryResult=$queryResult.$productNum.',';
                $_hour=$_hour+1;
            }
            $queryResult=$queryResult."\n";
            }
            echo $queryResult;
        }
?>