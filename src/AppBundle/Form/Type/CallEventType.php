<?php

namespace AppBundle\Form\Type;

use AppBundle\DBAL\Types\CallType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CallEventType extends AbstractEventType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('type', ChoiceType::class, [
                'choices' => CallType::getChoices(),
                'property_path' => 'callType',
            ])
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\CallEvent',
        ]);
    }
}
