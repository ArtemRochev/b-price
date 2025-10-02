<?php declare(strict_types = 1);

namespace App\Controller;

use App\Constraint\PairConstraint;
use App\DTO\ExchangePair;
use App\Repository\RateHistoryRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/rates', methods: ['GET'])]
final class RateController extends AbstractController
{
    public function __construct(private ValidatorInterface $validator) {}

    #[Route('/last-24h')]
    public function last24h(Request $request, RateHistoryRepository $repository): JsonResponse
    {
        $errors = $this->validator->validate($request->query->get('pair'),
            [
                new NotBlank(),
                new PairConstraint(),
            ]
        );

        if ($errors->count()) {
            return new JsonResponse(['status' => 'error'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

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
        $errors = $this->validator->validate($request->query->get('pair'),
            [
                new NotBlank(),
                new PairConstraint(),
            ]
        );

        if ($errors->count()) {
            return new JsonResponse(['status' => 'error'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

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
