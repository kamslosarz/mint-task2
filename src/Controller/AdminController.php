<?php


namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/users/{page}", name="admin_users")
     * @param int $page
     * @param UserRepository $userRepository
     * @return Response
     */
    public function users(UserRepository $userRepository, int $page = 1): Response
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('app_login');
        }

        $query = $userRepository->all();

        $pageSize = 10;
        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $pageSize);
        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($page - 1)) // set the offset
            ->setMaxResults($pageSize); // set the limit

        return $this->render('admin/index.html.twig', [
            'users' => $paginator,
            'totalItems' => $totalItems,
            'pagesCount' => $pagesCount,
            'currentPage' => $page
        ]);
    }

    /**
     * @Route("/admin/disable/{id}", name="admin_disable")
     * @param int $id
     * @param UserRepository $userRepository
     * @return Response
     */
    public function disableUser(int $id, UserRepository $userRepository): Response
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->find($id);
        $user->setEnabled(false);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        $this->addFlash('success', sprintf('User %s was disabled', $user->getUsername()));

        return $this->redirectToRoute('admin_users');
    }
}