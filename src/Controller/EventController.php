<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use DateTime;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Service\ServiceEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class EventController extends AbstractController
{
    /**
     * @Route("/event/add", name="add_event")
     */
    public function addEvent(EntityManagerInterface $em, Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $rq = $request->request->get('event');
            $event->setTitle($rq['title']);
            //$dateStart = new DateTime($rq['date']['year'] . '-' . $rq['date']['month'] . '-' . $rq['date']['day']);
            //$dateEnd = new DateTime($rq['dateEnd']['year'] . '-' . $rq['dateEnd']['month'] . '-' . $rq['dateEnd']['day']);
            $event->setDate(date_create($rq['date']));
            $event->setDateEnd(date_create($rq['dateEnd']));
            $event->setDescription($rq['description']);
            $event->setUser($this->getUser());
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('agenda');
        }
        

        return $this->render('calendar/addEvent.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/edit/{id}", name="edit_event")
     */
    public function editEvent(int $id, Request $request, EventRepository $eventRepository,ServiceEvent $serviceEvent)
    {
        $event = $eventRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $serviceEvent->save($event);
            $this->addFlash(
                'success',
                'Les opérations ont été ajoutées/modifiées'
            );
            return $this->redirectToRoute('agenda');
        }

        return $this->render("calendar/editEvent.html.twig", [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("event/show/{id}", name="show_event", methods={"GET"})
     */
    public function show(int $id, EventRepository $event): Response
    {
        $events = $event->findOneBy(['id' => $id]);
        return $this->render('calendar/showEvent.html.twig', [
            'event' => $events,
        ]);
    }

    /**
     * @Route("/event/remove/{id}", name="remove_event")
     */
    public function removeEvent(int $id, Request $request, EventRepository $eventRepository,ServiceEvent $serviceEvent)
    {
        $event = $eventRepository->findOneBy(['id' => $id]);
        $serviceEvent->delete($event);

        return $this->redirectToRoute("home");
    }
}