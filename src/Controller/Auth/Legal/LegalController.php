<?php

namespace App\Controller\Auth\Legal;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    #[Route('/legal', name: 'legal')]
    public function termsAndConditions()
    {
        return $this->render('legal/terms-and-conditions.html.twig');
    }
}
