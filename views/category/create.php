<?php
use yii\helpers\Html;

$this->title = 'Создать категорию';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\zpearl\shop\assets\BackendAsset::register($this);
?>
<div class="category-create">
    <div class="shop-menu">
        <?=$this->render('../parts/menu');?>
    </div>
    
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
