<?php

namespace AppBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class UnusualSolutionEventType extends AbstractEventType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\UnusualSolutionEvent',
        ]);
    }
}
