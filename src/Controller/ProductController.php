<?php

namespace App\Controller;

use App\Component\Page\PageUtility;
use App\Entity\Product;
use App\Module\Product\Form\ProductCreateForm;
use App\Module\Product\Form\ProductSearchForm;
use App\Module\Product\Request\CreateProductRequest;
use App\Module\Product\Table\ProductList;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BasicController
{
    const ROUTE_BASE = 'product';

    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        $form = $this->createForm(
            ProductSearchForm::class,
            null,
            [
                'action' => $this->generateUrl('product_get_list'),
                'method' => 'POST',
            ]
        );

        $table = $this->createTable(ProductList::class);

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
            'table' => $table
        ]);
    }

    /**
     * @Route("/product/get_list", name="product_get_list")
     * @param Request $request
     * @return Response
     */
    public function getList(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $page = new PageUtility($request, $em, Product::class, ProductSearchForm::class);

        return $page->getResponse();
    }

    /**
     * @Route("/product/get_autocomplete", name="product_autocomplete_list")
     * @param Request $request
     * @return Response
     */
    public function getAutocomplete(Request $request): Response
    {
        $name = $request->request->get('name');

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Product::class);

        $records = [];
        if ($repository instanceof ProductRepository) {
            $records = $repository->findByName($name);
        }

        $response = new Response(json_encode($records, JSON_FORCE_OBJECT, 10), Response::HTTP_OK);

        return $response;
    }

    /**
     * @Route("/product/add", name="product_add")
     *
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request): Response
    {

        $createRequest = new CreateProductRequest();

        $form = $this->createForm(ProductCreateForm::class, $createRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = new Product();
            $product->setName($createRequest->name);
            $product->setInfo($createRequest->info);
            $product->setPublicDate($createRequest->public_date);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'product.created_successfully');

            return $this->redirectToRoute('product');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param int $id
     * @Route("/product/view/{id}", name="product_view")
     * @return Response
     */
    public function viewAction(int $id): Response
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        return $this->render('product/view.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function editAction(int $id, Request $request): Response
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $createRequest = new CreateProductRequest();

        $form = $this->createForm(ProductCreateForm::class, $createRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setName($createRequest->name);
            $product->setInfo($createRequest->info);
            $product->setPublicDate($createRequest->public_date);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'product.created_successfully');

            return $this->redirectToRoute('product');
        } else {
            $form = $this->createForm(ProductCreateForm::class, $product);
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
