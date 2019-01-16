<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/11/9
 * Time: 17:52
 * Desc:运单追踪
 */
namespace backend\controllers;

use backend\models\Apijisu;
use backend\models\Channel;
use backend\models\Country;
use backend\models\Group;
use backend\models\Storage;
use backend\models\User;
use backend\models\Waybill;
use backend\models\WaybillActioner;
use backend\models\WaybillFinance;
use backend\models\WaybillSearch;
use http\Url;
use Yii;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use PHPExcel;

class TraceController extends Controller{

    public function actionSingle()
    {
        return $this->render('single');
    }

    /**运单单票追踪
     * @return false|string
     */
    public function actionSingleTrace()
    {
        $expressNum = Yii::$app->request->post('expressNum');
        $result = ['errorCode'=>1,'errorMsg'=>'系统错误','traceMessage'=>''];

        $api = new Apijisu(['callId'=>time()]);
        $traceResult = $api->tracking($expressNum);
        //var_dump($traceResult);die;
        if(!$traceResult){
            $result['errorMsg'] = '接口请求错误';
        }else{
            if($traceResult['code'] == 1){
                $result['errorMsg'] = $traceResult['message'];
            }else{
                //追踪结果状态为签收 则增加客户积分
                if($traceResult['message']['Response_Info']['status'] == '送达'){
                    $waybill = Waybill::find()->where(['expressNum'=>$expressNum])->asArray()->one();
                    if($waybill){
                        $finance = WaybillFinance::find()->where(['waybillId'=>$waybill['id']])->asArray()->one();
                        //运费大于零,添加到用户积分
                        if($finance && $finance['amountWaybill']>0){
                            $userModel = User::find()->where(['id'=>$waybill['memberId']])->one();
                            $userModel->integral += $finance['amountWaybill'];
                            $userModel->save(false);
                        }
                    }
                }
                $result = ['errorCode'=>0,'errorMsg'=>'success','traceMessage'=>$traceResult['message'],'expressNum'=>$expressNum];
            }
        }
        return json_encode($result);
    }

    /**运单详细轨迹信息
     * @return string
     */
    public function actionDetail()
    {
        $expressNum = Yii::$app->request->get('expressNum');
        $api = new Apijisu(['callId'=>time()]);
        $traceResult = $api->tracking($expressNum);
        return $this->render('detail',[
            'traces'=> $traceResult['message']['trackingEventList'],
            'batch' => Yii::$app->request->get('batch'),
            //'status' => $traceResult['message']['Response_Info']['status'],
            'status' => '其它异常',
            'expressNum' => '1054910614'
        ]);
    }


