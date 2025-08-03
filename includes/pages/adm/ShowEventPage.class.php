<?php

class ShowEventPage extends AbstractAdminPage
{
    public static $requireModule = 0;

    function __construct()
    {
    }

    function show()
    {
        $this->tplObj->assign_vars([
            'pageTitle' => 'Gestion des Évènements'
        ]);
        $this->display('page.event.default.tpl');
    }
}
