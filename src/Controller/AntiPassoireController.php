<?php

namespace App\Controller;

use App\Form\SearchAntiPassoireType;
use App\Repository\AntiPassoireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AntiPassoireController extends AbstractController
{
    private EntityManagerInterface $em;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger
    )
    {
        $this->em = $em;
        $this->logger = $logger;
    }
    /**
     * @Route("/anti-passoire/{slug}", name="app_antipassoire_show")
     */
    public function show(
        AntiPassoireRepository $antiPassoireRepository,
        string $slug
    ): Response
    {
        try {
            $antiPassoire = $antiPassoireRepository->findOneBy(['slug' => $slug]);

            if ($antiPassoire === null) {
                throw $this->createNotFoundException("Cet anti passoire n'existe pas.");
            }

            $antiPassoire->setDisplayNb($antiPassoire->getDisplayNb() + 1);
            $antiPassoire->setLastDisplay(new \DateTime('NOW', new \DateTimeZone('Europe/Paris')));
            $this->em->persist($antiPassoire);
            $this->em->flush();

            $form = $this->createForm(SearchAntiPassoireType::class, null, [
                'action' => $this->generateUrl('app_home'),
                'method' => 'POST'
            ]);
        } catch(\Exception $exception) {
            $this->logger->error($exception->getMessage());
            $this->logger->debug($exception->getTraceAsString());
        }

        return $this->render('anti_passoire/index.html.twig', [
            'antiPassoire' => $antiPassoire,
            'searcherForm' => $form->createView()
        ]);
    }
}
