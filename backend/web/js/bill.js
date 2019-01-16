
//选择保价则出现保价金额
$('input[name="valueInsured"]').change(function () {
    var isInsured = $(this).val();
    if(isInsured == 1){
        $('input[name="insuranceAmount"]').attr('disabled',false);
    }else{
        $('input[name="insuranceAmount"]').attr('disabled',true);
    }
});

var goods = {};
var i = 0;
//预录单创建---物品添加
$('.goods-add').click(function () {
    i++;
    var nameCn = $('input[name="nameCn"]').val();
    var nameEn = $('input[name="nameEn"]').val();
    var hsCode = $('input[name="hsCode"]').val();
    var price = $('input[name="price"]').val();
    var quantity = $('input[name="quantity"]').val();
    var weight = $('input[name="weight"]').val();
    var html = '';
    var good = {
        'nameCn' : '',
        'nameEn' : '',
        'hsCode' : '',
        'price' : '',
        'quantity': '',
        'weight' : '',
    };

    if(!nameCn || !nameEn || !hsCode){
        layer.msg('*为必填项');
        return;
    }
    if(price && price<=0){
        layer.msg('请填写正确价格数值');
        return;
    }
    if(quantity && quantity<=0){
        layer.msg('请填写正确数量数值');
        return;
    }
    if(weight && weight<=0){
        layer.msg('请填写正确重量数值');
        return;
    }

    html += '<tr id="'+ i +'">';
    html += '<td><input type="text" name="nameCn" value="'+ nameCn +'" disabled="true" class="form-control input-group-sm"></td>';
    html += '<td><input type="text" name="nameEn" value="'+ nameEn +'" disabled="true" class="form-control input-group-sm"></td>';
    html += '<td><input type="text" name="hsCode" value="'+ hsCode +'" disabled="true" class="form-control input-group-sm"></td>';
    html += '<td><input type="text" name="price" value="'+ price +'" disabled="true" class="form-control input-group-sm"></td>';
    html += '<td><input type="text" name="quantity" value="'+ quantity +'" disabled="true" class="form-control input-group-sm"></td>';
    html += '<td><input type="text" name="weight" value="'+ weight +'" disabled="true" class="form-control input-group-sm"></td>';
    html += '<td><input type="button" class="btn btn-danger goods-remove" value="删除" onclick="goodsRemove($(this))"/></td>';
    html += '</tr>';
    $('#goods').after(html);
    $('#goods :input[type="text"]').val('');
    $('#goods :input[type="number"]').val('');

    //写入goods;
    good.waybillId = '';
    good.nameCn = nameCn;
    good.nameEn = nameEn;
    good.hsCode = hsCode;
    good.price = price;
    good.quantity = quantity;
    good.weight = weight;

    goods[i] = good;

});

