<?php

namespace App\Http;
use App\Http\ARequest;
use App\Views\IndexView;
use App\Views;
use App\Database\PostgresBooksRepository;

/**
 * A class which handles validation for the books index request
 */
class BookIndexRequest extends ARequest {
    
    /**
     * Lists books with an author name "like" the one provided
     *
     * @params array $params The params extracted for the request
     */
    public function validate_request(array $params): bool
    {
        // @TODO: Move the magic numbers to a config file
        $items_per_page = $params['items_per_page'] ?? 9;
        if(!is_numeric($items_per_page) || 
            !in_array($items_per_page, IndexView::$valid_per_page_options) ) {
            return false;
        } else {
            $this->_params['items_per_page'] = (int) $items_per_page;
        }

        $page = $params['page'] ?? 1;
        if(!is_numeric($page)) {
            return false;
        } else {
            $this->_params['page'] = $page;
        }

        return true;
    }
}