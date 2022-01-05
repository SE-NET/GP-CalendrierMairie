<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use DateTime;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        return $this->render('home/index.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/agenda", name="agenda")
     */
    public function agenda(EventRepository $eventRepository): Response
    {
        $allEvents = $eventRepository->findAll();
        $user = null;
        $events = array();
        foreach ($allEvents as $event) {
            $events[] = array(
                'start' => $event->getDate()->format('Y-m-d'),
                'title' => $event->getTitle(),
                'allDay' => $event->getAllDay(),
                'end' => $event->getDateEnd()->format('Y-m-d'),
                'user' => $event->getUser()->getId()
            );
        }

        $json = json_encode($events);
        $filesystem = new Filesystem();

        $filesystem->dumpFile('assets/calendar/json/events.json', $json);
        return $this->render('calendar/calendar.html.twig');
    }
}
