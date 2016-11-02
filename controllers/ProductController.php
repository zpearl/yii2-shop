<?php
namespace zpearl\shop\controllers;

use Yii;
use zpearl\shop\models\product\ProductSearch;
use zpearl\shop\models\stock\StockSearch;
use zpearl\shop\models\price\PriceSearch;
use zpearl\shop\models\PriceType;
use zpearl\shop\events\ProductEvent;
use zpearl\shop\models\modification\ModificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ProductController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = $this->module->getService('product');
        $priceModel = $this->module->getService('price');
        
        $priceTypes = PriceType::find()->orderBy('sort DESC')->all();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            if($prices = yii::$app->request->post('Price')) {
                foreach($prices as $typeId => $price) {
                    $type = PriceType::findOne($typeId);
                    $price = new $priceModel($price);
                    $price->type_id = $typeId;
                    $price->name = $type->name;
                    $price->sort = $type->sort;
                    $price->product_id = $model->id;
                    $price->save();
                }
            }
            
            $module = $this->module;
            $productEvent = new ProductEvent(['model' => $model]);
            $this->module->trigger($module::EVENT_PRODUCT_CREATE, $productEvent);
            
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'priceModel' => $priceModel,
                'priceTypes' => $priceTypes,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $typeParams = Yii::$app->request->queryParams;
        $typeParams['StockSearch']['product_id'] = $id;
        $StockSearch = new StockSearch();
        $StockDataProvider = $StockSearch->search($typeParams);

        $searchModel = new PriceSearch();
        $typeParams = Yii::$app->request->queryParams;
        $typeParams['PriceSearch']['product_id'] = $id;
        $dataProvider = $searchModel->search($typeParams);
        $priceModel = $this->module->getService('price');
        
        $modificationModel = $this->module->getService('modification');
        $searchModificationModel = new ModificationSearch();
        $typeParams['ModificationSearch']['product_id'] = $id;
        $modificationDataProvider = $searchModificationModel->search($typeParams);
        $modificationModel = $this->module->getService('modification');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $module = $this->module;
            $productEvent = new ProductEvent(['model' => $model]);
            $this->module->trigger($module::EVENT_PRODUCT_UPDATE, $productEvent);
            
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'modificationModel' => $modificationModel,
                'searchModificationModel' => $searchModificationModel,
                'modificationDataProvider' => $modificationDataProvider,
                'model' => $model,
                'module' => $this->module,
                'modificationModel' => $modificationModel,
                'searchModificationModel' => $searchModificationModel,
                'modificationDataProvider' => $modificationDataProvider,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'priceModel' => $priceModel,
                'StockSearch' => $StockSearch,
                'StockDataProvider' => $StockDataProvider,
            ]);
        }
    }

    public function actionDelete($id)
    {
        if($model = $this->findModel($id)) {
            $this->findModel($id)->delete();

            $module = $this->module;
            $productEvent = new ProductEvent(['model' => $model]);
            $this->module->trigger($module::EVENT_PRODUCT_DELETE, $productEvent);
        }
        return $this->redirect(['index']);
    }

    public function actionProductInfo()
    {
        $productCode = (int)yii::$app->request->post('productCode');
        
        $model = $this->module->getService('product');
        
        if($model = $model::find()->where('code=:code OR id=:code', [':code' => $productCode])->one()) {
            $json = [
                'status' => 'success',
                'name' => $model->name,
                'code' => $model->code,
                'id' => $model->id,
            ];
        } else {
            $json = [
                'status' => 'fail',
                'message' => yii::t('order', 'Not found')
            ];
        }
        
        die(json_encode($json));
    }
    
    protected function findModel($id)
    {
        $model = $this->module->getService('product');
        
        if (($model = $model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
