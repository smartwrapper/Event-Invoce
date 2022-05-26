<?php

namespace Classiebit\Eventmie\FormFields;
use Facades\Classiebit\Eventmie\Eventmie;

use TCG\Voyager\FormFields\AbstractHandler;

use Classiebit\Eventmie\Models\User;

class OrganiserDropdown extends AbstractHandler
{
    protected $codename = 'organiser';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        $this->user         = new User;
        // get organisers
        $organisers         = $this->user->get_organisers(); 
        
        $view = 'eventmie::vendor.voyager.formfields.organiser';

        return Eventmie::view($view, [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'organisers' => $organisers,

        ]);
    }
}