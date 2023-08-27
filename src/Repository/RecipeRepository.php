<?php

namespace App\Repository;

use App\BreakException;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @param User $user
     * @return Recipe[]
     */
    public function getAvailableRecipes(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.isPublic = true')
            ->orWhere('r.author = :userID')
            ->setParameter('userID', $user->getId())
            ->orderBy('r.name')
            ->getQuery()
            ->getResult();
    }

    public function getDoableRecipes(User $user): array
    {
        $available = $this->getAvailableRecipes($user);
        $doable = new ArrayCollection();
        $fridge = $user->getFridge();
        foreach ($available as $recipe) {
            try {
                foreach ($recipe->getIngredients() as $ingredient) {
                    $ig = $fridge->getIngredients()->findFirst(function ($key, Ingredient $value) use ($ingredient) {
                        return $value->getType()->getName() == $ingredient->getType()->getName();
                    });
                    if ($ig == null || $ig->getQuantity() < $ingredient->getQuantity()) {
                        throw new BreakException();
                    }
                }
            } catch (BreakException $e) {
                continue;
            }
            $doable->add($recipe);
        }
        return $doable->toArray();
    }

//    /**
//     * @return Recipe[] Returns an array of Recipe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recipe
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
