<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/10/24
 * Time: 15:07
 * 存放自定义函数
 */
namespace backend\helpers;

use mdm\admin\models\Assignment;

class AdminFun{
    /**
     * 获取系统配置表中的值   2018/10/24
     */
    public static function Config($name = NULL,$value = NULL)
    {
        if($name === NULL){
            return false;
        }
        $data = \Yii::$app->db->createCommand("select * from ts_kf_config where `name`='{$name}'")->queryOne();
        //获取值
        if($value === NULL){
            if($data){
                return unserialize($data['data']);
            }
        }
        //设置值
        if($name != '' && $value!==NULL){
            //分新增和修改
            if($data){
                //修改
                \Yii::$app->db->createCommand()->update('ts_kf_config',['data' => serialize($value)],['id' => $data['id']])->execute();
            }else{
                \Yii::$app->db->createCommand()->insert('ts_kf_config',['name' => $name,'data' => serialize($value)])->execute();
            }
            return true;
        }
        return NULL;
    }

    /**
     * 获取waybill_status状态文字描述 2018/10/25
     */
    public static function getStatusValue($status = NULL)
    {
        switch ($status){
            case 1:
                return '未入库';break;
            case 2:
                return '已入库';break;
            case 3:
                return '处理中';break;
            case 4:
                return '已出库';break;
            case 5:
                return '已签收';break;
            default:
                return '';break;
        }
    }


    public static function managerOrStaff($id)
    {
        $assignment = \Yii::$app->db->createCommand('select * from `ts_auth_assignment` where `user_id`='.$id)->queryAll();
        $keyWords = array_map(function ($v){return $v['item_name'];},$assignment);
        if(!$assignment){
            return false;
        }elseif(in_array('客服人员',$keyWords)){
            return 'staff';
        }else{
            return 'manger';
        }
    }
}