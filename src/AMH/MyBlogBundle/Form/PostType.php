<?php
namespace AMH\MyBlogBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form used for sign in.
 *
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class PostType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text')->add('text', 'text');
    }

    public function getName()
    {
        return 'post_add';
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AMH\MyBlogBundle\Entity\Blog\Post',
            )
        );
    }
}

?>