//预录单创建-----物品删除
function goodsRemove(obj) {
    var id = $(obj).parent().parent().attr('id');
    delete goods[id];
    $(obj).parent().parent().remove();
}
//通过邮编自动填写省州城市
$('input[name="consigneeZip"]').blur(function () {
    var consigneeZip = $(this).val();
    $.post('/backend/web/index.php?r=auto-address/auto-fill',{'consigneeZip':consigneeZip},function (data) {
        if(data.address){
            $('input[name="consigneeState"]').val(data.address.province);
            $('input[name="consigneeCity"]').val(data.address.city);
        }
    },'json');
});
//通过国家、重量、用户ID、仓库ID、快慢线、父渠道ID 获取子渠道成本由低到高排序
$('select[name="channelParentId"]').change(function () {
    var countryId = $('select[name="countryId"]').val();
    var weightInput = $('input[name="weightInput"]').val();
    var memberName = $('select[name="memberName"]').val();
    var storageId = $('select[name="storageId"]').val();
    var speed = $('select[name="speed"]').val();
    var parentId = $('select[name="channelParentId"]').val();

    if(!countryId || !weightInput || !memberName || !storageId || !speed || !parentId){
        layer.msg('星号必填才能计算出子渠道成本');return;
    }

    var data = {
        'countryId':countryId,
        'weightInput':weightInput,
        'memberId':memberName,
        'storageId':storageId,
        'speed':speed,
        'parentId':parentId,
    };
    //通过接口计算子渠道成本并返回
    $.post('/backend/web/index.php?r=channel/get-child-channel',{data:data},function (data) {
        var childChannel = data.res;
        if(data.errorCode == 0){
            var html = '';
            for (var i=0 ;i<childChannel.length; i++){
                html += '<option value="' + childChannel[i].id + '">' + childChannel[i].name + '</option>';
            }
            $('select[name="channelChildId"]').html(html);
        }else{
            layer.msg(data.errorMsg);
        }

    },'json');
});
/**
 * 1.结合出单步骤，若要出单，必须先生成waybill,waybill_status,waybill_actioner,waybill_consignee,waybill_goods等记录才可提交出单接口
 * 2.出单接口会审核申报价值和物品价值是否相等，所以在该步骤，必须先判断
 * 3.点击出单时，生成记录后，访问接口，
 * 4.原表格一些已经写入库的数据则变为disabled
 * 5.全部完成后点击完成，会将转单号保存至waybill,跳转回预录单列表
 */

