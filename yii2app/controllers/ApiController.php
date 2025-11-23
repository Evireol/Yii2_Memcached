<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\models\Todo;

class ApiController extends Controller
{
    const CACHE_KEY_TODO_LIST = 'todo_list';

    public function beforeAction($action)
    {
        if (in_array($action->id, ['add-todo', 'list', 'view', 'update-status', 'delete'])) {
            $this->enableCsrfValidation = false;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    // Вспомогательный метод: сброс кэша списка
    private function invalidateTodoListCache()
    {
        Yii::$app->cache->delete(self::CACHE_KEY_TODO_LIST);
    }

    public function actionAddTodo()
    {
        $todo = new Todo();
        $todo->load(Yii::$app->request->post(), '');
        if ($todo->validate()) {
            $todo->save();
            $this->invalidateTodoListCache(); // ← Сброс кэша
            return ['success' => true, 'task' => $todo];
        }
        return ['success' => false, 'errors' => $todo->errors];
    }

    public function actionList()
    {
        $cache = Yii::$app->cache;
        $data = $cache->get(self::CACHE_KEY_TODO_LIST);
    
        if ($data === false) {
            // Кэш промах — читаем из БД
            Yii::info('Cache MISS: loading from DB', __METHOD__);
            $data = Todo::find()->asArray()->all();
            $cache->set(self::CACHE_KEY_TODO_LIST, $data, 300);
        } else {
            Yii::info('Cache HIT: loaded from cache', __METHOD__);
        }
    
        return $data;
    }

    public function actionView($id)
    {
        $todo = Todo::findOne($id);
        if (!$todo) {
            throw new NotFoundHttpException('Task not found');
        }
        return $todo;
    }

    public function actionUpdateStatus($id)
    {
        $todo = Todo::findOne($id);
        if (!$todo) {
            throw new NotFoundHttpException('Task not found');
        }
        $status = Yii::$app->request->post('status');
        if (!in_array($status, Todo::STATUSES)) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'Invalid status'];
        }
        $todo->status = $status;
        $todo->save(false);
        $this->invalidateTodoListCache(); // ← Сброс кэша
        return ['success' => true, 'task' => $todo];
    }

    public function actionDelete($id)
    {
        $todo = Todo::findOne($id);
        if (!$todo) {
            throw new NotFoundHttpException('Task not found');
        }
        $todo->delete();
        $this->invalidateTodoListCache(); // ← Сброс кэша
        return ['success' => true];
    }
}