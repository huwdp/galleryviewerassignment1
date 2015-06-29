<?php

namespace GalleryBundle\Form\DataTransformer;

// src/Acme/TaskBundle/Form/DataTransformer/CategoryToNumberTransformer.php

/*
 * Based on http://symfony.com/doc/current/cookbook/form/data_transformers.html
 * Accessed 29/05/2015
 */



use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\TaskBundle\Entity\Issue;

class CategoryToNumberTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (category) to a string (number).
     *
     * @param  Category|null $issue
     * @return string
     */
    public function transform($category)
    {
        if (null === $category) {
            return "";
        }
        return $category->getId();
    }

    /**
     * Transforms a string (number) to an object (category).
     *
     * @param  string $number
     *
     * @return Category|null
     *
     * @throws TransformationFailedException if object (category) is not found.
     */
    public function reverseTransform($number)
    {
        if (!$number) {
            return null;
        }

        $category = $this->om
            ->getRepository('GalleryBundle:Category')
            ->findOneBy(array('id' => $number->getCategory()))
        ;

        if (null === $category) {
            throw new TransformationFailedException(sprintf(
                'A Category with number "%s" does not exist!',
                $number
            ));
        }

        return $category;
    }
}
