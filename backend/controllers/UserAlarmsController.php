<?php

namespace backend\controllers;

use backend\models\AbnormalNotify;
use backend\models\Admin;
use backend\models\Group;
use backend\models\Upload;
use backend\models\UserAlarms;
use backend\models\Waybill;
use DeepCopy\f001\A;
use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \PHPExcel;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserAlarmsController extends Controller
{
    /**
     * 客服发送站内信给客户
     */
    public function actionMail()
    {
        $model = new UserAlarms();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $user = User::find()->where(['username'=>$post['UserAlarms']['userId']])->one();
            $post['UserAlarms']['userId'] = $user['id'];
            $post['UserAlarms']['adminId'] = Yii::$app->user->getId();
            $post['UserAlarms']['type'] = 1;
            if($model->create($post)){
                Yii::$app->session->setFlash('msg','发送成功！');
            }else{
                Yii::$app->session->setFlash('msg','发送失败！');
            }
        }
        return $this->render('mail',[
            'model' => $model,
        ]);
    }

    /**问题件发送站内信通知
     * @return string
     */
    public function actionProblemMail()
    {
        $id = Yii::$app->request->get('id');
        $waybill = Waybill::find()->where(['id'=>$id])->one();
        $model = new UserAlarms();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $post['UserAlarms']['adminId'] = Yii::$app->user->getId();
            $post['UserAlarms']['type'] = 1;
            if($model->create($post)){
                Yii::$app->session->setFlash('msg','发送成功！');
            }else{
                Yii::$app->session->setFlash('msg','发送失败！');
            }
        }
        return $this->render('problem-mail',[
            'waybill'=>$waybill,
            'model' => $model,
        ]);
    }

    /**
     * 异常件通知2018/11/26
     */
    public function actionAbnormalMail()
    {
        $model = new UserAlarms();

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $waybill = Waybill::find()->where(['id'=>$post['id']])->one();
            $post['UserAlarms']['type'] = 2;
            $post['UserAlarms']['expressNum'] = $waybill['expressNum'];
            $post['UserAlarms']['adminId'] = Yii::$app->user->getId();
            //写入UserAlarms 表，abnormal_notify增加记录或者对应的运单发送次数+1
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $res = $model->create($post);

                $notifyModel =  AbnormalNotify::find()->where(['express_num'=>$waybill['expressNum'],'user_id'=>$waybill['memberId']])->one();
                if($notifyModel){
                    //次数加一
                    $notifyModel->count += 1;
                    $res = $res && $notifyModel->save();
                }else{
                    //插入记录
                    $notifyModel = new AbnormalNotify();
                    $notifyModel->user_id = $waybill['memberId'];
                    $notifyModel->express_num = $waybill['expressNum'];
                    $res = $res && $notifyModel->save();
                }

                if($res){
                    Yii::$app->session->setFlash('msg','发送成功');
                    $transaction->commit();
                }else{
                    Yii::$app->session->setFlash('msg','发送失败');
                    $transaction->rollBack();
                }
            }catch (\Exception $e){
                Yii::$app->session->setFlash('msg','发送失败');
                $transaction->rollBack();
            }
        }else{
            $expressNum = Yii::$app->request->get('expressNum');
            $waybill = Waybill::find()->where(['expressNum'=>$expressNum])->one();
        }
        $notify = AbnormalNotify::find()->where(['express_num'=>$waybill['expressNum'],'user_id'=>$waybill['memberId']])->asArray()->one();
        $times = empty($notify) ? 0:$notify['count'];
        $userAlarms = UserAlarms::find()->where(['expressNum'=>$waybill['expressNum'],'userId'=>$waybill['memberId']])->asArray()->all();
        return $this->render('abnormal-mail',[
            'waybill'=>$waybill,
            'model' => $model,
            'times' => $times,
            'userAlarms' => $userAlarms,
        ]);
    }
}
