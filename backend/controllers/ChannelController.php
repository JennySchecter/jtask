<?php
namespace backend\controllers;
use backend\models\Apijisu;
use backend\models\Channel;
use backend\models\User;
use Yii;
use yii\web\Controller;

class ChannelController extends Controller{
    /**
     * 获取父渠道下拉检索
     */
    public function actionGetParentChannel($q)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '','text'=>'']];
        if(!$q){
            return $out;
        }

        $data = Channel::find()->where(['parentId'=>0])
                ->select('id,name as text')
                ->andFilterWhere(['like','name',$q])
                ->limit(50)
                ->asArray()
                ->all();

        $out['results'] = array_values($data);
        return $out;
    }

    /**
     * 创建预录单时通过渠道成本接口获得子渠道按费用顺序排列
     */
    public function actionGetChildChannel()
    {
        $result = ['errorCode' => 1, 'errorMsg'=>'系统错误','res'=>[]];
        $data = Yii::$app->request->post()['data'];

        $member = User::find()->where(['username'=>$data['memberId']])->one();
        $data['memberId'] = $member->id;

        $api = new Apijisu(['callId' => time()]);
        $res = $api->channelCost($data);

        if($res['code'] == 0){
            $result = ['errorCode' => 0, 'errorMsg'=>'success','res'=>$res['data']];
        }else{
            $result['errorMsg'] = '请检查信息填写是否有误';
        }
        return json_encode($result);

    }

    public function actionGetChildByParent()
    {
        $pid = Yii::$app->request->get('pid');
        $child = Channel::dropDrownChild($pid);
        $str = '';
        if(count($child) > 0){
            foreach ($child as $k=>$v){
                $str .= '<option value="'. $k .'">' .$v . '</option>';
            }
        }else{
            $str .= '<option></option>';
        }
        return $str;
    }
}