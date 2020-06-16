<?php
$server='localhost';
$db='smd';
$user='root';
$password='123456';
$conn=new mysqli($server,$user,$password,$db);
if($conn->connect_error){
	die("连接服务器失败,请检查网络连接!");
}
?>

<style>
    h1{
        color:blue;
        font-size: 65px;
        text-align: center;
    }

    .dataTable{
        font-size: 25px;
        text-align: center;
        color:blue;
        border-collapse: collapse;
    }

    #operateButton{
        background-color: #f44336;
        font-size: 20px;
        border: 2px solid red;
    }

    #confirmButton{
        color:blue;
        /* background-color: green; */
        font-size: 23px;
        
    }

    .dateInput{
        width:200px;
        font-size: 25px;
        color:chocolate;
        text-align: center;
        }
    
    a.hyperLink{
        font-size:30px;
        color:dark;
        background-color:gray;
        text-decoration:none;
        position:relative;
    }
    
</style>
<script>
// <script src='../javascript/jquery-3.5.1.js'>

   function delTableRow(obj){
        var rowNode=obj.parentNode.parentNode;
        var model,date,hour,num,time;
        for(var i=0;i<rowNode.cells.length;++i){
            var cellContent=rowNode.cells[i].innerText;
            if(i==1)
            {
                model=cellContent;
            }
            if(i==2){
                date=cellContent;
            }
            if(i==3)
            {
                hour=cellContent;
            }
            if(i==4)
            {
                num=cellContent;
            }
            if(i==5)
            {
                time=cellContent;
            }
        }
        var alterMessage='确认删除该组数据？\n'+'model:'+model+'\ndate:'+date+'\nhour:'+hour+'\nnum:'+num;
        var isDel=window.confirm(alterMessage);
        if(isDel){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.open("POST","smdDelData.php",true);
            xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            var sendData="model="+model+"&hour="+hour+"&date="+date+"&num="+num+"&time="+time;
            xmlhttp.send(sendData);
            rowNode.remove();
        }
   }
</script>

<html>
    <head>
        <meta charset="utf8">
        <title>生产数据列表</title>
        <!-- <script src='../javascript/jquery-3.5.1.js'></script> -->
    </head>
    <h1>
        SMD生产数据查询
    </h1>
    <body background='../img/modelS.jpg'>
        <div class='hyperLink'>
        <a href='smd.php' class='hyperLink'>生产数据插入</a>
        <a href='../html/smdAddProductionPlan.html' class='hyperLink'>生产计划插入</a>
        <a href='../html/smdDisplay.html' class='hyperLink'>生产数据展示</a>
        </div>
         
        <div align='center' style='font-size:30px'>
        <input type='date' id='_date' class='dateInput'>
        <input type='button' id='confirmButton' class='button' value='确认'>
        </div>
        <br>
    <div>
        <table class='dataTable' align="center" border="5px" cellpadding='10' id='_table'>
            <thead>
                <tr>
                    <th>序号</th>
                    <th>型号</th>
                    <th>日期</th>
                    <th>时间(小时)</th>
                    <th>数量</th>
                    <th>插入时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $currDate=date('Y-m-d');
                $prevDate=date('Y-m-d',strtotime("-1 day"));
                $getDataSql='select model,date,hour,productionNum,insertTime from smdProductionData where insertTime like \''.$currDate.'%\' or insertTime like \''.$prevDate.'%\';';
                $result=$conn->query($getDataSql);
                $sn=1;
                while($row=$result->fetch_assoc())
                {
                    echo "<tr id='_row'>";
                    echo "<th>$sn</th>";
                    echo "<th name='model'>$row[model]</th>";
                    echo "<th name='date'>$row[date]</th>";
                    echo "<th name='hour'>$row[hour]</th>";
                    echo "<th name='productionNum'>$row[productionNum]</th>";
                    echo "<th name='insertTime'>$row[insertTime]</th>";
                    echo "<th><input type='button' id='operateButton' value='删除' class='operateButton' onclick=delTableRow(this)></th>";
                    echo "</tr>";
                    $sn+=1;
			    }
		    ?>
            </tbody>
        </table>
    </div>
    <script>
        /////set the default date//////
        var time=new Date();
        var day = ("0" + time.getDate()).slice(-2);
        var month = ("0" + (time.getMonth() + 1)).slice(-2);
        var date = time.getFullYear() + "-" + (month) + "-" + (day);
        document.getElementById('_date').value=date;
    </script>

    </body>
</html>