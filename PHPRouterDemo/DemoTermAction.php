<?php

namespace PHPRouterDemo;

use OLOG\MaskActionInterface;

class DemoTermAction implements MaskActionInterface {

    protected $term_id;

    public function __construct($term_id) {
        $this->term_id = $term_id;
    }

    static public function mask() {
        return '/term/(\d+)';
    }

    public function url() {
        return '/term/' . $this->term_id;
    }

    public function action() {
        echo '<div>TERM ' . $this->term_id . '</div>';
    }

}
