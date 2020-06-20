var colNum=15;
var maxColNumPerPage=14;

function create_row(data_item){
    var row_obj = $("<tr></tr>");
    row_obj.attr('class','dataRow');
    var dayProductData=0;
    for(var k in data_item){
        // alert(k);
        if("id" != k){//去除返回字段中的id
            var col_td = $("<td id='_td'></td>");
            col_td.html(data_item[k]);//给col_td写入内容
            if(k=='c_a'||k=='c_b'||k=='col_0'||k=='col_1'){
                col_td.addClass('productTd');
            }
            else if((parseInt(data_item[k])<parseInt(data_item['c_b']))||(parseInt(data_item[k])<parseInt(data_item['col_1'])))
            {
                dayProductData+=parseInt(data_item[k]);
                col_td.addClass('unreachProduct');
                // alert(k);
            }
            else{
                dayProductData+=parseInt(data_item[k]);
                col_td.addClass('reachProduct');
            }
            row_obj.append(col_td);//追加DOM
        }
    }
    //添加总产量列
    var productData_td=$('<td></td>');
    productData_td.addClass('dayProductData');
    productData_td.text(dayProductData);
    row_obj.append(productData_td);

    //自定义按钮
    var delButton = $('<a class="optLink" href="javascript:;">删除&nbsp;</a>');//删除按钮
    delButton.attr("dataid",data_item['id']);//给按钮添加dataid属性
    delButton.click(delHandler);//给按钮添加点击事件
    var editButton = $('<a class="optLink" href="javascript:;">编辑</a>');//编辑按钮
    editButton.attr("dataid",data_item['id']);
    editButton.click(editHandler);
    //追加操作列
    var opt_td = $('<td></td>');
    opt_td.addClass('testClass');
    if($('#_button').val()=='退出编辑模式'){
        opt_td.css('display','block');
    }
    opt_td.append(delButton);
    opt_td.append(editButton);
    row_obj.append(opt_td);
    return row_obj;
}

//操作列的删除事件
function delHandler(){
    var isDel=window.prompt("输入'123456'确认删除操作!");
    if(isDel!='123456')
    {
        alert('输入错误！')
        return;
    }

    var data_id = $(this).attr("dataid");//获取删除的dataid的值，$(this)指点击的这个button
    var model=$(this).parent().parent().children(':first').text();
        // alert(model);
    var meButton = $(this);//按钮这个变量
    $.post("../php/smd.php?action=del_row",{dataid:data_id,modelId:model},function(res){
        if(res == "ok"){
            $(meButton).parent().parent().remove();//删除行记录
        }else{
            alert('操作列的删除事件!');
            alert(res);
        }
    });
}


 //编辑行记录
 function editHandler(){
    var data_id = $(this).attr("dataid");
    var meRow = $(this).parent().parent();//没有事件
    var editRow = $("<tr></tr>");
    for(var i=0;i<colNum;i++){
        if(i==0||i==colNum-1){
            var editTd = $("<td><input type='text' readonly class='txtField'/></td>");
        }else{
            var editTd = $("<td><input type='text' class='txtField'/></td>");
        }
        
        var v = meRow.find('td:eq(' + i +')').html();
        editTd.find('input').val(v);
        editRow.append(editTd);
    }

    //操作列
    var opt_td = $("<td></td>");
    var saveButton = $("<a href='javascript:;' class='optLink'>保存&nbsp;</a>");
    saveButton.click(function(){
        // var isSave=window.confirm('保存修改记录?');
        // if(!isSave)
        // {
        //     return;
        // }
        var currentRow = $(this).parent().parent();
        var input_fields = currentRow.find("input");
        var post_fields = {};
        for(var i=0,j=input_fields.length-1;i<j;i++){
            
            if(input_fields[i].value==''){
                post_fields['col_'+i]=0;
            }
            else if(i!=0){
                post_fields['col_' + i] = parseInt(input_fields[i].value);
            }
            else{
                post_fields['col_' + i] =input_fields[i].value;
            }
            if(i!=0&&!checkNumIsValid(post_fields['col_' + i]))
            {
                alert('输入的数据非法! '+post_fields['col_'+i]);
                return;
            }
        }
        post_fields['id'] = data_id;
        $.post("../php/smd.php?action=edit_row",post_fields,function(res){
            if(res == 'ok'){
                var newUpdateRow = create_row(post_fields);
                currentRow.replaceWith(newUpdateRow);
            }else{
                // alert('编辑行记录!');
                alert(res);
            }
        });
    });

    
        var cancleButton = $("<a href='javascript:;' class='optLink'>取消</a>")
        cancleButton.click(function(){
            var currentRow = $(this).parent().parent();//当前行
            meRow.find('a:eq(0)').click(delHandler);//新替换的行没有点击事件，需要重新赋予点击事件
            meRow.find('a:eq(1)').click(editHandler);
            currentRow.replaceWith(meRow);//meRow为以前的行
        });

        opt_td.append(saveButton);
        opt_td.append(cancleButton);
        editRow.append(opt_td);
        meRow.replaceWith(editRow);
    }


    function checkNumIsValid(num){
        num = String(num).replace(/\s+/g,"");
		var r = /^\+?[0-9][0-9]*$/;
		if (!r.test(num)){
            return false;
		}
        return true;
    }


