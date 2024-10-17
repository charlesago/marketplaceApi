<?php

namespace App\Form;

use App\Entity\CreatedApi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateApiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('requestAmountPerSale')
            ->add('apiKey')
            ->add('docLink')
            ->add('linkToApiUser', TextType::class, [
                'label' => 'URL to your API user creation route',
            ])
            ->add('linkToApiUserDelete', TextType::class, [
                'label' => 'URL to your API user deletion route'
            ])
            ->add('linkToApiRequest', TextType::class, [
                'label' => 'URL to your API requests remaining route'
            ]);
        if (!$options['editKey']) {
            $builder->add('apiKey', TextType::class, [
                'label' => 'API Key',
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreatedApi::class,
            'editKey' => false,
        ]);
        $resolver->setAllowedTypes('editKey', 'bool');
    }
}
