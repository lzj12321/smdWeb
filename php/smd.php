<?php
//路由
$action = $_GET['action'];
switch($action){
    case 'init_data_list':
    init_data_list();
    break;
    case 'add_row':
    add_row();
    break;
    case 'del_row':
    del_row();
    break;
    case 'edit_row':
    edit_row();
    break;
}

global $colNum;

//初始化数据
function init_data_list(){
    $currHour=date('H');
    $_dataTable='';
    if($currHour>=8&&$currHour<20){
        $_dataTable='et_data';
    }
    else{
        $_dataTable='ft_data';
    }
    $sql = 'SELECT * FROM '.$_dataTable. ';';//下面函数的参数
    $query = query_sql($sql);//自定义函数未定义形参
    while ($row = $query->fetch_assoc()){
        $data[] = $row;
    }
    echo json_encode($data);exit();
}

//新增行记录
function add_row(){
    $currHour=date('H');
    $sql1='select 1 from et_data where c_a=\''.$_POST['col_0'].'\';';
    $sql2='select 1 from ft_data where c_a=\''.$_POST['col_0'].'\';';
    // echo $sql2;
    $result1=query_sql($sql1);
    $result2=query_sql($sql2);
    if($result1->fetch_assoc()||$result2->fetch_assoc()){
        echo '添加的型号 \''.$_POST['col_0'].'\' 重复!';
        exit();
    }

    $colNum = 14;
    $addSql='insert into smdProductData(';
    for($i=0;$i<$colNum;$i++)
    {
        $addSql=$addSql.'data_'.$i.',';
    }


    $addSql.='timeFlag,date) values(';


    $sql11 = 'INSERT INTO et_data ( `c_a`,`c_b`,`c_c`,`c_d`,`c_e`,`c_f`,`c_g`,`c_h`,`c_i`,`c_j`,`c_k`,`c_l`,`c_m`,`c_n` ) VALUES ( ';
    $sql22 = 'INSERT INTO ft_data ( `c_a`,`c_b`,`c_c`,`c_d`,`c_e`,`c_f`,`c_g`,`c_h`,`c_i`,`c_j`,`c_k`,`c_l`,`c_m`,`c_n` ) VALUES ( ';
    for($i = 0;$i<$colNum;$i++){
        if($i==0||$i==1){
            $sql11 .= '\'' . $_POST['col_' . $i] . '\',';
            $sql22 .= '\'' . $_POST['col_' . $i] . '\',';


            $addSql.= '\'' . $_POST['col_' . $i] . '\',';


            continue;
        }


        $addSql .= '\'' . $_POST['col_' . $i] . '\',';

        if($currHour>=8&&$currHour<20){
            $sql11 .= '\'' . $_POST['col_' . $i] . '\',';
            $sql22 .= '0,';
        }
        else{
            $sql22 .= '\'' . $_POST['col_' . $i] . '\',';
            $sql11 .= '0,'; 
        }
    }

    if($currHour>=8&&$currHour<20){
        $addSql .='\'day\',';
    }
    else{
        $addSql .='\'night\',';
    }
    $date='';
    if($currHour<8){
        $date=date('Y-m-d',strtotime("-1 day"));
    }
    else{
        $date=date('Y-m-d');
    }

    $addSql .='\''.$date.'\');';

    query_sql($addSql);


    $sql11 = trim($sql11,',');
    $sql11 .= ')';
    $sql22 = trim($sql22,',');
    $sql22 .= ')';
    $lastInsertId = "SELECT LAST_INSERT_ID() AS LD";
    if($res11 = query_sql($sql11,$lastInsertId)){
        $d = $res11->fetch_assoc();
        if($currHour>=8&&$currHour<20){
        echo $d['LD'];}
    }else{
        echo "db error...";exit();
    }

    if($res22 = query_sql($sql22,$lastInsertId)){
        $d = $res22->fetch_assoc();
        if($currHour<8||$currHour>=20){
        echo $d['LD'];}
        exit();
    }else{
        echo "db error...";
        exit();
    }
}

//删除行记录
function del_row(){
    $currHour=date('H');
    $_dataTable='';
    $dataid = $_POST['dataid'];
    $sql1 = 'DELETE FROM et_data where `id` = ' . $dataid;
    $sql2 = 'DELETE FROM ft_data where `id` = ' . $dataid;

    $date='';
    if($currHour<8){
        $date=date('Y-m-d',strtotime("-1 day"));
    }
    else{
        $date=date('Y-m-d');
    }



    
    $deleteDataSql="update smdProductData set dataFlag='deleted' where date='".$date.'\' and data_0=\''.$_POST['modelId'].'\';';
    query_sql($deleteDataSql);
    // echo $deleteDataSql;
    if(query_sql($sql1)&&query_sql($sql2)){
        echo "ok";exit();
    }else{
        echo "db error...";exit();
    }
}

//编辑行记录
function edit_row(){
    $currHour=date('H');
    $_dataTable='';
    $currData='';
    $timeFlag='';
    if($currHour>8&&$currHour<20){
        $_dataTable='et_data';
        $timeFlag='day';
    }
    else{
        $_dataTable='ft_data';
        $timeFlag='night';
    }
    if($currHour<8){
        $date=date('Y-m-d',strtotime("-1 day"));
    }
    else{
        $date=date('Y-m-d');
    }
    $colNum = 14;
    $sql = 'UPDATE '.$_dataTable.' SET ';
    $id = $_POST['id'];
    unset($_POST['id']);
    for($i=0;$i<$colNum;$i++){
        $sql .= '`c_'.chr(97 + $i) . '` =\''.$_POST['col_' . $i] . '\',';
    }
    $sql = trim($sql,',');
    $sql .= ' WHERE `id` = ' . $id;


    $updateSql='update smdProductData set ';
    for($i=0;$i<$colNum;$i++){
        $updateSql .= 'data_'.$i.'=\''.$_POST['col_'.$i].'\',';
    }
    $updateSql=trim($updateSql,',');
    
    $updateSql .= ' where data_0=\''.$_POST['col_0'].'\' and date=\''.$date.'\' and timeFlag=\''.$timeFlag.'\';';

    query_sql($updateSql);



    // echo $sql;
    if(query_sql($sql)){
        echo "ok";exit();
    }else{
        echo "db error...";exit();
    }
}

//数据库查询
function query_sql(){
    $mysqli = new mysqli('127.0.0.1','root','123456','smd');
    $sqls = func_get_args();//获取函数的所有参数
    foreach ($sqls as $key => $value){
        $query = $mysqli->query($value);
    }
    $mysqli->close();
    return $query;
}