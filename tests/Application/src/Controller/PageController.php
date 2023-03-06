<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\PurchaseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('', name: 'app_home')]
    public function index(EventDispatcherInterface $eventDispatcher): Response
    {
        $eventDispatcher->dispatch(new ClientSideEvent(PurchaseEvent::create('T-1234')));

        return $this->render('page/index.html.twig');
    }
}
