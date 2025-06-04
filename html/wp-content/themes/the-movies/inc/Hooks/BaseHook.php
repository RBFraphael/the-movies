<?php

namespace WpTheme\Hooks;

abstract class BaseHook {

    protected string $hook;
    protected string $type;
    protected int $priority = 10;
    protected int $accepted_args = 1;

    abstract public function run(...$args);

    public function register() {
        if($this->type == "action"){
            add_action($this->hook, [$this, "run"], $this->priority, $this->accepted_args);
        } else if($this->type == "filter"){
            add_filter($this->hook, [$this, "run"], $this->priority, $this->accepted_args);
        }
    }
}
