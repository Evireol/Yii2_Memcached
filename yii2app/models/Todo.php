<?php
namespace app\models;

use Yii;

class Todo
{

    const STATUSES = ['pending', 'in_progress', 'completed'];

    public static function add($text)
    {
        $file = Yii::getAlias('@app/runtime/todo.json');
        $todos = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $todos[] = [
            'id' => time(),
            'text' => $text,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        file_put_contents($file, json_encode($todos, JSON_PRETTY_PRINT));
        return end($todos);
    }

    public static function getAll()
    {
        $file = Yii::getAlias('@app/runtime/todo.json');
        return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    }

    public static function getById($id)
    {
        $todos = self::getAll();
        foreach ($todos as $todo) {
            if (isset($todo['id']) && (string)$todo['id'] === (string)$id) {
                return $todo;
            }
        }
        return null;
    }

    public static function updateStatus($id, $status)
    {
        if (!in_array($status, self::STATUSES)) {
            return ['error' => 'Invalid status. Allowed: ' . implode(', ', self::STATUSES)];
        }

        $file = Yii::getAlias('@app/runtime/todo.json');
        if (!file_exists($file)) {
            return ['error' => 'No tasks found'];
        }

        $todos = json_decode(file_get_contents($file), true);
        $found = false;

        foreach ($todos as &$todo) {
            if ((string)$todo['id'] === (string)$id) {
                $todo['status'] = $status;
                $todo['updated_at'] = date('Y-m-d H:i:s');
                $found = true;
                break;
            }
        }

        if (!$found) {
            return ['error' => 'Task not found'];
        }

        file_put_contents($file, json_encode($todos, JSON_PRETTY_PRINT));
        return ['success' => true, 'task' => $todo];
    }

}