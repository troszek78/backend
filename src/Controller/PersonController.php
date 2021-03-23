<?php

namespace App\Controller;

use App\Component\Page\PageUtility;
use App\Entity\Person;
use App\Module\Person\Form\PersonCreateForm;
use App\Module\Person\Form\PersonEditForm;
use App\Module\Person\Form\PersonSearchForm;
use App\Module\Person\Request\CreatePersonRequest;
use App\Module\Person\Table\PersonList;
use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends BasicController
{
    const ROUTE_BASE = 'person';
    /**
     * @Route("/person", name="person")
     */
    public function index(): Response
    {
        //create form
        $form = $this->createForm(
            PersonSearchForm::class,
            null,
            [
                'action' => $this->generateUrl('person_get_list'),
                'method' => 'POST',
            ]
        );

        // create table
        $table = $this->createTable(PersonList::class);

        return $this->render('person/index.html.twig', [
            'form' => $form->createView(),
            'table' => $table
        ]);
    }

    /**
     * Get List
     * @Route("/person/get_list", name="person_get_list")
     * @param Request $request
     * @return Response
     */
    public function getList(Request $request): Response
    {
        // get doctrine manager
        $em = $this->getDoctrine()->getManager();

        // set page
        $page = new PageUtility($request, $em, Person::class, PersonSearchForm::class);

        return $page->getResponse();
    }

    /**
     * @Route("/person/get_autocomplete", name="person_autocomplete_list")
     * @param Request $request
     * @return Response
     */
    public function getAutocomplete(Request $request): Response
    {
        $name = $request->request->get('name');

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Person::class);

        $records = [];
        if ($repository instanceof PersonRepository) {
            $records = $repository->findByName($name);
        }

        $response = new Response(json_encode($records, JSON_FORCE_OBJECT, 10), Response::HTTP_OK);

        return $response;
    }

    /**
     * @Route("/person/add", name="person_add")
     *
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request): Response
    {
        $createPersonRequest = new CreatePersonRequest();

        $form = $this->createForm(PersonCreateForm::class, $createPersonRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = new Person();
            $person->setLogin($createPersonRequest->login);
            $person->setLName($createPersonRequest->l_name);
            $person->setFName($createPersonRequest->f_name);
            $person->setState($createPersonRequest->state);

            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            $this->addFlash('success', 'person.created_successfully');

            return $this->redirectToRoute('person');
        }

        return $this->render('person/add.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @param int $id
     * @Route("/person/view/{id}", name="person_view")
     * @return Response
     */
    public function viewAction(int $id): Response
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);

        return $this->render('person/view.html.twig', [
            'person' => $person,
        ]);
    }

    /**
     * @Route("/person/edit/{id}", name="person_edit")
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function editAction(int $id, Request $request): Response
    {
        $product = $this->getDoctrine()->getRepository(Person::class)->find($id);

        $createRequest = new CreatePersonRequest();

        $form = $this->createForm(PersonEditForm::class, $createRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setLogin($createRequest->login);
            $product->setLName($createRequest->l_name);
            $product->setFName($createRequest->f_name);
            $product->setState($createRequest->state);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'product.created_successfully');

            return $this->redirectToRoute('person');
        } else {
            $form = $this->createForm(PersonEditForm::class, $product);
        }

        return $this->render('person/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
