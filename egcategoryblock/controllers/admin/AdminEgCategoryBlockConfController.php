<?php

class AdminEgCategoryBlockConfController extends ModuleAdminController
{
    public function initContent()
    {
        if (!$this->viewAccess()) {
            $this->errors[] = Tools::displayError('You do not have permission to view this.');
            return;
        }

        $idTab = (int) Tab::getIdFromClassName('AdminModules');
        $idEmployee = (int) $this->context->employee->id;
        $token = Tools::getAdminToken('AdminModules'.$idTab.$idEmployee);
        Tools::redirectAdmin('index.php?controller=AdminModules&configure=egcategoryblock&token='.$token);
    }
}
