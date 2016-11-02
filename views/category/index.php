<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use zpearl\shop\models\Category;
use kartik\export\ExportMenu;

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;

\zpearl\shop\assets\BackendAsset::register($this);
?>
<div class="category-index">
    <div class="shop-menu">
        <?=$this->render('../parts/menu');?>
    </div>
    
    <div class="row">
        <div class="col-md-2">
            <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-4">
            <?php
            $gridColumns = [
                'id',
                'name',
            ];

            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns
            ]);
            ?>
        </div>
    </div>

    <br style="clear: both;"></div>
    
    <ul class="nav nav-pills">
        <li role="presentation" <?php if(yii::$app->request->get('view') == 'tree' | yii::$app->request->get('view') == '') echo ' class="active"'; ?>><a href="<?=Url::toRoute(['category/index', 'view' => 'tree']);?>">Деревом</a></li>
        <li role="presentation" <?php if(yii::$app->request->get('view') == 'list') echo ' class="active"'; ?>><a href="<?=Url::toRoute(['category/index', 'view' => 'list']);?>">Списком</a></li>
    </ul>
    
    <br style="clear: both;">
    <?php
    if(isset($_GET['view']) && $_GET['view'] == 'list') {
        $categories = \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 55px;']],
                'name',
                [
                    'attribute' => 'image',
                    'format' => 'image',
                    'filter' => false,
                    'content' => function ($image) {
                        if($image = $image->getImage()->getUrl('50x50')) {
                            return "<img src=\"{$image}\" class=\"thumb\" />";
                        }
                    }
                ],
                [
                    'attribute' => 'parent_id',
                    'filter' => Html::activeDropDownList(
                        $searchModel,
                        'parent_id',
                        Category::buildTextTree(),
                        ['class' => 'form-control', 'prompt' => 'Категория']
                    ),
                    'value' => 'parent.name'
                ],
                ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',  'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 125px;']],
            ],
        ]);
    } else {
        $categories = \pistol88\tree\widgets\Tree::widget(['model' => new \zpearl\shop\models\Category()]);
    }
    
    echo $categories;
    ?>

</div>
