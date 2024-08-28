<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Video;
use App\Entity\VideoProgress;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\VideoProgressRepository;
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

        // Récupération de la progression de la vidéo pour l'utilisateur connecté
        $progress = $progressRepository->findOneBy(['user' => $user, 'video' => $video]);

        // Passe la progression à la vue, assurez-vous qu'elle est correctement calculée
        $progressValue = $progress ? $progress->getProgress() : 0;

        // Indique si l'utilisateur a démarré la vidéo
        $hasStarted = false;
        if ($progress && $progress->getProgress() > 0 && $progress->getProgress() < $video->getDuration()) {
            $hasStarted = true;
        }

        return $this->render('video/watch.html.twig', [
            'video' => $video,
            'progress' => $progressValue,
            'hasStarted' => $hasStarted
        ]);
    }

    // #[Route('/video/{id}/progress', name: 'video_progress', methods: ['POST'])]
    // #[IsGranted('ROLE_USER')]
    // public function updateProgress(
    //     Video $video,
    //     Request $request,
    //     EntityManagerInterface $em
    // ): JsonResponse {
    //     $user = $this->getUser();
    //     $progressRepository = $em->getRepository(VideoProgress::class);

    //     $progressValue = (float) $request->request->get('progress');

    //     // Ne pas sauvegarder si la progression est à 0
    //     if ($progressValue == 0) {
    //         return new JsonResponse(['status' => 'no progress recorded'], 204);
    //     }

    //     $progress = $progressRepository->findOneBy(['user' => $user, 'video' => $video]);

    //     if (!$progress) {
    //         $progress = new VideoProgress();
    //         $progress->setUser($user);
    //         $progress->setVideo($video);
    //     }

    //     $progress->setProgress($progressValue);
    //     $progress->setLastWatchedAt(new \DateTime());

    //     $em->persist($progress);
    //     $em->flush();

    //     return new JsonResponse(['status' => 'progress saved']);
    // }

    #[Route('/video/{id}/progress', name: 'video_progress', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function updateProgress(
        Video $video,
        Request $request,
        EntityManagerInterface $em,
        VideoProgressRepository $progressRepository
    ): JsonResponse {
        $user = $this->getUser();

        // $progressRepository = $em->getRepository(VideoProgress::class);
        // $userRepository = $em->getRepository(User::class);  // Assurez-vous d'ajouter ce repository si ce n'est pas déjà fait

        $progressValue = (float) $request->request->get('progress');

        $videoDuration = $video->getDuration();  // Assurez-vous que la méthode getDuration() existe dans l'entité Video

        // Ne pas sauvegarder si la progression est à 0
        if ($progressValue == 0) {
            return new JsonResponse(['status' => 'no progress recorded'], 204);
        }

        if ($progressValue < 0) {
            return new JsonResponse(['status' => 'invalid progress value'], 400);
        }

        if ($videoDuration <= 0) {
            return new JsonResponse(['status' => 'invalid video duration'], 400);
        }

        $progress = $progressRepository->findOneBy(['user' => $user, 'video' => $video]);

        if ($progressValue >= $videoDuration) {
            // Si la vidéo est entièrement regardée, supprimer l'enregistrement de progression
            if ($progress) {
                $em->remove($progress);
            }

            // Ajouter la vidéo à la collection watchedVideos de l'utilisateur
            $user = $this->getUser();
            if (!$user->getWatchedVideos()->contains($video)) {
                $user->addWatchedVideo($video);
                $em->persist($user);
            }

        } else {
            // Sinon, mettre à jour ou créer l'enregistrement de progression
            if (!$progress) {
                $progress = new VideoProgress();
                $progress->setUser($user);
                $progress->setVideo($video);
            }

            $progress->setProgress($progressValue);
            $progress->setLastWatchedAt(new \DateTime());

            $em->persist($progress);
        }

        $em->flush();

        return new JsonResponse(['status' => 'progress saved']);
    }


    #[Route('/video/{id}/mark-as-watched', name: 'video_mark_as_watched', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function markAsWatched(Video $video, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user->hasWatchedVideo($video)) {
            $user->addWatchedVideo($video);
            $em->flush();
        }

        return new JsonResponse(['status' => 'Video marked as watched']);
    }
}
