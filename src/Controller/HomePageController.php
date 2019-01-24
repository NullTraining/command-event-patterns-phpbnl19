<?php

declare(strict_types=1);

namespace App\Controller;

use App\Command\CreateUser;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomePageController extends AbstractController
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function index(): Response
    {
        $createUser = new CreateUser(1);

        $this->commandBus->handle($createUser);

        return $this->render('homepage.html.twig', []);
    }
}