//信息填写完后，创建运单基本表信息并出单
$('#auto-api').click(function () {
    var codeNum = $('input[name="codeNum"]').val();                             //运单号
    var orderNum = $('input[name="orderNum"]').val();                           //订单号
    var overWeightOut = $('input[name="overWeightOut"]').val();                 //超重发货
    var valueInsured = $('input[name="valueInsured"]:checked').val();           //是否保价
    var insuranceAmount = $('input[name="insuranceAmount"]').val();             //保价金额
    var countryId = $('select[name="countryId"]').val();                        //国家Id
    var consigneeName = $('input[name="consigneeName"]').val();                 //收件人姓名
    var consigneeTel = $('input[name="consigneeTel"]').val();                   //收件人电话
    var consigneeZip = $('input[name="consigneeZip"]').val();                   //收件人邮编
    var consigneeState = $('input[name="consigneeState"]').val();               //省州
    var consigneeCity = $('input[name="consigneeCity"]').val();                 //城市
    var consigneeCounty = $('input[name="consigneeCounty"]').val();             //区县
    var consigneeAddress1 = $('input[name="consigneeAddress1"]').val();         //收件地址
    var memberName = $('select[name="memberName"]').val();                      //客户名称
    var channelParentId = $('select[name="channelParentId"]').val();            //父渠道
    var channelChildId = $('select[name="channelChildId"]').val();              //子渠道
    var storageId = $('select[name="storageId"]').val();                        //仓库Id
    var remark = $('textarea[name="remark"]').val();                            //备注
    var remarkSpecial = $('textarea[name="remarkSpecial"]').val();              //特殊备注
    var remarkMember = $('input[name="remarkMember"]').val();                   //客户备注
    var declareValue = $('input[name="declareValue"]').val();                   //申报价值
    var weightInput = $('input[name="weightInput"]').val();                     //入库重量

    if(JSON.stringify(goods) == '{}'){
        layer.msg('至少请添加一种物品！');
        return;
    }

    var goodsArr = [];
    for(var g in goods){
        goodsArr[g] = goods[g];
    }

    //判断申报价值与物品的总价值是否相等
    var priceAll = goodsArr.map(function (value) { return  value.price * value.quantity;}).reduce(function (previousValue, currentValue) { return previousValue+currentValue; });

    if(priceAll != declareValue){
        layer.msg('申报价值与物品总价不符');return;
    }

    // 若选择了保价，则必须填写保价金额
    if(valueInsured == 1 && !insuranceAmount){
        layer.msg('您选择了保价，请填写保价金额');return;
    }
    if(insuranceAmount && insuranceAmount <= 0){
        layer.msg('请填写正确的保价金额');return;
    }
    if(!codeNum || !orderNum || !countryId || !consigneeTel || !consigneeName || !consigneeZip || !consigneeState || !consigneeCity || !consigneeAddress1 || !memberName || !channelParentId || !channelChildId || !storageId || !declareValue || !weightInput){
        layer.msg('星号为必填项！'); return;
    }

    if(declareValue <= 0){
        layer.msg('申报价值必须大于零！');return;
    }
    if(weightInput <= 0){
        layer.msg('入库重量必须大于零！');return;
    }

    var post_data = {
        'codeNum':codeNum,
        'orderNum':orderNum,
        'overWeightOut':overWeightOut,
        'valueInsured':valueInsured,
        'insuranceAmount':insuranceAmount,
        'countryId':countryId,
        'consigneeName':consigneeName,
        'consigneeTel':consigneeTel,
        'consigneeZip':consigneeZip,
        'consigneeState':consigneeState,
        'consigneeCity':consigneeCity,
        'consigneeCounty':consigneeCounty,
        'consigneeAddress1':consigneeAddress1,
        'memberName':memberName,
        'channelParentId':channelParentId,
        'channelChildId':channelChildId,
        'storageId':storageId,
        'remark':remark,
        'remarkSpecial':remarkSpecial,
        'remarkMember':remarkMember,
        'declareValue':declareValue,
        'weightInput':weightInput,
        'goods':goodsArr
    };

    $.post('/backend/web/index.php?r=waybill/auto-api',post_data,function (data) {
        layer.msg(data.errorMsg);
        if(data.errorCode==0 && data.waybillId){
            $('input[name="waybillId"]').val(data.waybillId);   //运单ID
            //之前的写入信息不可更改变为disabled
            $('input[name="codeNum"]').attr('disabled',true);
            $('input[name="orderNum"]').attr('disabled',true);
            $('select[name="speed"]').attr('disabled',true);
            $('input[name="overWeightOut"]').attr('disabled',true);
            $('input[name="valueInsured"]').attr('disabled',true);
            $('input[name="insuranceAmount"]').attr('disabled',true);
            $('select[name="countryId"]').attr('disabled',true);
            $('input[name="consigneeName"]').attr('disabled',true);
            $('input[name="consigneeTel"]').attr('disabled',true);
            $('input[name="consigneeZip"]').attr('disabled',true);
            $('input[name="consigneeState"]').attr('disabled',true);
            $('input[name="consigneeCity"]').attr('disabled',true);
            $('input[name="consigneeCounty"]').attr('disabled',true);
            $('input[name="consigneeAddress1"]').attr('disabled',true);
            $('select[name="memberName"]').attr('disabled',true);
            $('select[name="channelParentId"]').attr('disabled',true);
            $('select[name="channelChildId"]').attr('disabled',true);
            $('select[name="storageId"]').attr('disabled',true);
            $('input[name="declareValue"]').attr('disabled',true);
            $('input[name="weightInput"]').attr('disabled',true);
            $('input[name="nameCn"]').attr('disabled',true);
            $('input[name="nameEn"]').attr('disabled',true);
            $('input[name="hsCode"]').attr('disabled',true);
            $('input[name="price"]').attr('disabled',true);
            $('input[name="quantity"]').attr('disabled',true);
            $('input[name="weight"]').attr('disabled',true);
            $('.goods-add').attr('disabled',true);
            $('.goods-remove').attr('disabled',true);
            $('#auto-api').attr('disabled',true);

            //若出单接口有返回信息则转单号自动写入
            var out = data.out;
            if(out){
                $('input[name="expressNum"]').val(out.waybill);
            }
        }
    },'json');
});
//完善备注信息
$('.finish').click(function () {
    var remark = $('textarea[name="remark"]').val();
    var remarkSpecial = $('textarea[name="remarkSpecial"]').val();
    var remarkMember = $('input[name="remarkMember"]').val();
    var waybillId = $('input[name="waybillId"]').val();
    var expressNum = $('input[name="expressNum"]').val();
    var post_data = {
        'expressNum':expressNum,
        'remark':remark,
        'remarkSpecial':remarkSpecial,
        'remarkMember':remarkMember,
        'waybillId':waybillId,
    };

    $.post('/backend/web/index.php?r=waybill/finish-prerecord',post_data,function (data) {
        layer.msg(data.errorMsg);
    },'json');
});