$(function(){
    var g_table = $("table.data");//定义全局变量，定位到html的表格
    var init_data_url = "../php/smd.php?action=init_data_list";
    $(document).keydown(function(event){
        if(event.keyCode==13){
            var x=document.getElementsByClassName('testClass');
            for(var i=0;i<=maxColNumPerPage&&i<x.length;++i){
                x.item(i).style.display='block';
                }
            $(".pageButton").css('display','block');
            $("#pageTitle").css('display','block');
            document.getElementById('_button').value='退出编辑模式';
        }
    })

    $.get(init_data_url,function(data){
        var row_items = $.parseJSON(data);//json数据转换成json数组对象
        //js循环遍历
        var totalPage=Math.ceil(row_items.length/maxColNumPerPage);
        $("#pageTitle").text('1/'+totalPage);
        for(var i = 0 , j = row_items.length ; i < j&&i<maxColNumPerPage ; i++){
            var data_dom = create_row(row_items[i]);
            g_table.append(data_dom);
        }
    });


    $("#clearBtn").click(function(){
        var isClear=window.prompt("输入'clear'确认清空操作!");
        if(isClear!='clear')
        {
            alert('输入错误！')
            return;
        }
        var dataRows=$('tr');
        for(var i=1;i<dataRows.length;i++){
            var model=dataRows[i].cells[0].innerHTML;
            var dataId=dataRows[i].cells[15].children[0].getAttribute('dataid');
            $.post("../php/smd.php?action=del_row",{dataid:dataId,modelId:model},function(res){
                // $.post("../php/smd.php?action=del_row",{dataid:data_id},function(res){
                    if(res == "ok"){
                        location.reload();
                    }else{
                        alert('操作列的删除事件!');
                        alert(res);
                    }
                })
            }})

    
    $("#nextPageButton").click(function(){
        var currPage= $('#pageTitle').text().split("/")[0];
        $.get(init_data_url,function(smdData){
        var row_items = $.parseJSON(smdData);//json数据转换成json数组对象
        $("tr").remove(".dataRow");
        var startId=0;
        var totalPage=Math.ceil(row_items.length/maxColNumPerPage);
        if((currPage*maxColNumPerPage)<row_items.length)
        {
                $("#pageTitle").text(parseInt(currPage)+1+'/'+totalPage);
                startId=currPage*maxColNumPerPage;
        }
        else{
            startId=0;
            $("#pageTitle").text('1/'+totalPage);
        }
        //js循环遍历
        for(var i = startId , j = row_items.length,k=0 ; i < j&&k<maxColNumPerPage ; i++,k++){
            var data_dom = create_row(row_items[i]);
            g_table.append(data_dom);
        }
    });
    })

    $("#prevPageButton").click(function(){
        var currPage= $('#pageTitle').text().split("/")[0];
        $.get(init_data_url,function(smdData){
        var row_items = $.parseJSON(smdData);//json数据转换成json数组对象
        $("tr").remove(".dataRow");
        var startId=0;
        var totalPage=Math.ceil(row_items.length/maxColNumPerPage);
        if(currPage==1)
        {
                $("#pageTitle").text(totalPage+'/'+totalPage);
                startId=(totalPage-1)*maxColNumPerPage;
                // alert(startId);
        }
        else{
            startId=(currPage-2)*maxColNumPerPage;
            $("#pageTitle").text((currPage-1)+'/'+totalPage);
        }
        //js循环遍历
        for(var i = startId , j = row_items.length,k=0 ; i < j&&k<maxColNumPerPage ; i++,k++){
            var data_dom = create_row(row_items[i]);
            g_table.append(data_dom);
        }
    });
    })

    //添加行记录
    $("#addBtn").click(function(){
        var addRow = $("<tr></tr>");
        //八个文本框
        for(var i=0;i<colNum;i++){
            var col_td;
            if(i==colNum-1||i==0){
                col_td = $("<td><input type='text' class='txtField'/></td>");
            }
            else{
                col_td = $("<td><input type='number' class='txtField'/></td>");
            }
            addRow.append(col_td);
        }
        //操作列
        var col_opt = $("<td></td>");
        var confirmBtn = $("<a href='javascript:;' class='optLink'>确认&nbsp;</a>");
        confirmBtn.click(function(){//确认操作
            var currentRow = $(this).parent().parent();
            var input_fields = currentRow.find("input");

            if(input_fields[0].value==''||input_fields[1].value==''||input_fields[1].value==0)//判断输入是否合法
            {
                alert('型号或小时计划产量错误，请重新输入！');
                return;
            }
            input_fields[1].value=parseInt(input_fields[1].value);//清除产量中前面的0
            
            var post_fields = {};//发送数据对象
            for(var i=0,j=input_fields.length-1;i<j;i++)
            {
                post_fields['col_' + i] = input_fields[i].value;
                if(input_fields[i].value==''){
                    post_fields['col_'+i]=0;
                }

                if(i!=0&&!checkNumIsValid(post_fields['col_' + i]))
                {
                    alert('输入的数据非法! '+post_fields['col_'+i]);
                    return;
                }
            }
            $.post("../php/smd.php?action=add_row",post_fields,function(res){
                if(0 < res){
                    post_fields['id'] = res;
                    var postAddRow = create_row(post_fields);
                    currentRow.replaceWith(postAddRow);
                }else{
                    // alert('添加行记录!');
                    alert(res);
                }
            });
        });

        var cancelBtn = $("<a href='javascript:;' class='optLink'>取消</a>");
        cancelBtn.click(function(){//删除操作，取消直接删除行
            $(this).parent().parent().remove();
        });

        col_opt.append(confirmBtn);
        col_opt.append(cancelBtn);
        addRow.append(col_opt);

        g_table.append(addRow);
    });


    function checkNumIsValid(num){
            num = String(num).replace(/\s+/g,"");
		    var r = /^\+?[0-9][0-9]*$/;
			if (!r.test(num)) {
                return false;
		    }
            return true;
    }


    //打印输出调试
    function debug(res){
        console.log(res);
    }
});

