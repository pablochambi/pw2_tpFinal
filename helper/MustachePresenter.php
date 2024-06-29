<?php

class MustachePresenter
{
    private $mustache;
    private $partialsPathLoader;

    public function __construct($partialsPathLoader = null)
    {
        Mustache_Autoloader::register();

        $options = array();
        if ($partialsPathLoader) {
            $options['partials_loader'] = new Mustache_Loader_FilesystemLoader($partialsPathLoader);
        }

        $this->mustache = new Mustache_Engine($options);
        $this->partialsPathLoader = $partialsPathLoader;
    }

    public function render($contentFile, $data = array())
    {
        echo $this->generateHtml($contentFile, $data);
    }

    public function generateHtml($contentFile, $data = array())
    {
        // Comprobación de sesión para seleccionar el header correcto
        if (isset($_SESSION) && !empty($_SESSION)) {
            $headerFile = $this->partialsPathLoader . '/headerLoged.mustache';
        } else {
            $headerFile = $this->partialsPathLoader . '/header.mustache';
        }

        // Leer el contenido de los archivos
        $contentAsString = file_get_contents($headerFile);
        $contentAsString .= file_get_contents($contentFile);
        $contentAsString .= file_get_contents($this->partialsPathLoader . '/footer.mustache');

        // Renderizar el contenido usando Mustache
        return $this->mustache->render($contentAsString, $data);
    }

    public function generateHtmlSimple($contentFile, $data = array())
    {

        // Leer el contenido del archivo
        $contentAsString = file_get_contents($contentFile);
        // Renderizar el contenido usando Mustache
        return $this->mustache->render($contentAsString, $data);
    }


}