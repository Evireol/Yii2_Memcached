<?php
// controllers/CacheController.php
namespace app\controllers;

use Yii;
use yii\web\Controller;

class CacheController extends Controller
{
    public function actionIndex()
    {
        $key = 'test';
        $value = Yii::$app->cache->get($key);

        if ($value === false) {
            $value = 'Cached at: ' . date('c');
            Yii::$app->cache->set($key, $value, 20);
        }

        return $this->renderContent("<h1>$value</h1>");
    }
}