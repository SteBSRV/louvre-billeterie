<?php
// src/LA/TicketingBundle/Controller/TicketingController

namespace LA\TicketingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use LA\TicketingBundle\Entity\Order;
use LA\TicketingBundle\Entity\Ticket;
use LA\TicketingBundle\Form\Type\OrderType;

class TicketingController extends Controller
{
    public function infoAction()
    {
        // Array des différents tarifs
        $prices = array(
            Ticket::PRICE_FREE => 'Gratuit (jusqu\'à 4ans)',
            Ticket::PRICE_KID => 'Enfant (jusqu\'à 12 ans)',
            Ticket::PRICE_REDUCED => 'Réduit (nécessite un justificatif)',
            Ticket::PRICE_SENIOR => 'Sénior (à partir de 60 ans)',
            Ticket::PRICE_NORMAL => 'Normal',
            )
        ;

        // Récupérer les tarifs : 'const'
        return $this->render('LATicketingBundle:Ticketing:info.html.twig', compact('prices'));
    }

    public function orderCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ticketRepo = $em->getRepository('LATicketingBundle:Ticket');
        $nbTicketsToday = $ticketRepo->getNbTicketsPerDay();

        if ($nbTicketsToday >= 1000) {
            $request->getSession()->getFlashBag()->add('warning','Tous les tickets pour aujourd\'hui ont été vendus.');
            return $this->render('LATicketingBundle:Ticketing:order_full.html.twig');
        }
        // Création d'une instance order
        $order = new Order();
        // Création du formulaire destiné à hydrater l'instance $order
        $form = $this->get('form.factory')->create(OrderType::class, $order);
        // Test sur l'état du formulaire
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $order->setTicketsOrder();
            // Persist de la commande hydratée par le formulaire
            $em->persist($order);
            // Sauvegarde en base
            $em->flush();
            // Message de confirmation de commande
            $request->getSession()->getFlashBag()->add('info','Commande valide.');

            $id = $order->getId();
            // Paiement de la commande :
            return $this->redirectToRoute('la_ticketing_buy', compact('id','order'));
        }

        // Création du formulaire de commande
        return $this->render('LATicketingBundle:Ticketing:order_create.html.twig', array('form' => $form->createView()));
    }

    public function orderBuyAction(Order $order, Request $request)
    {
        if ($request->isMethod('POST')) {
            // Récupération de l'adresse email pour l'envoi des billets
            $mail = $request->get('stripeEmail');
            $order->setMail($mail);

            // Configuration de la clé privé
            \Stripe\Stripe::setApiKey("sk_test_cTxtx7HJFSy2rLXArzb0oVt5");

            // Récupération du Token de paiement
            $token = $request->get('stripeToken');

            // Tentative de transaction
            try {
                $charge = \Stripe\Charge::create(array(
                    'amount'      => $order->getTotalAmount(),
                    'currency'    => 'eur',
                    'source'      => $token,
                    'description' => 'Paiement des billets'
                    ));
            } catch(\Stripe\Error\Card $e) {
                // Retourne l'erreur dans un message Flash
                $request->getSession()->getFlashBag()->add('error','La carte saisie n\'est pas valide');

                $id = $order->getId();
                // Redirection pour une nouvelle tentative
                return $this->redirectToRoute('la_ticketing_buy', compact('id'));
            }

            // Validation :
            $order->setPaid(true);

            // Persistate en base :
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            // Confirmation
            $request->getSession()->getFlashBag()->add('success', 'Paiement validé, vous allez recevoir d\'ici quelques secondes un mail de confirmation accompagné des billets');
            // Mail :
            $this->get('la_ticketing.mailer')->sendOrderSuccess($order);

            $id = $order->getId();
            // Confirmation
            return $this->redirectToRoute('la_ticketing_confirm', compact('id'));
        }

        // Création du formumlaire de paiement + Récap commande
        return $this->render('LATicketingBundle:Ticketing:order_buy.html.twig', compact('order'));
    }

    public function orderConfirmAction(Request $request, Order $order)
    {
        // Confirmation de la commande (redirection suite au succès du paiement)
        return $this->render('LATicketingBundle:Ticketing:order_confirm.html.twig', compact('order'));
    }

    public function generatePdfAction($id, Ticket $ticket)
    {
        $order = $ticket->getOrder();

        $html = $this->renderView('LATicketingBundle:PDF:ticket.html.twig', compact('ticket','order'));

        // Création du PDF
        $html2pdf = new \HTML2PDF('P',array('58','208'),'fr');
        $html2pdf->writeHTML($html);

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
        $nbTicketsToday = $repoTicket->getNbTicketsPerDay();

        // Affichage des stats
        return $this->render('LATicketingBundle:Ticketing:stats.html.twig', compact('nbTicketsToday')
        );
    }

}
