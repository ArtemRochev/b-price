<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\RateHistory;
use App\Repository\RateHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rates', methods: ['GET'])]
final class RateController extends AbstractController
{
    #[Route('/last-24h')]
    public function index(Request $request, RateHistoryRepository $repository): JsonResponse
    {
        $pair = $request->get('pair');

        [$from, $to] = explode('/', $pair);

        $ratesHistory = $repository->findByPair($from, $to);

        $res = [];

        foreach ($ratesHistory as $history) {
            $res[] = [
                'rate' => $history->getRate(),
                'date' => $history->getDate()->format(DATE_ATOM),
            ];
        }

        return $this->json($res);
    }
}
