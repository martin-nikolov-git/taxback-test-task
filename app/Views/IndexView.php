<?php

namespace App\Views;

use App\Views\GeneralView;

class IndexView extends GeneralView {
    /**
     * The view is only concerned with the following query params
     */
    protected static $concerned_query_params = ['page', 'search', 'items_per_page'];
    
    /**
     * The valid per_page options
     */
    public static $valid_per_page_options = [3, 9, 18, 30];

    /**
     * Just includes the listing template
     */
    public function content()
    {
        $this->add_var('items_per_page_options', self::$valid_per_page_options);
        $this->get_template("listing");
    }
}