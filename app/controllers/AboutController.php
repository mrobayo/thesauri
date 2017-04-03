<?php

namespace Thesaurus\Controllers;

/**
 *
 * @author mrobayo
 */
class AboutController extends \ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Acerca de');
        parent::initialize();
    }

    public function indexAction()
    {
    }
}
