<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;

$this->title = 'Производители';
$this->params['breadcrumbs'][] = $this->title;

\zpearl\shop\assets\BackendAsset::register($this);
?>
<div class="producer-index">
    
    <div class="shop-menu">
        <?=$this->render('../parts/menu');?>
    </div>
    
    <div class="row">
        <div class="col-md-2">
            <?= Html::a('Создать производителя', ['create'], ['class' => 'btn btn-success']) ?>
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
    
    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
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
            
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',  'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 125px;']],
        ],
    ]); ?>

</div>
