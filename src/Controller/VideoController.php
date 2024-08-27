<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\VideoProgress;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\VideoProgressRepository;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideoController extends AbstractController
{
    #[Route('/video', name: 'app_video')]
    public function index(
        VideoRepository $videoRepository
    ): Response
    {
        return $this->render('video/index.html.twig', [
            'videos' => $videoRepository->findAll()
        ]);
    }

    #[Route('/video/{id}', name: 'video_watch')]
    #[IsGranted('ROLE_USER')]
    public function video(
        Video $video,
        VideoProgressRepository $progressRepository
    ): Response
    {
        $user = $this->getUser();

        $progress = $progressRepository->findOneBy(['user' => $user, 'video' => $video]);

        return $this->render('video/watch.html.twig', [
            'video' => $video,
            'progress' => $progress ? $progress->getProgress() : 0,
        ]);
    }

    #[Route('/video/{id}/progress', name: 'video_progress', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function updateProgress(
        Video $video,
        Request $request,
        EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        $progressRepository = $em->getRepository(VideoProgress::class);
        $progress = $progressRepository->findOneBy(['user' => $user, 'video' => $video]);

        if (!$progress) {
            $progress = new VideoProgress();
            $progress->setUser($user);
            $progress->setVideo($video);
        }

        $progress->setProgress($request->request->get('progress'));
        $progress->setLastWatchedAt(new \DateTime());

        $em->persist($progress);
        $em->flush();

        return new JsonResponse(['status' => 'progress saved']);
    }
}
