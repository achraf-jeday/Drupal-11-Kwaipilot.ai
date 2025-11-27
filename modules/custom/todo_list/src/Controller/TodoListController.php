<?php

namespace Drupal\todo_list\Controller;

use Drupal\Core\Controller\ControllerBase;

class TodoListController extends ControllerBase {
  public function content() {
    $build = [
      '#type' => 'markup',
      '#markup' => $this->getTodoListHtml(),
      '#attached' => [
        'library' => [
          'todo_list/todo_list_assets',
        ],
      ],
    ];

    return $build;
  }

  private function getTodoListHtml() {
    return '
      <div class="todo-container">
        <h2>My Todo List</h2>
        <div class="todo-input-section">
          <input type="text" id="todo-input" placeholder="Add a new task...">
          <button id="add-btn">Add Task</button>
        </div>
        <ul id="todo-list"></ul>
      </div>
    ';
  }
}
