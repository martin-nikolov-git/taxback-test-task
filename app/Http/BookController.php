<?php
namespace App\Http;
use App\Http\BookIndexRequest;
use App\Database\IBooksRepository;
use App\Views\IndexView;
use App\Views\IView;

/**
 * Handles book requests
 */
class BookController {
    /**
     * Repository to get books from
     */
    protected $bookRepository;

    /**
     * @params App\Database\IBooksRepository $repo The object, from which to query book objects
     */
    public function __construct(IBooksRepository $repo)
    {
        $this->bookRepository = $repo;
    }

    /**
     * Handles a listing request, if there is a search it will provide filtered books, otherwise
     * it will return a View object with a render method
     *
     * @params App\Http\BookIndexRequests $request The request sent from the client
     */
    public function index(BookIndexRequest $request) : IView
    {
        $items_per_page = $request->get('items_per_page') ?? 9;
        $page = $request->get('page') ?? 1;
        $search = $request->get('search', null);

        if($search !== null) {
            $books_list = $this->bookRepository->search($search, $page - 1, $items_per_page);
            $all_items_count = $this->bookRepository->count_filtered($search);
        } else {
            $books_list = $this->bookRepository->list($page - 1, $items_per_page);
            $all_items_count = $this->bookRepository->count();
        }

        //Set view params
        $view = new IndexView($request);
        
        if($search !== null) {
            $view->add_var('search', $search);
        }

        $view->add_var('books', $books_list);
        $view->add_var('items_count', $all_items_count);
        $view->add_var('page', $page);
        $view->add_var('items_per_page', $items_per_page);

        return $view;
    }
}