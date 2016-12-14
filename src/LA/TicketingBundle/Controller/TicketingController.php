<?php

namespace LA\TicketingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use LA\TicketingBundle\Entity\Command;
use LA\TicketingBundle\Entity\Ticket;
use LA\TicketingBundle\Form\CommandType;

class TicketingController extends Controller
{
    public function infoAction()
    {
        // Array des différents tarifs
        $prices[] = Ticket::PRICE_FREE;
        $prices[] = Ticket::PRICE_KID;
        $prices[] = Ticket::PRICE_REDUCED;
        $prices[] = Ticket::PRICE_SENIOR;
        $prices[] = Ticket::PRICE_NORMAL;

        // Récupérer les tarifs : 'const'
        return $this->render('LATicketingBundle:Ticketing:info.html.twig', array(
            'prices' => $prices));
    }

    public function commandCreateAction(Request $request)
    {
        // Création d'une instance commande
        $command = new Command();
        // Création du formulaire destiné à hydrater l'instance $command
        $form = $this->get('form.factory')->create(CommandType::class, $command);

        // Test sur l'état du formulaire
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            // Rajout du nombre de tickets à la commande
            $command->setNbTickets();

            // Récupération de l'entity manager
            $em = $this->getDoctrine()->getManager();

            // Récupéraiton de la liste des tickets
            $tickets = $command->getTickets();
            // Montant total de la commande (en centimes)
            $amount = 0;

            // Rajout de la commande aux tickets et calcul du montant
            foreach ($tickets as $ticket) {
                $ticket->setCommand($command);
                $ticket->setPrice();
                $amount += $ticket->getPrice();
            }
            $command->setAmount($amount);

            // Persist de la commande hydratée par le formulaire
            $em->persist($command);

            // Sauvegarde en base
            $em->flush();
       

            // Message de confirmation de commande
            $request->getSession()->getFlashBag()->add('info','Commande valide.');

            // Paiement de la commande :
            return $this->redirectToRoute('la_ticketing_buy', array(
                'id'      => $command->getId(),
                'command' => $command)
            );
        }

        // Création du formulaire de commande
        return $this->render('LATicketingBundle:Ticketing:command_create.html.twig', array('form' => $form->createView()));
    }

    public function commandBuyAction(Command $command, Request $request)
    {
        if ($request->isMethod('POST')) {
            // Configuration de la clé privé
            \Stripe\Stripe::setApiKey("sk_test_cTxtx7HJFSy2rLXArzb0oVt5");

            // Récupération du Token de paiement
            $token = $request->get('stripeToken');
            echo $token;

            // Tentative de transaction
            try {
                $charge = \Stripe\Charge::create(array(
                    'amount'      => $command->getAmount(),
                    'currency'    => 'eur',
                    'source'      => $token,
                    'description' => 'Paiement des billets'
                    ));
            } catch(\Stripe\Error\Card $e) {
                // Retourne l'erreur dans un message Flash
                $request->getSession()->getFlashBag()->add('error','La carte saisie n\'est pas valide');

                // Redirection pour une nouvelle tentative
                return $this->redirectToRoute('la_ticketing_buy', array('id' => $command->getId()));
            }

            // Validation :
            $command->setValid(true);
            $tickets = $command->getTickets();
            foreach ($tickets as $ticket) {
                $ticket->setValidationCode();
            }

            // Persistate en base :
            $em = $this->getDoctrine()->getManager();
            $em->persist($command);
            $em->flush();

            // Confirmation
            $request->getSession()->getFlashBag()->add('info', 'Paiement validé, vous allez recevoir d\'ici quelques secondes un mail de confirmation accompagné des billets');
            // Mail :
            $this->sendMailConfirm($command);

            // Confirmation
            return $this->redirectToRoute('la_ticketing_confirm', array('id' => $command->getId()));
        }

        // Création du formumlaire de paiement + Récap commande
        return $this->render('LATicketingBundle:Ticketing:command_buy.html.twig', array('command' => $command));
    }

    public function commandConfirmAction(Request $request, Command $command)
    {
        // Confirmation de la commande (redirection suite au succès du paiement)
        return $this->render('LATicketingBundle:Ticketing:command_confirm.html.twig', array('command' => $command));
    }

    public function sendMailConfirm(Command $command)
    {
        // Création du mail à destination du propriétaire de la commande
        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmation de la réservation de vos places')
            ->setFrom('xxx@xxx.xxx')
            ->setTo($command->getMail())
            ->setReplyTo('xxx@xxx.xxx')
            ->setBody($this->renderView('LATicketingBundle:Ticketing:command_mail.html.twig', array('command' => $command)))
            ->setContentType('text/html')
        ;

        // Envoi du message
        $this->get('mailer')->send($message);

        return;
    }

    public function generatePdfAction($id, Ticket $ticket)
    {
        // Récupération du code de validation du billet
        $validCode = $ticket->getValidationCode();

        // Création du PDF
        $html2pdf = new \HTML2PDF('P','A4','fr');
        $html2pdf->writeHTML('<qrcode value="' . $validCode . '" ec="H" style="width: 50mm; background-color: white; color: black;">' . $validCode . '</qrcode>');

        // Retour du PDF au navigateur
        $html2pdf->Output($ticket->getId() . '.pdf');
    }

    public function statsAction()
    {
        // Ticket Repository
        $repoTicket = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('LATicketingBundle:Ticket')
        ;

        // Récupération des tickets vendus aujourd'hui
        $todayTickets = $repoTicket->ticketsPerDay();

        // Affichage des stats
        return $this->render('LATicketingBundle:Ticketing:stats.html.twig', array(
            'tickets' => $todayTickets
            )
        );
    }

}
