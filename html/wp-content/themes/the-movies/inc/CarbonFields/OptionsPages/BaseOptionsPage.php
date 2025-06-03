<?php

namespace WpTheme\CarbonFields\OptionsPages;

use Carbon_Fields\Container\Container;

abstract class BaseOptionsPage {

    protected string $pageTitle;
    protected string $pageSlug;
    protected string|null $parentSlug = null;

    public function register() {
        if(!$this->pageSlug || strlen(trim($this->pageSlug)) == 0) {
            $this->pageSlug = sanitize_title($this->pageTitle);
        }

        if($this->parentSlug) {
            Container::make("theme_options", $this->pageTitle)
                ->set_page_file($this->pageSlug)
                ->set_page_menu_parent($this->parentSlug)
                ->add_fields($this->fields());
        } else {
            Container::make("theme_options", $this->pageTitle)
                ->set_page_file($this->pageSlug)
                ->add_fields($this->fields());

            add_action("admin_footer-toplevel_page_" . $this->pageSlug, [$this, "appendContent"]);
        }
    }

    abstract public function fields();

    public function appendContent() {
        ?>
        <div id="appended-content" style="display:none;">
            <?php $this->afterForm(); ?>
        </div>
        <script type="text/javascript">
            (function(){
                const customHtml = document.getElementById("appended-content");
                if(customHtml) {
                    const wpBody = document.getElementById("wpbody-content");
                    if(wpBody) {
                        wpBody.appendChild(customHtml);
                        customHtml.style.display = "block";
                    }
                }
            })();
        </script>
        <?php
    }

    public function afterForm() {}

}
