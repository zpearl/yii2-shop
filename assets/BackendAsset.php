<?php
namespace zpearl\shop\assets;

use yii\web\AssetBundle;

class BackendAsset extends AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public $js = [
        'js/scripts.js',
        'js/stickyeah.js',
    ];
    
    public $css = [
        'css/styles.css',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }
}
