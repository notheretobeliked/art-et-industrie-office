<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class test extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $test = new FieldsBuilder('test');

        $test
            ->setLocation('post_type', '==', 'post');

        $test
            ->addRepeater('items')
                ->addText('item')
            ->endRepeater();

        return $test->build();
    }
}
