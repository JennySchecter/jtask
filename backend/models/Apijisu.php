<?php
namespace backend\models;

use Yii;
use yii\base\Model;

class Apijisu extends Model{
    public $username = 'zhuanran';
    public $token = '123456';
    public $callId;
    public $version = '1.0.0';

    public $auto = 'http://api.js-exp.com/order/autoApi';   //出单接口
    public $channel = 'http://api.js-exp.com/common/channelListByCost';    //渠道成本接口
    public $trace = 'http://api.js-exp.com/waybill/Tracking';  //转单号单票追踪接口
    public $traceMany = 'http://api.js-exp.com/waybill/tracemany';  //转单号多票追踪接口

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**计算渠道成本
     * @param $data
     * @return mixed
     */
    public function channelCost($data)
    {
        header("application/x-www-form-urlencoded;charset=utf-8");
        $sign = $this->generateSign($this->username,$this->token,$this->callId,$this->version);
        $postData = [
            'callId' => $this->callId,
            'token' => $this->token,
            'username' => $this->username,
            'version' => $this->version,
            'sign' => $sign,
            'country' => (integer)$data['countryId'],
            'weight' => $data['weightInput'],
            'memberId' => $data['memberId'],
            'storage' => $data['storageId'],
            'speed' => $data['speed'],
            'parentId' => $data['parentId']
        ];
        return $this->curlPost($postData,$this->channel);
    }

    /**出单接口 可能返回转单号
     * @param $data
     * @return mixed
     */
    public function autoApi($data)
    {
        header("application/x-www-form-urlencoded;charset=utf-8");
        $sign = $this->generateSign($this->username,$this->token,$this->callId,$this->version);
        $postData = [
            'callId' => $this->callId,
            'token' => $this->token,
            'username' => $this->username,
            'version' => $this->version,
            'sign' => $sign,
            'waybillId' => $data['waybillId'],
            'adminId' => Yii::$app->user->getId(),
            'createInvoice' => 0,
        ];
        return $this->curlPost($postData,$this->channel);
    }

    /**单票追踪
     * @param $expressNum
     * @return mixed
     */
    public function tracking($expressNum)
    {
        header("application/x-www-form-urlencoded;charset=utf-8");
        $sign = $this->generateSign($this->username,$this->token,$this->callId,$this->version);
        $postData = [
            'callId' => $this->callId,
            'token' => $this->token,
            'username' => $this->username,
            'version' => $this->version,
            'sign' => $sign,
            'expressNum' => $expressNum,
        ];
        return $this->curlPost($postData,$this->trace);
    }

    /**多票追踪
     * @param $expressArr
     * @return mixed
     */
    public function trackingMany($expressArr)
    {
        header("application/x-www-form-urlencoded;charset=utf-8");
        $sign = $this->generateSign($this->username,$this->token,$this->callId,$this->version);
        $tmpArr = array(
            'expressNum' => $expressArr,
        );
        $expressNum = base64_encode(json_encode($tmpArr));
        $postData = [
            'callId' => $this->callId,
            'token' => $this->token,
            'username' => $this->username,
            'version' => $this->version,
            'sign' => $sign,
            'expressNum' => $expressNum,
        ];
        return $this->curlPost($postData,$this->traceMany);
    }
    /**返回post请求数据
     * @param $postData
     * @param $url
     * @return mixed
     */
    public function curlPost($postData,$url)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
        curl_setopt($ch,CURLOPT_URL,$url);

        $res = json_decode(curl_exec($ch),true);
        if($res === false){
            echo curl_error($ch);exit;
        }
        return $res;
    }

    public function generateSign($username,$token,$callId,$version)
    {
        $signArr = [
            'username' => $username,
            'token'    => $token,
            'callId'   => $callId,
            'version'  => $version
        ];
        ksort($signArr);
        $sign = md5(implode('',$signArr));
        return $sign;
    }
}