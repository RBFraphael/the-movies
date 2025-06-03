<?php

namespace WpTheme\CarbonFields\Blocks;

use Carbon_Fields\Block;

abstract class BaseBlock {

    protected string $blockName;

    public function register() {
        Block::make($this->blockName)
            ->add_fields($this->fields())
            ->set_render_callback(
                function ($fields, $attributes, $inner_blocks) {
                    $this->render($fields, $attributes, $inner_blocks);
                }
            );
    }

    abstract public function fields();
    
    abstract public function render($fields, $attributes, $inner_blocks);

}
