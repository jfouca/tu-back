<?php

namespace Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints as Assert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Bundle\MemberBundle\Entity\Member;
use Bundle\MemberBundle\Entity\Message;

class RegistrationController extends Controller
{
    /**
     * @Route("/register")
     * @Method("POST")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Register a new beta member with the given email address",
     *  requirements={
     *      {"name"="email", "dataType"="string", "required"=true, "description"="email address"}
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         400="Returned when the email given is not valid"
     *   }
     * )
     */
    public function registerAction(Request $request)
    {
        $email = $request->request->get('email');
        $member = new Member();
        $member->setEmail($email);
        $errors = $this->get('validator')->validate($member);
        if (count($errors) > 0) {
            return new JsonResponse('Member not valid', Response::HTTP_BAD_REQUEST);
        }
        $this->getDoctrine()->getManager()->persist($member);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse('ok', Response::HTTP_OK);
    }

    /**
     * @Route("/contact")
     * @Method("POST")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Saves a new contact message",
     *  requirements={
     *      {"name"="email", "dataType"="string", "required"=true, "description"="email address"},
     *      {"name"="title", "dataType"="string", "required"=true, "description"="title of the message"},
     *      {"name"="body", "dataType"="string", "required"=true, "description"="body of the message"}
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         400="Returned when the email given is not valid"
     *   }
     * )
     */
    public function contactAction(Request $request)
    {
        $message = new Message();
        $message->setEmail($request->request->get('email'));
        $message->setTitle($request->request->get('title'));
        $message->setBody($request->request->get('body'));

        $errors = $this->get('validator')->validate($message);
        if (count($errors) > 0) {
            return new JsonResponse('Message not valid', Response::HTTP_BAD_REQUEST);
        }
        $this->getDoctrine()->getManager()->persist($message);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse('ok', Response::HTTP_OK);
    }
}
