<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/10/24
 * Time: 15:09
 */

namespace backend\controllers;

use backend\models\Apijisu;
use yii\web\Controller;
use backend\helpers\AdminFun;

class TestController extends Controller{

    public function actionTestOffLine()
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,0);
        curl_setopt($ch,CURLOPT_URL,'http://www.baidu.com');
        $res = curl_exec($ch);
        if(!$res){
            echo curl_error($ch);
        }
        var_dump($res);
    }
    public function actionTry()
    {
        $username = 'zhuanran';
        $token = '123456';
        $callId = time();
        $version = '1.1.0';
        $signArr = [
            'username'=>$username,
            'token'=> $token,
            'callId'=> $callId,
            'version'=> $version,
        ];
        ksort($signArr);
        $sign = md5(implode('',$signArr));

        $postData = [
            'callId' => $callId,
            'token' => $token,
            'username' => $username,
            'version' => $version,
            'sign' => $sign,
            'adminId' => 199,
            'waybillId' => 189999,
            'createInvoice' => 0
        ];

        $url1 = 'http://api.js-exp.com/order/autoApi';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
        curl_setopt($ch,CURLOPT_URL,$url1);

        //curl_exec($ch);
        $res = json_decode(curl_exec($ch),true);

        //$this->print_log(\Yii::$app->basePath.'\uploads\log.txt',json_encode($postData));
        var_dump($res);die;
    }

    function print_log($url,$data){
        $file = fopen($url,"a");
        fwrite($file,date('Y-m-d H:i:s').'：'.$data."\r\n");
        fclose($file);
    }

    public function actionGetChildChannel()
    {
        //echo \Yii::$app->basePath;die;
        $username = 'zhuanran';
        $token = '123456';
        $callId = time();
        $version = '1.0.0';
        $signArr = [
            'username'=>$username,
            'token'=> $token,
            'callId'=> $callId,
            'version'=> $version,
        ];
        ksort($signArr);
        $sign = md5(implode('',$signArr));

        $postData = [
            'callId' => $callId,
            'token' => $token,
            'username' => $username,
            'version' => $version,
            'sign' => $sign,
            'country' => 215,
            'weight' => 12,
            'memberId' => 883,
            'storage' => 1,
            'speed' => 'F',
            'parentId' => 1,
        ];

        $url1 = 'http://api.jisuexp.com/common/channelListByCost';
        $url2 = 'http://api.js-exp.com/common/channelListByCost';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
        curl_setopt($ch,CURLOPT_URL,$url2);
        //curl_exec($ch);
        /*if(!curl_exec($ch)){
            echo curl_error($ch);
        }*/
        //file_put_contents(\Yii::$app->basePath.'\uploads\log.txt',curl_exec($ch));
        $res = json_decode(curl_exec($ch),true);
        curl_close($ch);
        var_dump($res);
    }

    public function actionTrace()
    {
        header("application/x-www-form-urlencoded;charset=utf-8");
        $username = 'zhuanran';
        $token = '123456';
        $callId = time();
        $version = '1.0.0';
        $signArr = [
            'username'=>$username,
            'token'=> $token,
            'callId'=> $callId,
            'version'=> $version,
        ];
        ksort($signArr);
        $sign = md5(implode('',$signArr));

        $postData = [
            'callId' => $callId,
            'token' => $token,
            'username' => $username,
            'version' => $version,
            'sign' => $sign,
            'expressNum' => '1054910614' ,
        ];
        $url = 'http://api.js-exp.com/waybill/Tracking';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
        curl_setopt($ch,CURLOPT_URL,$url);
        //curl_exec($ch);
//        if(!curl_exec($ch)){
//            echo curl_error($ch);
//        }
//        $this->print_log(\Yii::$app->basePath.'/uploads/log.txt',json_encode($postData));
//        file_put_contents(\Yii::$app->basePath.'/uploads/trace.txt',curl_exec($ch));
        $res = json_decode(curl_exec($ch),true);
        curl_close($ch);
        var_dump($res);
    }

    public function actionTraceMore()
    {
        //header("application/x-www-form-urlencoded;charset=utf-8");
        $expressArr = array('4112414762','9555540455','875840500','9555469022','9555470713','9555540256','9555540385');
        //$expressArr = array('4112414762');
        $username = 'zhuanran';
        $token = '123456';
        $callId = time();
        $version = '1.0.0';
        $signArr = [
            'username'=>$username,
            'token'=> $token,
            'callId'=> $callId,
            'version'=> $version,
        ];
        ksort($signArr);
        $sign = md5(implode('',$signArr));

        $tmpArr = array(
            'expressNum' => $expressArr,
        );
        $expressNum = base64_encode(json_encode($tmpArr));

        $postData = [
            'callId' => $callId,
            'token' => $token,
            'username' => $username,
            'version' => $version,
            'sign' => $sign,
            'expressNum' => $expressNum ,
        ];
        $url = 'http://api.js-exp.com/waybill/tracemany';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
        curl_setopt($ch,CURLOPT_URL,$url);
        //curl_exec($ch);

//        $this->print_log(\Yii::$app->basePath.'\uploads\log.txt',json_encode($postData));
//        file_put_contents(\Yii::$app->basePath.'\uploads\traceMore.txt',curl_exec($ch));
        $res = json_decode(curl_exec($ch),true);
        var_dump($res);
        curl_close($ch);
    }

    public function actionIntegral()
    {
        $arr = [
            881 => 1,
            882 => 2,
            883 => 3,
            884 => 4,
        ];
        $sql = "UPDATE `ts_user` set integral= CASE id ";
        foreach ($arr as $k=>$v){
            $sql .= "WHEN $k THEN $v ";
        }
        $keyStr = implode(',',array_keys($arr));
        $sql .= "END WHERE id IN (" . $keyStr .")";

        \Yii::$app->db->createCommand($sql)->execute();

    }
}