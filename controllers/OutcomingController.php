<?php

namespace zpearl\shop\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class OutcomingController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->adminRoles,
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'edittable' => ['post'],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = $this->module->getService('outcoming');

        if ($post = Yii::$app->request->post()) {
            $model->date = time();
            $model->content = serialize($post);
            
            $productModel = $this->module->getService('product');
            $flas = '';
            foreach($post['element'] as $id => $count) {
                if($product = $productModel::findOne($id)) {
                    $answer = $product->minusAmount($count, true);
                    if($answer != 1){
                        $flash .= $product->name.' '.$answer.'<br/>';
                        \Yii::$app->session->setFlash('success', $answer);
                    }
                }
            }
            
            if($flash != '') {
                \Yii::$app->session->setFlash('success', $flash);
            } else if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Отправление успешно добавлено.');
            }else {
                \Yii::$app->session->setFlash('success', 'Что-то пошло не так.Попробуйте еще раз.');
            }

            return $this->redirect(['create', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
}
