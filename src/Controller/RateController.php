<?php declare(strict_types = 1);

namespace App\Controller;

use App\DTO\ExchangePair;
use App\Repository\RateHistoryRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rates', methods: ['GET'])]
final class RateController extends AbstractController
{
    #[Route('/last-24h')]
    public function last24h(Request $request, RateHistoryRepository $repository): JsonResponse
    {
        $pair = new ExchangePair($request->get('pair'));

        $ratesHistory = $repository->findByPairLast24h($pair->getFrom(), $pair->getTo());

        $res = [];

        foreach ($ratesHistory as $history) {
            $res[] = [
                'rate' => $history->getRate(),
                'date' => $history->getDate()->format(DATE_ATOM),
            ];
        }

        return $this->json($res);
    }

    #[Route('/day')]
    public function day(Request $request, RateHistoryRepository $repository): JsonResponse
    {
        $pair = new ExchangePair($request->get('pair'));

        $ratesHistory = $repository->findByPairDay($pair->getFrom(), $pair->getTo(), new DateTime($request->get('date')));

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
