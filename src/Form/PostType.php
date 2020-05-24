<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		
		$builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => 'Длина должна быть не менее  {{ limit }} символов',
                        'maxMessage' => 'Длина должна быть не более  {{ limit }} символов'
                    ]),
                    new Regex([
                        'pattern' => '/^[1-9a-zа-яё,.!?\-\r\n ]+$/ui',
                        'message' => 'Из знаков можно использовать только [.,!?- ]'
                    ])
                ],
            ])
            ->add('text', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 2000,
                        'minMessage' => 'Длина должна быть не менее  {{ limit }} символов',
                        'maxMessage' => 'Длина должна быть не более  {{ limit }} символов'
                    ]),
                    new Regex([
                        'pattern' => '/^[1-9a-zа-яё,.!?\-\r\n ]+$/ui',
                        'message' => 'Из знаков можно использовать только [.,!?- ]'
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
