<?php

use Stripe\Checkout\Session;
use Stripe\Event;
use Stripe\Stripe;

defined('BASEPATH') or exit('No direct script access allowed');

class Stripe_Model extends CI_Model
{

    /**
     * Generates a new stripe session with a customer email.
     *
     * @param string $customer_email The email address of the session.
     *
     * @return string The ID of the newly created session.
     */
    function generateNewSessionId($customer_email, $eventData)
    {
        Stripe::setApiKey(STRIPE_PRIVATE_KEY);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                // Change it to $eventData->title
                'name' => $eventData->title,
                'description' => $eventData["tagline"],
                'images' => [(base_url() . 'assets/images/ASPA_logo.png')],
                'amount' => (float) $eventData["price"] * 100,
                'currency' => 'NZD',
                'quantity' => 1,
            ]],
            'success_url' => base_url() . 'EnrollmentForm/StripePaymentSuccessful?session_id={CHECKOUT_SESSION_ID}?event_id=' . $eventData['id'] . '',
            'cancel_url' => base_url(),
            'customer_email' => $customer_email,

        ]);
        $stripeSession = array($session);
        return ($stripeSession[0]['id']);
    }

    /**
     * This function checks if the stripe session has made it's payment successfully.
     *
     * @param string $sessionId The session id of this current payment session.
     *
     * @return bool If the user has paid.
     */
    function checkPayment($sessionId)
    {
        $hasPaid = False;

        Stripe::setApiKey(STRIPE_PRIVATE_KEY);

        $events = Event::all([
            'type' => 'checkout.session.completed',
            'created' => [
                // Check for events created in the last 3 minutes
                'gte' => time() - 1 * 10 * 60,
            ],
        ]);

        foreach ($events->autoPagingIterator() as $event) {
            $session = $event->data->object;

            // Check if a paid session ID matches with current session ID
            if ($session->id == $sessionId) {
                $hasPaid = True;
                break;
            }
        }

        return $hasPaid;
    }

    /**
     * Returns the customers email given a session ID.
     *
     * @param string $sessionId The session id of the desired email address.
     *
     * @return string $email the email address of a given session.
     */
    function getEmail($sessionId)
    {
        Stripe::setApiKey(STRIPE_PRIVATE_KEY);

        $session_object = Session::retrieve($sessionId);

        return $session_object->customer_email;
    }
}
