<?php
namespace backend\controllers;

use backend\helpers\AdminFun;
use backend\models\Admin;
use backend\models\Pickup;
use backend\models\SurveyList;
use backend\models\User;
use backend\models\UserSend;
use backend\models\Waybill;
use backend\models\WaybillProblem;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $admin = Admin::findIdentity(Yii::$app->user->getId());

//        $isStaff = AdminFun::managerOrStaff($admin['id']);
//        if($isStaff){
//            if($isStaff == 'staff'){  //客服
//                $group = $admin['groupid'];
//                //若该客服有分组，查询出他所在分组对应的客户
//                if($group){
//                    $serviceCustoms = User::find()->where(['groupid'=>$group])->asArray()->all();
//
//                    $customs = array_map(function ($v){return $v['name'];},$serviceCustoms);
//
//                    //筛选出该客户对应的国内异常（问题件）运单
//                    //$billProblem = Waybill::find()->joinWith(['waybillStatus'])->where()
//                }
//            }else{
//
//            }
//        }
        //国内异常件(问题件)
        //$a = Waybill::find()->joinWith(['waybillStatus'])->where([])
        $queryBillProblem = WaybillProblem::find(); //问题件
        $queryPickup = Pickup::find(); //取件
        Pickup::updateAll(['status'=>2],'status=0 and last_time <'.time());
        $querySend = UserSend::find();//寄件
        $querySurveyList = SurveyList::find();  //调查工单

        //调查工单超时
        SurveyList::updateAll(['overtime'=>1],'status=0 and c_time <'.(time()-86400));
        SurveyList::updateAll(['overtime'=>2],'status=1 and next_time <'.(time()-86400));

        //未处理问题件统计
        $billProblemCount = $queryBillProblem->where(['deal_status'=>0])->count();
        //问题件未处理超时
        $problemOvertime = $queryBillProblem->where(['deal_status'=>0])
                        ->andFilterWhere(['<','c_time',time()-86400])->count();
        //处理中
        $dealOvertime = $queryBillProblem->where(['deal_status'=>1])->count();
        //待取件
        $pickCount = $queryPickup->where(['status'=>0])->count();
        //待取件超时
        $pickOvertime = $queryPickup->where(['status'=>2])->count();

        //异常件调查未处理
        $surveyCount = $querySurveyList->where(['status'=>0])->count();
        //异常件调查处理中
        $surveyDealCount = $querySurveyList->where(['status'=>1])->count();
        //异常件处理超时
        $surveyOvertime = $querySurveyList->Where('overtime != 0')->count();


        //寄件未处理
        $sendCount = $querySend->where(['status'=>0])->count();
        //寄件问题件
        $sendProblem = $querySend->where(['status'=>1])->count();

        return $this->render('index',[
            'admin'=>$admin,
            'billProblemCount' => $billProblemCount,
            'problemOvertime' => $problemOvertime,
            'dealOvertime' => $dealOvertime,
            'pickCount' => $pickCount,
            'pickOvertime' => $pickOvertime,

            'surveyCount' => $surveyCount,
            'surveyDealCount' => $surveyDealCount,
            'surveyOvertime' => $surveyOvertime,

            'sendCount' => $sendCount,
            'sendProblem' => $sendProblem,
        ]);
    }

    /**
     * Login action.
     *后台用户登录
     * @return string
     */
    public function actionLogin()
    {
        // 判断用户是访客还是认证用户
        // isGuest为真表示访客，isGuest非真表示认证用户，认证过的用户表示已经登录了，这里跳转到主页面
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