    /**运单批量追踪
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionBatch()
    {
        $searchModel = new WaybillSearch();
        return $this->render('batch', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionBatchTrace()
    {
        if(Yii::$app->request->isPost){
            $searchModel = new WaybillSearch();
            $waybills = $searchModel->searchTrace(Yii::$app->request->post())->asArray()->all();
            //提取转单号
            $expressArr = array_map(function ($v){return $v['expressNum'];},$waybills);
            if(empty($expressArr)){
                Yii::$app->session->setFlash('msg','筛选结果为空');
                return $this->redirect(['batch']);
            }else{
                //注：数据量太多需要ajax翻页
                //要查询的转单号存入cache
                Yii::$app->cache->set('expressArr',$expressArr);
                $shiftArr = [];
                if (count($expressArr) < 200) {
                    $shiftArr = Yii::$app->cache->get('expressArr');
                    $more = false;
                }else{
                    for ($i = 1; $i <= 200; $i++) {
                        $shiftArr[] = array_shift($expressArr);
                    }
                    Yii::$app->cache->set('expressArr',$expressArr);
                    $more = true;
                }
                $api = new Apijisu(['callId'=>time()]);
                $result = $api->trackingMany($shiftArr);
                $traceLists = [];
                //请求成功
                if($result['code'] == 0){
                    $valueArr = array_values($result['data']);
                    $traceLists = array_combine($shiftArr,$valueArr);
                    //var_dump($traceLists);die;
                    //写入缓存 ，后面导出使用
                    $cache = Yii::$app->cache;
                    $cache->set('traceLists',$traceLists,60*60);
                    //print_r($traceLists);die;
                    $integralData = [];
                    //用户及对应增加积分
                    foreach ($traceLists as $k=>$v){
                        if(is_array($v) && $v['Response_Info']['status'] == '送达'){
                            $waybill = Waybill::find()->where(['expressNum'=>$k])->asArray()->one();
                            $finance = WaybillFinance::find()->where(['waybillId'=>$waybill['id']])->asArray()->one();
                            if($finance && $finance['amountWaybill']>0){
                                $userModel = User::find()->where(['id'=>$waybill['memberId']])->asArray()->one();
                                $integralData[$waybill['memberId']] = $userModel['integral'] + $finance['amountWaybill'];
                            }
                        }
                    }

                    //拼接sql语句 修改用户积分
                    if(count($integralData) > 0){
                        $sql = "UPDATE `ts_user` SET integral= CASE id ";
                        foreach ($integralData as $k => $v){
                            $sql .= "WHEN $k THEN $v ";
                        }
                        $idStr = implode(',',array_keys($integralData));
                        $sql .= "END WHERE id IN (" . $idStr . ")";
                        Yii::$app->db->createCommand($sql)->execute();
                    }
                }
            }
        }
        $traceLists = Yii::$app->cache->get('traceLists');
        return $this->render('batch-trace',[
            'traceLists'=>$traceLists,
            'more' => $more,
        ]);
    }

    /**
     * ajax拉取数据
     */
    public function actionAjaxPull()
    {
        $expressNum = Yii::$app->cache->get('expressArr');
        $more = 1;
        $result = ['html' => '', 'more' => 1];
        if ( !empty($expressNum) ) {
            if( count($expressNum) > 200 ){
                $shiftArr = [];
                for ($i = 1; $i <= 200; $i++){
                    $shiftArr[] = array_shift($expressNum);
                }
                Yii::$app->cache->set('expressNum',$expressNum);
            }else{
                $shiftArr = Yii::$app->cache->get('expressNum');
                Yii::$app->cache->set('expressNum',[]);
                $more = 0;
            }

            $api = new Apijisu(['callId'=>time()]);
            $result = $api->trackingMany($shiftArr);
            $traceLists = [];

            $html = '';
            //请求成功
            if($result['code'] == 0){
                $valueArr = array_values($result['data']);
                $traceLists = array_combine($shiftArr,$valueArr);

                foreach ($traceLists as $k => $v){
                    if (is_array($v)) {
                        $html .= '<tr><td><input type="checkbox" name="expressNum" value="' . $k . '">' . $k .'</td>';
                        $html .= '<td>' . $v['Response_Info']['Number'] . '</td>';
                        $html .= '<td>' . $v['Response_Info']['referNbr'] . '</td>';
                        $html .= '<td>' . $v['Response_Info']['EmsKind'] . '</td>';
                        $html .= '<td>' . $v['Response_Info']['Destination'] . '</td>';
                        $html .= '<td>' . $v['Response_Info']['transKind'] . '</td>';
                        $html .= '<td>' . $v['Response_Info']['Receiver'] . '</td>';
                        $html .= '<td>' . $v['Response_Info']['totalPieces'] . '</td>';
                        $html .= '<td>' . $v['Response_Info']['totalWeigt'] . '</td><td>';

                        $s = $v['Response_Info']['status'];
                        if($s == '其它异常' || $s == '扣关' || $s == '超时' || $s == '地址错误' || $s == '销毁'){
                            $html .= '<span class="label label-danger">' . $s . '</span>';
                        }else if($s == '转运中'){
                            $html .= '<span class="label label-primary">' . $s . '</span>';
                        }else if($s == '送达'){
                            $html .= '<span class="label label-success">' . $s . '</span>';
                        }else if($s == '未发送'){
                            $html .= '<span class="label bg-gray">' . $s . '</span>';
                        }else if($s == '已发送'){
                            $html .= '<span class="label bg-black">' . $s . '</span>';
                        }else if($s == '丢失' || $s == '退件'){
                            $html .= '<span class="label bg-purple">' . $s . '</span>';
                        }else{
                            $html .= $s;
                        }

                        $html .= '</td><td>' . $v['Response_Info']['deliveryDate'] . '</td>';
                        $html .= '<td>' . $v['trackingEventList'][count($v['trackingEventList'])-1]['details'] . '</td>';
                        $html .= '<td>' . Html::a('<span class="glyphicon glyphicon-info-sign"></span>查看',\yii\helpers\Url::to(['/trace/detail','expressNum'=>$k,'batch'=>1]),['class'=>'btn btn-xs btn-primary']) . '</td>';

                        if($s == '其它异常' || $s == '扣关' || $s == '超时' || $s == '地址错误' || $s == '销毁' || $s == '丢失' || $s == '退件'){
                            $html .= Html::a('<span class="glyphicon glyphicon-envelope">发送</span>',['/user-alarms/abnormal-mail','expressNum'=>$k],['class'=> 'btn btn-xs btn-success']);
                        }
                    }
                }
            }
            $result = [ 'html' =>$html, 'more' => $more];
        }else{
            $result = [ 'html' => '', 'more' => 0];
        }
        return Json::encode($result);
    }
    /**
     * 导出选中的追踪运单
     */
    public function actionExport()
    {
        $keys = explode(';',Yii::$app->request->post('exp-ids'));
        array_pop($keys);

        //先处理数据
        $cache = Yii::$app->cache->get('traceLists');
        $exportData = [];
        foreach ($keys as $v){
            $waybill = Waybill::find()->where(['expressNum'=>$v])->asArray()->one();
            $actioner = WaybillActioner::find()->where(['waybillId'=>$waybill['id']])->asArray()->one();
            $channel = Channel::find()->where(['id'=>$waybill['channelParentId']])->asArray()->one();
            $storage = Storage::find()->where(['id'=>$waybill['storageId']])->asArray()->one();
            $userModel = User::find()->where(['id'=>$waybill['memberId']])->asArray()->one();
            $group = Group::find()->where(['id'=>$userModel['groupid']])->asArray()->one();

            $exportData[$v] = [
                'timeIn' => date('Y.m.d',$actioner['timeIn']),
                'name' => $waybill['memberName'],
                'orderNum' => $waybill['orderNum'],
                'expressNum' => $v,
                'channel' => $channel['name'],
                'country' => $cache[$v]['Response_Info']['Destination'],
                'problem' => $cache[$v]['trackingEventList'][count($cache[$v]['trackingEventList'])-1]['details'],
                'storage' => $storage['name'],
                'group' => $group['groupname'],
            ];
        }

        $objPHPExcel = new PHPExcel();
        try{
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1','入库时间')
                ->setCellValue('B1','客户名称')
                ->setCellValue('C1','订单号')
                ->setCellValue('D1','转单号')
                ->setCellValue('E1','渠道')
                ->setCellValue('F1','目的地')
                ->setCellValue('G1','问题')
                ->setCellValue('H1','总仓库')
                ->setCellValue('I1','客户组别');


            $n = 2;
            foreach ($exportData as $v){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.($n),$v['timeIn']);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.($n),$v['name']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.($n),$v['orderNum']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.($n),$v['expressNum']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.($n),$v['channel']);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.($n),$v['country']);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.($n),$v['problem']);
                $objPHPExcel->getActiveSheet()->setCellValue('H'.($n),$v['storage']);
                $objPHPExcel->getActiveSheet()->setCellValue('I'.($n),$v['group']);
                $n++;
            }
            //单元格居中
            $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            ob_end_clean();
            ob_start();

            header('Content-Type:application/vnd.ms-excel');
            //设置输出文件名及格式
            header('Content-Disposition:attachment;filename="'.'运单追踪列表-'.date("YmdHis").'.xls"');

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            $objWriter->save('php://output');
            ob_flush();
        }catch(\Exception $e){
            echo $e ;exit;
        }
    }
}