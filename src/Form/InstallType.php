<?php


namespace App\Form;

use App\Entity\Tag;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class InstallType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("username", TextType::class)
            ->add("email", TextType::class)
            ->add("plainPassword", RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => "Password"],
                'second_options' => ['label' => "Password Confirmation"]
            ])
            ->add('generateArticles', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Generate dummy articles'
            ])
            ->add("Save", SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-full'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }

}
