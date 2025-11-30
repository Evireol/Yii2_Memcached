<?php
namespace app\commands;

use yii\console\Controller;
use app\models\Todo;

class SeedController extends Controller
{
    public function actionIndex()
    {
        Todo::deleteAll(); // опционально
        $tasks = [
            ['text' => 'Обновить данные', 'status' => 'pending'],
            ['text' => 'Зафиксировать статус', 'status' => 'in_progress'],
            ['text' => 'Отправить отчёт', 'status' => 'completed'],
        ];
        foreach ($tasks as $task) {
            $model = new Todo();
            $model->text = $task['text'];
            $model->status = $task['status'];
            $model->save();
        }
        $this->stdout(" Загружено " . count($tasks) . " задач.\n");
    }
}