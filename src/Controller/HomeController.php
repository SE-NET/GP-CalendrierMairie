<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use DateTime;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/add", name="add_event")
     */
    public function addEvent(EntityManagerInterface $em, Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $rq = $request->request->get('event');
            $event->setTitle($rq['title']);
            $dateStart = new DateTime($rq['date']['year'] . '-' . $rq['date']['month'] . '-' . $rq['date']['day']);
            //$dateEnd = new DateTime($rq['dateEnd']['year'] . '-' . $rq['dateEnd']['month'] . '-' . $rq['dateEnd']['day']);
            $event->setDate($dateStart);
            $event->setAllDay($rq['allDay']);
            $em->persist($event);
            $em->flush();
        }
        

        return $this->render('calendar/addCalendar.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
