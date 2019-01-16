<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2019/1/3
 * Time: 22:51
 */
namespace backend\controllers;

use backend\models\Knowledge;
use backend\models\Upload;
use yii\data\Pagination;
use yii\web\Controller;
use Yii;
use yii\web\UploadedFile;

class KnowledgeController extends Controller{
    public function actionIndex()
    {
        $data = Knowledge::find();

        $count = $data->count();
        $pager = new Pagination([
           'totalCount' => $count,
           'pageSize' => 20,
        ]);

        $fileLists = $data->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        return $this->render('index',[
            'fileLists' => $fileLists,
            'pager'     => $pager,
            'total'     => $count,
        ]);
    }

    public function actionAdd()
    {
        $model = new Knowledge();
        $upload = new Upload();
        if(yii::$app->request->post()){
            $file = UploadedFile::getInstance($upload,'knowledgeFile');
            $filename = date('YmdHis') . '-' . iconv("UTF-8","gb2312", $file->name);
            $file->saveAs(yii::$app->basePath . '/uploads/knowledge/' . $filename);
            $post = Yii::$app->request->post();
            $post['Knowledge']['attachment_path'] = '/knowledge/'. $filename;
            if ( $model->add($post) ) {
                Yii::$app->session->setFlash('msg','添加成功!');
            }else{
                // 删除上传的文件
                unlink($filename);
                Yii::$app->session->setFlash('msg','添加失败，请重试');
            }
        }
        return $this->render('add',[
            'model' => $model,
            'upload' => $upload,
        ]);
    }

    public function actionDownload()
    {
        $filename = Yii::$app->request->get('filename');

        $file = fopen ( '../uploads' . $filename, "r" );

        Header ( "Content-type: application/octet-stream" );
        Header ( "Accept-Ranges: bytes" );
        Header ( "Accept-Length: " . filesize ( '../uploads' . $filename ) );
        Header ( "Content-Disposition: attachment; filename=" . '../uploads' . $filename );
        //输出文件内容
        //读取文件内容并直接输出到浏览器
        echo fread ( $file, filesize ( '../uploads' . $filename ) );
        fclose ( $file );
        exit ();
    }
    public function actionDel()
    {
        $id = Yii::$app->request->post('id');
        $model = Knowledge::find()->where(['id'=>$id])->asArray()->one();
        $result = ['errorCode' => 1, 'errorMsg' => '系统错误'];
        if(empty($model)){
            $result['errorMsg'] = '该文档不存在';
        }else{
            $res = Knowledge::deleteAll(['id'=>$id]);
            if($res){
                // 删除附件
                unlink(Yii::$app->basePath.'/uploads'.$model['attachment_path']);
                $result = ['errorCode' => 0, 'errorMsg' => '删除成功'];
            }else{
                $result['errorMsg'] = '删除失败';
            }
        }
        return json_encode($result);
    }
}