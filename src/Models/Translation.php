<?php

namespace Classiebit\Eventmie\Models;

use Classiebit\Eventmie\Models\Translation;
class Translation extends \TCG\Voyager\Models\Translation
{
    protected $fillable = ['id', 'table_name', 'column_name', 'foreign_key', 'locale', 'value'];
}
