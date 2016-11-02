<?php
use yii\helpers\Html;

$this->title = 'Добавить модификацию';
\zpearl\shop\assets\ModificationConstructAsset::register($this);
?>
<div class="product-modification-create">

    <?= $this->render('_form', [
        'model' => $model,
        'productModel' => $productModel,
        'module' => $module,
    ]) ?>

</div>
