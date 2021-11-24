<?php

namespace Core\Bases;

class View
{

    private false|string $path, $layout, $title = false;
    private Controller $controller;
    private bool $hasHeader, $hasFooter;

    /**
     * @param string $path
     * @param string $layout
     * @param bool $hasHeader
     * @param bool $hasFooter
     * @param bool|string $title
     */
    public function __construct(string $path, string $layout = 'system/layout', bool $hasHeader = true, bool $hasFooter = true, bool|string $title = false)
    {
        $this->path = $path;
        $this->layout = $layout;
        $this->hasHeader = $hasHeader;
        $this->hasFooter = $hasFooter;
        $this->title = $title;
    }

    /**
     * @param false|string $title
     */
    public function setTitle(bool|string $title): void
    {
        $this->title = $title;
    }

    public function hasHeader(bool $has)
    {
        $this->hasHeader = $has;
        $this->controller->view->header = $has;
    }

    public function hasFooter(bool $has)
    {
        $this->hasFooter = $has;
        $this->controller->view->footer = $has;
    }

    /**
     * @param Controller $controller
     */
    public function render(Controller $controller)
    {
        $this->controller = $controller;
        $this->hasHeader($this->hasHeader);
        $this->hasFooter($this->hasFooter);
        if ($this->title !== false) {
            $this->controller->setTitle($this->title);
        }
        $this->controller->renderView($this->path, $this->layout);
    }

}