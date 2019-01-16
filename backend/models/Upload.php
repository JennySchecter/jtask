<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/11/7
 * Time: 16:34
 */
namespace backend\models;

use Yii;
use yii\base\Model;

class Upload extends Model{
    public $file;
    public $txt;
    public $knowledgeFile;

    public function rules()
    {
        return [
            ['file','file','extensions'=>'xls'],
            ['file','required'],
            ['txt','file','extensions'=>'txt'],
            ['txt','required'],
            ['knowledgeFile','required',],
            ['knowledgeFile','file','extensions'=>'txt,xls,xlsx,jpeg,jpg,png,gif,doc,docx,pdf'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file'=>'Excel文件上传',
            'txt' => 'txt文件上传',
            'knowledgeFile' => '知识库文件上传',
        ];
    }



}