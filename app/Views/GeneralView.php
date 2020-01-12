<?php

namespace App\Views;

use App\Views\IView;
use App\General\EnvReader;
use App\Http\ARequest;

/**
 * An abstract class which handles the display of our templates
 */
abstract class GeneralView implements IView {

    /**
     * List of params specific for the view, later to be overriden in the implementation.
     * when constructing the url, we don't need query params which are not needed for the view.
     */
    protected static $concerned_query_params = [];

    /**
     * The user request, from which to get passed values, if needed.
     */
    protected $request = null;

    /**
     * Helper property, containing the variables used within the templates
     */
    private $vars = [];

    public function __construct(ARequest $request)
    {
        $this->request = $request;
    }

    /**
     * Generates the URL, overriding parameters if passed in the array
     */
    public function url(array $replaced_values):string
    {
        $query_params = [];
        foreach(static::$concerned_query_params as $param_key) {
            if(array_key_exists($param_key, $replaced_values)) {
                $query_params[$param_key] = $replaced_values[$param_key];
                continue;
            }
            $value = $this->request->get($param_key, null);
            if($value === null) {
                continue;
            }
            $query_params[$param_key] = $value;
        }
        return $this->request->get_url() . '?' . http_build_query($query_params);
    }

    /**
     * Adds a variable for the template.
     * @TODO: I was thinking if a validation can be added here, but it is of no concern to the View to validate 
     */
    public function add_var(string $variable_name, $variable_value)
    {
        $this->vars[$variable_name] = $variable_value;

        return $this;
    }

    /**
     * Generates the templates and returns the created string.
     */
    public function render():string
    {
        ob_start();
        $this->header();
        $this->content();
        $this->footer();

        return ob_get_clean();
    }

    /**
     * Adds template depending on the filename and extracts the vars for it to use.
     */
    protected function get_template(string $template_name)
    {
        $filepath = EnvReader::get('APP_TEMPLATES_FOLDER') . "\\$template_name.php";
        
        if(file_exists($filepath)) {
            extract($this->vars);
            include $filepath;
        }
    }

    /**
     * What to be displayed in the header
     */
    protected function header()
    {
        $this->get_template("header");
    }

    /**
     * What to be displayed in the footer
     */
    protected function footer()
    {
        $this->get_template("footer");
    }

    /**
     * What to be displayed in the content of the view
     */
    abstract function content();
}