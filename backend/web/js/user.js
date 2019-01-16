
    //全反选
   $('#all').click(function () {
       var checks = $('input:checkbox[name="ids"]');
       if($(this).is(':checked')){
           checks.prop('checked',true);
       }else{
           checks.prop('checked',false);
       }
   });
   //批量设置客服服务分组
   $('#cid').click(function () {
       var idArr = [];
       var gid = $('select[name="group"]').val();
       var checks = $('input:checkbox[name="ids"]:checked');
       $.each(checks,function () {
           idArr.push($(this).val());
       });
       if(idArr.length == 0){
           layer.msg('请选择需要分组的客户');
           return ;
       }
       $.get('/backend/web/index.php?r=admin/setgroup',{'ids':idArr,'gid':gid},function (data) {
           if(data.errorCode == 0){
               setTimeout('location.reload();',3000);
               layer.msg(data.errorMsg, {icon: 6});
           }else{
               layer.msg(data.errorMsg, {icon: 5});
           }
       },'json');
   });

   //设置客户vip
    function setvip(id) {

        $.get('/backend/web/index.php?r=user/setvip',{'id':id},function (data) {
            if(data.errorCode == 0){
                setTimeout('location.reload();',3000);
                layer.msg(data.errorMsg, {icon: 6});
            }else{
                layer.msg(data.errorMsg, {icon: 5});
            }
        },'json');
    }

    //取消客户vip
    function cancelvip(id) {
        $.get('/backend/web/index.php?r=user/cancelvip',{'id':id},function (data) {
            if(data.errorCode == 0){
                setTimeout(function () { location.reload(); },3000);
                layer.msg(data.errorMsg, {icon: 6});
            }else{
                layer.msg(data.errorMsg, {icon: 5});
            }
        },'json');
    }

    //批量设置客服服务分组
    $('#cid-user').click(function () {
        var idArr = [];
        var gid = $('select[name="group"]').val();
        var checks = $('input:checkbox[name="ids"]:checked');
        $.each(checks,function () {
            idArr.push($(this).val());
        });
        if(idArr.length == 0){
            layer.msg('请选择需要分组的客户');
            return ;
        }
        $.get('/backend/web/index.php?r=user/setgroup',{'ids':idArr,'gid':gid},function (data) {
            if(data.errorCode == 0){
                setTimeout('location.reload();',3000);
                layer.msg(data.errorMsg, {icon: 6});
            }else{
                layer.msg(data.errorMsg, {icon: 5});
            }
        },'json');
    });

    //客户寄件确认收货
    $('.receipt').click(function () {
        var id = $(this).attr('key');
        $.get('/backend/web/index.php?r=user-send/receipt',{'id':id},function (data) {
            if(data.errorCode == 0){
                setTimeout('location.reload();',3000);
                layer.msg(data.errorMsg, {icon: 6});
            }else{
                layer.msg(data.errorMsg, {icon: 5});
            }
        },'json');
    });

    /*
     * 运单设置问题件 ajax判断运单是否是问题件 若已经添加到问题件表中 则提示前往问题件列表处理
     */
    $('.setprob').click(function () {
        var id = $(this).attr('key');
        $.get('/backend/web/index.php?r=waybill/check-is-prob',{'id':id},function (data) {
           if(data.errorCode==0){
               //跳转到问题件设置页面
               location.href = '/backend/web/index.php?r=waybill-problem/create&id='+id;
           } else{
               layer.msg(data.errorMsg);
           }
        },'json');
    });

    //删除运单 伪删除 waybillstatus表中recycle字段改变
    $('.delete').click(function () {
        var id = $(this).attr('key');
        layer.confirm('您确定删除该运单？',{
            btn:['确定','取消']
        },function () {
            $.get('/backend/web/index.php?r=waybill-status/delete',{'id':id},function (data) {
                if(data.errorCode==0){
                    layer.msg(data.errorMsg);
                    setTimeout('location.reload();',3000);
                }else{
                    layer.msg(data.errorMsg);
                }
            },'json');
        },function () {
            
        });
    });

    //新建异常件工单中据订单号获取客户名称和调查渠道并填入
    $('#onum').blur(function () {
        var onum = $(this).val();
        $.get('/backend/web/index.php?r=survey-list/getname',{'onum':onum},function (data) {
            $('#getname').val(data.memberName);
            if(data.errorCode==0){
                $('#channel').val(data.dc_channel);
            }
        },'json');
    });

    //异常工单预归档，点击致歉，隐藏理赔责任划分
    $('.apologise').click(function () {
        $('.pc_step1').hide();
        $('.office_pc').hide();
        $('.company_pc').hide();
    });

    //异常工单预归档，点击赔偿，显示理赔责任划分
    $('.compensate').click(function () {
        $('.pc_step1').show();
    });

    //异常件工单选择官方被点击
    $('.office').click(function () {
        if($(this).is(':checked')){
            $('.office_pc').show();
        }else{
            $('.office_pc').hide();
        }
    });

    //异常件工单选择公司
    $('.company').click(function(){
        if($(this).is(':checked')){
            $('.company_pc').show();
        }else{
            $('.company_pc').hide();
        }
    });

    //异常件理赔信息提交
    function compensate() {
        //点击赔偿后，官方和公司必须选择一个或两个
        if($('.compensate').is(':checked')){
            var pc_type = $('input:checkbox[name="pc_type[]"]:checked');
            var pcArr = [];
            $.each(pc_type,function () {
                pcArr.push($(this).val());
            });
            if(pcArr.length==0){
                layer.msg('请至少选择一个理赔方');
                return false;
            }
            //若选择了官方理赔,则必须要填写相关信息
            if(pcArr.indexOf('1')!=-1){
                var office_name = $(':input[name="office_name"]').val();
                var office_money = $(':input[name="office_money"]').val();
                if(!office_name || !office_money){
                    layer.msg('请完整填写理赔信息');
                    return false;
                }
                if(office_money <= 0){
                    layer.msg('理赔金额不得小于0');
                    return false;
                }
            }
            //若选择了公司理赔,则必须要填写相关信息
            if(pcArr.indexOf('2')!=-1){
                var departments = $('#departments').select2('data');
                var company_money = $('.company_money').val();
                var staff = $('#staff').select2('data');

                if(departments.length==0){
                    layer.msg('请选择公司部门');
                    return false;
                }
                if(!company_money || company_money <= 0){
                    layer.msg('理赔金额不得小于0');
                    return false;
                }
                if(!staff){
                    layer.msg('请选择员工');
                    return false;
                }
                //赔偿三种情况    1部门 1员工；1部门 2 员工；多部门 多员工
                if(departments.length==1 && staff.length>2){
                    layer.msg('1部门不能超过2个员工');
                    return false;
                }
            }
        }
        return true;
    }

    //打印调查理赔工单
    $('.print').click(function () {
        window.print();
    });

    //运单查询下拉框展开
    function stretch(obj) {
        var key = $(obj).attr('key');
        if(key == 'close'){
            //then open
            $(obj).parent().parent().siblings().show();
            //change the icon and text
            $(obj).attr('key','open');
            $(obj).html('<span class="glyphicon glyphicon-chevron-up">收起</span>');
        }else{
            //then close
            $(obj).parent().parent().siblings().hide();
            //change the icon and text
            $(obj).attr('key','close');
            $(obj).html('<span class="glyphicon glyphicon-chevron-down">展开</span>');
        }
    }


