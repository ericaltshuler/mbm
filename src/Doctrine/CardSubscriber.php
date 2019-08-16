<?php

namespace App\Doctrine;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Card;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Service\Root;

/**
 * This doctrine event subscriber subscribes to the preUpdate and prePersist events on the Users class.
 * See https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/events.html.
 *
 * @author Brennan Walsh <mail@brennanwal.sh>
 */
class CardSubscriber implements EventSubscriber
{
    private $root;
    private $template;

    public function __construct(
      Root $root,
      \Twig_Environment $template
    ) {
        $this->root = $root;
        $this->template = $template;
    }

    public function getSubscribedEvents()
    {
        return array(Events::preUpdate, Events::preRemove);
    }

    /**
     * If a User is being updated, check if the email or password has changed.
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        // Check if entity being updated is a User.
        if ($entity instanceof Card) {
            // If the user updated their password, encode and update it.
            if ($args->hasChangedField('form') && $entity->getStatus() == true) {
              
              $pdfOptions = new Options();
              $pdfOptions->set('defaultFont', 'Arial');
              $dompdf = new Dompdf($pdfOptions);
              $content = json_decode($entity->getForm(), TRUE);
              dump($content);
              $html = $this->template->render('util/pdf.twig', [
                'content' => $content,
                'title' => $entity->getName(),
              ]);
              $dompdf->loadHtml($html);
              $dompdf->set_option('defaultFont', 'DejaVuSans');
              $dompdf->setPaper('A4', 'portrait');
              $dompdf->render();
              $output = $dompdf->output();        
              $publicDirectory = $this->root->getRootPath() . '/submissions';
              $pdfFilepath = '/submission-' . $entity->getUserId()->getId() . '-' . $entity->getId() . '.pdf';
              $fullpath = $publicDirectory . $pdfFilepath;
              file_put_contents($fullpath, $output);  
              $entity->setFileLink($pdfFilepath);
            }


        }
    }

    /**
     * If a User is being deleted, send them one last email confirming the deletion.
     */
    public function preRemove(LifecycleEventArgs $args)
    {
    }
}
