<?php


namespace App\Form;


use App\Entity\Article;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("title", TextType::class)
            ->add("text", TextareaType::class, ['label' => "Body"])
            ->add(
                'thumbnail',
                FileType::class,
                [
                    'label' => 'Article Thumbnail (PNG, JPG, GIF)',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File(
                            [
                                'maxSize' => '4m',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/png',
                                    'image/gif',
                                ],
                                'mimeTypesMessage' => 'Please upload a valid thumbnail',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'tags',
                EntityType::class,
                array(
                    'class' => Tag::class,
                    'multiple' => true,
                    'expanded' => true,
                )
            )
            ->add("save", SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Article::class,
            ]
        );
    }

}