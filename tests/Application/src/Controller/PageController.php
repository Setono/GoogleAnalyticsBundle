<?php

declare(strict_types=1);

namespace App\Controller;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\PurchaseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('', name: 'app_home')]
    public function index(MessageBusInterface $eventBus): Response
    {
        $event = PurchaseEvent::create('T-1234')->setServerSide(false);
        $eventBus->dispatch($event);

        return $this->render('page/index.html.twig');
    }
}
