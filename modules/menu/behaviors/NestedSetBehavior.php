<?php

namespace anda\cms\modules\menu\behaviors;

use wbraganca\behaviors\NestedSetBehavior as Behavior;

/**
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @author Wanderson Bragança <wanderson.wbc@gmail.com>
 */
class NestedSetBehavior extends Behavior
{
    public $typeAttribute = 'type';

    public $moduleIdAttribute = 'module_id';

    public $moduleRecordIdAttribute = 'module_record_id';

    public $urlAttribute = 'url';
}
