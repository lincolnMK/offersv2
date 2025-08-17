<?php
/** abstract class Controller {
*    protected function view($view, $params = []) {
*    extract($params);
*    $viewFile = __DIR__ . '/../views/' . $view . '.php';
*    $layout = __DIR__ . '/../views/layouts/main.php';
*    ob_start();
*    require $viewFile;
*    $content = ob_get_clean();
*    require $layout;
*  }
* }
*/


abstract class Controller {
    /**
     * Render a view with optional layout
     *
     * @param string $view   The view file inside /views/
     * @param array  $params Variables passed to the view
     * @param mixed  $layout Layout type: 
     *                       - null (default): use main.php
     *                       - false: no layout
     *                       - string: use layouts/{string}.php
     */
    protected function view($view, $params = [], $layout = null) {
        extract($params);

        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: $viewFile");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Handle layout logic
        if ($layout === false) {
            // No layout, just render raw view
            echo $content;
        } else {
            // Default to "main" if layout is null
            $layout = $layout ?? 'main';
            $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';

            if (!file_exists($layoutFile)) {
                throw new Exception("Layout file not found: $layoutFile");
            }

            require $layoutFile;
        }
    }
}
