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
        $api = file_get_contents('https://calendrier.api.gouv.fr/jours-feries/metropole.json');
        $feries = array();
        foreach (json_decode($api) as $date=>$name){
            $feries[] = array(
                'title' => $name,
                'start' => $date,
                'end' => $date,
                'display' => 'background'
            );
        }


        $events = array();
        foreach ($eventRepository->findAll() as $event) {
            $events[] = array(
                'title' => $event->getTitle(),
                'start' => $event->getDate()->format('Y-m-d'),
                'end' => $event->getDateEnd()->format('Y-m-d'),
                'allDay' => 1,
                'user' => $event->getUser()->getId(),
                'url' => 'event/'.$event->getId(),
                'editable' => false
            );
        }

        ;
        $json = json_encode(array_merge($events, $feries));
        $filesystem = new Filesystem();

        $filesystem->dumpFile('assets/calendar/json/events.json', $json);
        return $this->render('calendar/calendar.html.twig');
    }
}
