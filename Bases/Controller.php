<?php

namespace Core\Bases;

use Core\Utils\Html;
use Core\Utils\Logger;
use Core\Utils\Session;

abstract class Controller
{

    public static string $views_dir = __DIR__ . "/../../app/Views";

    public \stdClass $view;
    public array|false $errors = false, $inputs = false, $success = false;

    private string $layoutPath, $viewPath;

    public function __construct()
    {
        $this->view = new \stdClass;
        $this->view->header = true;
        $this->view->footer = true;
        if (Session::has('errors')) {
            $this->errors = Session::get('errors');
            Session::remove('errors');
        }
        if (Session::has('inputs')) {
            $this->inputs = Session::get('inputs');
            Session::remove('inputs');
        }
        if (Session::has('success')) {
            $this->success = Session::get('success');
            Session::remove('success');
        }
    }

    public function addError($error, $autoPrint = false) {
        $this->errors[] = $error;
        if ($autoPrint)
            echo Html::error($error, "Error");
        return $this;
    }

    public function showErrors()
    {
        if ($this->errors) {
            echo "<div class='alert alert-danger alert-dismissible' role='alert'>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
        </button>";
            foreach ($this->errors as $msg)
                echo "<p><i class='glyphicon glyphicon-alert'></i> {$msg}></p>";
            echo "</div>";
        }
    }

    public function showSuccess()
    {
        if ($this->success) {
            echo "<div class='alert alert-success alert-dismissible' role='alert'>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
        </button>";
            foreach ($this->success as $msg)
                echo "<p><i class='glyphicon glyphicon-alert'></i> {$msg}></p>";
            echo "</div>";
        }
    }

    public function renderView($path, $layout = "system/layout", $extension = GENERAL_EXTENSION)
    {
        if (!isset($this->view->title)) {
            $et = explode("/", $path);
            $this->view->title = $et[count($et) - 1];
        }
        $this->viewPath = $path;
        $this->layoutPath = $layout;
        if (is_null($layout)) {
            $this->viewContent($extension);
        } else {
            $this->layout($extension);
        }
    }

    private function viewContent($extension)
    {
        $file = self::$views_dir . "/{$this->viewPath}." . $extension;
        if (file_exists($file))
            require_once $file;
        else
            Logger::error("View path not found");
    }

    private function layout($extension)
    {
        $file = self::$views_dir . "/{$this->layoutPath}.{$extension}";
        if (file_exists($file)) {
            require_once $file;
        } else {
            Logger::error("Layout path not found");
        }
    }

    public function setTitle($title)
    {
        $this->view->title = $title;
    }

    public function getTitle(): string
    {
        return $this->view->title;
    }

}