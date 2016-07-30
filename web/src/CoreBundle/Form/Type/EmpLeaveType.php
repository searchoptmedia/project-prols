<?php

namespace CoreBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EmpLeaveType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'CoreBundle\Model\EmpLeave',
        'name'       => 'empleave',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('request');
        $builder->add('status');
        $builder->add('dateStarted');
        $builder->add('dateEnded');
        $builder->add('empAccId');
        $builder->add('listLeaveTypeId');
    }
}
