<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Board;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Service\Root;
use App\Entity\Card;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DashController extends AbstractController
{
  
    public function boards()
    {
        return $this->render('dash/boards.twig');
    }

    public function board($slug)
    {
        $board = $this->getDoctrine()
            ->getRepository(Board::class)
            ->findOneBy(array('slug' => $slug));
        $this->denyAccessUnlessGranted('view', $board);
        return $this->render('dash/board.twig', [
            'board' => $board
        ]);
    }

    public function profile(Request $request)
    {
        return $this->render('dash/profile.twig');
    }
  
  public function pdf(Root $root, Request $request) {
    $file = $request->query->get('filename');
    $path = $root->getRootPath() . "/submissions/" . $file;
    $user = $this->getUser();
    if ($user && strpos($file, 'submission-') !== false) {
      $filename = str_replace(".pdf", "", $file);
      $filename = str_replace("submission-", "", $filename);    
      $filename = explode('-', $filename);
      if($user->getId() == $filename[0]) {
        $doc = $this->getDoctrine()
            ->getRepository(Card::class)
            ->findOneBy(array('id' => $filename[1]));
        if($doc) {
      header('Content-type: application/pdf');
      header('Content-Disposition: inline; filename="' . $file . '"');
      header('Content-Transfer-Encoding: binary');
      header('Content-Length: ' . filesize($path));
      header('Accept-Ranges: bytes');
      return new BinaryFileResponse($path);
        }
        }
    }
    $this->addFlash("danger", "You do not have sufficient privelages to access that file, or it doesn't exist. Please contact support if this problem persists.");
    return $this->redirectToRoute('Home');
  }
}
