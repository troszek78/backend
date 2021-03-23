<?php

namespace App\Controller;

use App\Component\Page\PageUtility;
use App\Component\Traits\Converts;
use App\Entity\Person;
use App\Entity\PersonLikeProduct;
use App\Entity\Product;
use App\Module\PersonLikeProduct\Form\CreateForm;
use App\Module\PersonLikeProduct\Form\PersonForm;
use App\Module\PersonLikeProduct\Form\SearchForm;
use App\Module\PersonLikeProduct\Request\CreateRequest;
use App\Module\PersonLikeProduct\Table\PersonLikeProductFullList;
use App\Module\PersonLikeProduct\Table\ProductList;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Exception;
use Symfony\Component\Routing\Annotation\Route;

class PersonLikeProductController extends BasicController
{
    use Converts;
    const ROUTE_BASE = 'like';
    const ROUTE_PERSON = self::ROUTE_BASE . "_person";

    /**
     * @Route("/like", name="like")
     */
    public function index(): Response
    {
        $form = $this->createForm(
            SearchForm::class,
            null,
            [
                'action' => $this->generateUrl('like_get_list'),
                'method' => 'POST',
            ]
        );
        $table = $this->createTable(PersonLikeProductFullList::class);

        return $this->render('personLikeProduct/index.html.twig', [
            'form' => $form->createView(),
            'table' => $table
        ]);
    }

    /**
     * @Route("/like/get_list", name="like_get_list")
     * @param Request $request
     * @return Response
     */
    public function getList(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $searchForm = self::classNameToSnake(SearchForm::class);
        $personForm = self::classNameToSnake(PersonForm::class);

        if ($request->request->has($personForm)) {
            $formName = $personForm;
        } else {
            $formName = $searchForm;
        }

        $page = new PageUtility(
            $request,
            $em,
            PersonLikeProduct::class,
            $formName,
            false,
            1,
            25,
            'person_id',
            'ASC'
        );

        return $page->getResponse();
    }

    /**
     * @Route("/like/add", name="like_add")
     *
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request): Response
    {

        $createRequest = new CreateRequest();

        $personRoute = $this->generateUrl('person_autocomplete_list');

        $productRoute = $this->generateUrl('product_autocomplete_list');

        $dataRoute = [
            'person' => $personRoute,
            'product' => $productRoute
        ];

        $form = $this->createForm(CreateForm::class, $createRequest, ['data_route' => $dataRoute]);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $person_id = $createRequest->person_id;
            $product_id = $createRequest->product_id;

            $record = $em->getRepository(PersonLikeProduct::class)
                ->findOneBy(['person_id' => $person_id, 'product_id' => $product_id]);

            if (!$record) {
                $person = $em->getRepository(Person::class)->find($person_id);
                $product = $em->getRepository(Product::class)->find($product_id);

                if ($person instanceof Person && $product instanceof Product) {
                    try {
                        $record = new PersonLikeProduct($person, $product);
                        $em->persist($record);
                        $em->flush();
                    } catch (Exception $exception) {
                        return new Response($exception->getMessage());
                    }
                }

                return $this->redirectToRoute('like_person', ['id' => $person_id]);
            }
        }

        return $this->render('personLikeProduct/add.html.twig', [
            'form' => $form->createView(),
            'personRoute' => $personRoute,
            'productRoute' => $productRoute
        ]);
    }

    /**
     * @Route("/like/add_product", name="like_add_product")
     * @param Request $request
     * @return Response
     */
    public function addProductAction (Request $request): Response
    {
        $formName = self::classNameToSnake(PersonForm::class);

        $parameters = $request->request->get($formName);

        $em = $this->getDoctrine()->getManager();

        if (!empty($parameters['person_id']) && !empty($parameters['product_id'])) {
            $person_id = (int)$parameters['person_id'];
            $product_id = (int)$parameters['product_id'];

            $record = $em->getRepository(PersonLikeProduct::class)
                ->findOneBy(['person_id' => $person_id, 'product_id' => $product_id]);

            if (!$record instanceof PersonLikeProduct) {
                $person = $em->getRepository(Person::class)->find($person_id);
                $product = $em->getRepository(Product::class)->find($product_id);
                if ($person instanceof Person && $product instanceof Product) {
                    try {
                        $record = new PersonLikeProduct($person, $product);
                        $em->persist($record);
                        $em->flush();
                    } catch (Exception $exception) {
                        return new Response($exception->getMessage());
                    }
                }

            }
        }

        unset($parameters['product_name']);
        unset($parameters['product_id']);

        $em = $this->getDoctrine()->getManager();

        $request->request->set($formName, $parameters);

        $page = new PageUtility(
            $request,
            $em,
            PersonLikeProduct::class,
            PersonForm::class,
            false,
            1,
            25,
            'person_id',
            'ASC'
        );

        return $page->getResponse();
    }

    /**
     * @param int $id
     * @Route("/like/person/{id}", name=self::ROUTE_PERSON)
     * @return Response
     */
    public function personAction(int $id): Response
    {
        $productRoute = $this->generateUrl('product_autocomplete_list');

        $dataRoute = [
            'product' => $productRoute
        ];

        $person = $this->getDoctrine()->getRepository(Person::class)->find($id);
        $form = $this->createForm(
            PersonForm::class,
            [
                'person_id' => $person->getId(),
                'person_name' => $person->getFName() . ' ' . $person->getLName()
            ],
            [
                'action' => $this->generateUrl('like_add_product'),
                'method' => 'POST',
                'data_route' => $dataRoute,
            ]
        );

        $table = $this->createTable(ProductList::class);

        return $this->render('personLikeProduct/person.html.twig', [
            'form' => $form->createView(),
            'table' => $table
        ]);
    }

    /**
     * @param int $person_id
     * @param int $product_id
     * @Route("/like/delete/person_id={person_id}product_id={product_id}", name="like_delete")
     * @return Response
     */
    public function deleteAction(int $person_id, int $product_id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $record = $em->getRepository(PersonLikeProduct::class)
            ->findOneBy(['person_id' => $person_id, 'product_id' => $product_id]);

        if($record){
            try{
                $em->remove($record);
                $em->flush();
            }
            catch( Exception $e )
            {
                return new Response( $e->getMessage(), 500 );
            }
            return $this->redirectToRoute('person_like_product');
        } else {
            return new Response("Record Not Found", 500);
        }
    }
}
