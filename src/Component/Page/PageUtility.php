<?php

namespace App\Component\Page;

use App\Component\Traits\Converts;
use App\Component\Traits\Error;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class PageUtility
{
    /**
     * used traits
     */
    use Converts, Error;

    /**
     * constants
     */
    const SORT_ASC = 'ASC';
    const SORT_DESC = 'DESC';

    /**
     * parameters
     */
    /**
     * Request
     * @var Request
     */
    private $request;

    /**
     * Entity Manager
     * @var ObjectManager
     */
    private $em;

    /**
     * Entity name of the database table
     * @var string
     */
    private $entityName;

    /**
     * Form Name
     * @var string
     */
    private $formName;

    /**
     * Number of records per page
     * @var int
     */
    private $pageSize;

    /**
     * Actual page
     * @var int
     */
    private $page;

    /**
     * Sort field
     * @var string
     */
    private $sortBy;

    /**
     * Sort direction
     * @var string
     */
    private $sortOrder;

    /**
     * Parameters
     * @var array
     */
    private $parameters;

    /**
     * Support pagination
     * @var bool
     */
    private $supportPagination;

    /**
     * PageUtility constructor.
     * @param Request $request
     * @param ObjectManager $em
     * @param $entity_name
     * @param $form_name
     * @param $support_pagination
     * @param int $page
     * @param int $page_size
     * @param string $sort_by
     * @param string $sort_order
     */
    public function __construct(
        Request $request,
        ObjectManager $em,
        string $entity_name,
        string $form_name = '',
        bool $support_pagination = true,
        int $page = 1,
        int $page_size = 10,
        string $sort_by = 'id',
        string $sort_order = 'DESC'
    ) {
        $this->request = $request;
        $this->em = $em;
        $this->entityName = $entity_name;
        $this->formName = self::classNameToSnake($form_name);
        $this->supportPagination = $support_pagination;
        $this->sortBy = $sort_by;
        $this->sortOrder = $sort_order;
        $this->page = $page;
        $this->pageSize = $page_size;
        $this->setParameters();
    }

    /**
     * Return Response for request
     * @return Response
     */
    public function getResponse(): Response
    {
        // get records
        $records = $this->getRecords();

        // check errors
        if ($this->hasErrors()) {
            return new Response(json_encode([
                'success' => false,
                'errors' => $this->getErrors(),
            ], JSON_FORCE_OBJECT, 10), Response::HTTP_OK);
        }

        return new Response(json_encode([
            'success' => true,
            'records' => $records,
            'parameters' => $this->getParameters(),
        ], JSON_FORCE_OBJECT, 10), Response::HTTP_OK);
    }

    /**
     * Get the records for the current page
     *
     * @return array
     */
    private function getRecords(): array
    {
        try {
            // get count total rows for query
            if ($this->supportPagination) {

                // get Query Builder
                $queryBuilder = $this->setQueryBuilder();
                // check instance Query Builder
                if (!$queryBuilder instanceof QueryBuilder) {
                    throw new Exception('Wrong instance of Query Builder');
                }
                // get aliases from Query Builder
                $aliases = $queryBuilder->getAllAliases();

                // set select for count
                $query = $queryBuilder->select('COUNT(' . $aliases[0] . '.' . $this->sortBy . ')')
                    ->getQuery();

                // get total rows
                $total = $query->getSingleScalarResult();

                // set parameter total_record - for response
                $this->parameters['total_records'] = $total;

                // get Max page
                $maxPage = ceil($total/$this->pageSize);

                // check actual page
                if ($this->page > $maxPage) {
                    $this->page = $maxPage;
                    $this->parameters['page'] = $this->page;
                }
            }

            // get Query Builder
            $queryBuilder = $this->setQueryBuilder();

            // check instance
            if (!$queryBuilder instanceof QueryBuilder) {
                throw new Exception('Wrong instance of Query Builder');
            }

            // get records
            $records = $queryBuilder
                ->setFirstResult(($this->page - 1) * $this->pageSize) // set the offset
                ->setMaxResults($this->pageSize) // set the limit;
                ->getQuery()
                ->getArrayResult();
        } catch (Exception $exception) {
            // add error
            $this->addError($exception->getMessage());
            $records = [];
        }

        return $records;
    }

    /**
     * Get the parameters for the page display
     *
     * @return array
     */
    private function getParameters(): array
    {
        // check form name
        if (!empty($this->formName)) {
            return [
                $this->formName => $this->parameters
            ];
        }

        return $this->parameters;
    }

    /**
     * Set Parameters
     */
    private function setParameters(): void
    {
        // get parameters from request
        $parameters = $this->request->request->all();

        // check form name
        if (!empty($this->formName)) {
            $parameters = $parameters[$this->formName];
        }

        // check parameter page
        if (!isset($parameters['page'])) {
            $parameters['page'] = $this->page;
        } else {
            $this->page = $parameters['page'];
        }

        // check actual page
        if ($this->page < 1) {
            $this->page = 1;
            $parameters['page'] = $this->page;
        }

        // check parameter page size
        if (!isset($parameters['page_size'])) {
            $parameters['page_size'] = $this->pageSize;
        } else {
            $this->pageSize = $parameters['page_size'];
        }

        // check parameter sort by
        if (!isset($parameters['sort_by'])) {
            $parameters['sort_by'] = $this->sortBy;
        } else {
            $this->sortBy = $parameters['sort_by'];
        }

        // check parameter sort order
        if (!isset($parameters['sort_order'])) {
            $parameters['sort_order'] = $this->sortOrder;
        } else {
            $this->sortOrder = $parameters['sort_order'];
        }

        // assign parameters
        $this->parameters = $parameters;
    }

    /**
     * Set Query Builder
     *
     * @return QueryBuilder
     */
    private function setQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->em
            ->getRepository($this->entityName)
            ->search($this->parameters);

        return $queryBuilder;
    }
}