//运单追踪 单票
$('#single-trace').click(function () {
    var expressNum = $('input[name="expressNum"]').val();
    $.post('/backend/web/index.php?r=trace/single-trace',{'expressNum':expressNum},function (data) {
        if(data.errorCode == 1){
            layer.msg(data.errorMsg);
        }else{
            var message = data.traceMessage;
            if(!message.ReturnValue){
                layer.msg(data.errorMsg);
            }else{
                var html = '<tr>';
                html += '<td>' + data.expressNum + '</td>';
                html += '<td>' + message.Response_Info.Number + '</td>';
                html += '<td>' + message.Response_Info.referNbr + '</td>';
                html += '<td>' + message.Response_Info.EmsKind + '</td>';
                html += '<td>' + message.Response_Info.Destination + '</td>';
                html += '<td>' + message.Response_Info.transKind + '</td>';
                html += '<td>' + message.Response_Info.Receiver + '</td>';
                html += '<td>' + message.Response_Info.totalPieces + '</td>';
                html += '<td>' + message.Response_Info.totalWeigt + '</td>';
                var status = message.Response_Info.status;
                if(status == '其他异常' || status == '扣关' || status == '超时' || status == '地址错误' || status == '销毁'){
                    html += '<td><span class="label label-danger">' + status + '</span></td>';
                }else if(status == '转运中'){
                    html += '<td><span class="label label-primary">' + status + '</span></td>';
                }else if(status == '送达'){
                    html += '<td><span class="label label-success">' + status + '</span></td>';
                }else if(status == '未发送'){
                    html += '<td><span class="label bg-gray">' + status + '</span></td>';
                }else if(status == '已发送'){
                    html += '<td><span class="label bg-black">' + status + '</span></td>';
                }else if(status == '丢失' || status == '退件'){
                    html += '<td><span class="label bg-purple">' + status + '</span></td>';
                }else{
                    html += '<td>' + status + '</td>';
                }
                html += '<td>' + message.Response_Info.deliveryDate + '</td>';
                var last_info = message.trackingEventList;
                html += '<td>' + last_info[last_info.length-1].details + '</span></td>';
                html += '<td>';
                html += '<a href="/backend/web/index.php?r=trace/detail&expressNum=' + data.expressNum + '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-info-sign">查看</span></a>';
                //异常件要有发送按钮
                if(status == '其它异常' || status == '扣关' || status == '超时' || status == '地址错误' || status == '销毁' || status == '丢失' || status == '退件'){
                    html += '<a href="/backend/web/index.php?r=user-alarms/abnormal-mail&expressNum=' + data.expressNum + '" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-envelope">发送</span></a>';
                }
                html += '</td>';
                html += '</tr>';
                $('#trace-message').html(html);
            }
        }
    },'json');
});

//运单追踪全反选
$('input[name="expressNum-all"]').click(function () {
    var checks = $('input[name="expressNum"]');
    if($(this).is(':checked')){
        checks.prop('checked',true);
        var expressStr = '';
        $.each(checks,function () {
            expressStr += $(this).val() + ';';
        });
        $('input[name="exp-ids"]').val(expressStr);
    }else{
        checks.prop('checked',false);
    }
});

//
$('input[name="expressNum"]').change(function () {
    var checks = $('input[name="expressNum"]:checked');
    var all = $('input[name="expressNum"]');
    if(all.length == checks.length){
        $('input[name="expressNum-all"]').prop('checked',true);
    }else{
        $('input[name="expressNum-all"]').prop('checked',false);
    }
    var expressStr = '';
    $.each(checks,function () {
        expressStr += $(this).val() + ';';
    });
    $('input[name="exp-ids"]').val(expressStr);
    console.log(expressStr);
});