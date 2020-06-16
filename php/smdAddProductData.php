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
            .input{
                width:400px;
                font-size: 35px;
                color:chocolate;
                text-align: center;
            }

            .title{
                font-size: 45px;
                position: relative;
                color: teal;
            }

            .pushbutton{
                font-size: 40px;
                background-color: cornflowerblue;
                color:blue;
                border: cyan;
                margin: 20px;
                transition-duration: 0.2s;
            }

            h1{
                text-align: center;
                font-size: 70px;
                color:blue;
            }

            a.hyperLink{
                font-size:30px;
                color:dark;
                background-color:gray;
                text-decoration:none;
                position:relative;
            }

            div.hyperLink{

            }

        </style>
        <script>
            function clearInputData()
            {
                document.getElementById("_num").value='';
                document.getElementById("modelSelect").focus();
            }

            function submitInputData()
            {
                var num=document.getElementById("_num").value;
                if(num===""||num==0){
                    alert("输入的生产数量不能为空!");
                    document.getElementById("_num").focus();
                    return;
                }
                if(!checkNumInput())
                {
                    alert("请输入正确的生产数据!");
                    document.getElementById("_num").value="";
                    document.getElementById("_num").focus();
                    return;
                }
                var date=document.getElementById('_date').value;
                if(date==''){
                    alert('请选择日期!');
                    return;
                }
                var hour=document.getElementById('_hour').value;
                if(hour==''){
                    alert('请选择小时!');
                    return;
                }

                var model=document.getElementById('modelSelect').value;
                var sendData='model='+model+'&num='+num.toString()+'&date='+date+'&hour='+hour.toString();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.open("POST","./smdSaveProductData.php",true);
                xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xmlhttp.send(sendData);
                xmlhttp.onreadystatechange=function(){
                if(xmlhttp.readyState==4&&xmlhttp.status==200){
                    var result=xmlhttp.responseText;
                    alert(result);
                }
                }

                clearInputData();
            }


            function checkNumInput(){
                var value = document.getElementById("_num").value;
			        var r = /^\+?[1-9][0-9]*$/;
			        if (!r.test(value)) {
                        return false;
		        }
                return true;
            }
        </script>
    <head>
        <meta charset="utf-8">
        <title>输入生产数据</title>
    </head>
    <body background="../img/modelS.jpg" style="background-attachment:unset; line-height:15px">
    <br><br>
        <h1>PI SMD生产数据统计系统</h1>
        
        <div class='hyperLink'>
        <a href='smdDisplayProductionData.php' class='hyperLink'>输入数据查询</a>
        <a href='../html/smdAddProductionPlan.html' class='hyperLink'>生产计划插入</a>
        <a href='../html/smdDisplay.html' class='hyperLink'>生产数据展示</a>
        </div>
        <br>
        <div align="center" style="position: relative;">
            <p class="title">型号</p>
            <select name="model" id="modelSelect" class="input">
        <?php
            $currDate=date('Y-m-d');
            $prevDate=date('Y-m-d',strtotime("-1 day"));
            $getModelSql='select model from smdProductionPlan where time in(\''.$currDate.'\',\''.$prevDate.'\');';
			$result=$conn->query($getModelSql);
            while($row=$result->fetch_assoc())
            {
				echo "<option value='$row[model]'>$row[model]</option>";
			}
		?>
            </select>
            <br><br>
            <p class="title">日期</p>
            <input type='date' id='_date' name='date' class='input'>
            <br><br>
            <p class="title">时间</p>
            <select name="hour" id="_hour" class="input">
        <?php
            $_hour=0;
            while($_hour<24)
            {
                echo "<option value='$_hour'>$_hour</option>";
                $_hour+=1;
			}
		?>
            </select>
            <br><br>
            <p class="title" id='p_num'>数量</p>
            <input id="_num" name="num" class="input" type="text"><br><br>
            <input type="button" value="确认" onclick="submitInputData()" class="pushbutton">
            <input type="reset" value='清除' class="pushbutton" onclick='clearInputData()'>
        <script>
            var time = new Date();
            /////set the default hour//////
            var currHour=time.getHours();
            if(currHour==0){
                currHour=23;
            }
            else{
                currHour=currHour-1;
            }
            document.getElementById('_hour').value=currHour;

            /////set the default date//////
            var date;
            if(currHour==23)
            {
                var prevDate=new Date(time.getTime()-24*3600*1000);
                var day = ("0" + prevDate.getDate()).slice(-2);
                var month = ("0" + (prevDate.getMonth() + 1)).slice(-2);
                date = prevDate.getFullYear() + "-" + (month) + "-" + (day);
            }else{
                var day = ("0" + time.getDate()).slice(-2);
                var month = ("0" + (time.getMonth() + 1)).slice(-2);
                date = time.getFullYear() + "-" + (month) + "-" + (day);
            }
            document.getElementById('_date').value=date;
        </script>
    </div>
    </body>
