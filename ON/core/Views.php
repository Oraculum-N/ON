<?php

namespace ON;

class Views
{
    protected $_viewsRegister = null;
    protected $_template = null;
    protected $_templatetype = 'xml';
    protected $_pagefile = null;

    public function __construct()
    {
        if (!defined('VIEW_DIR')):
                define('VIEW_DIR', 'views');
        endif;
        $this->_viewsRegister = new ViewsRegister();
    }

    public function addTemplate($template = null)
    {
        if (is_null($template)):
                throw new Exception('[Error '.__METHOD__.'] Template nao informado'); else:
                $templatefilexml = VIEW_DIR.'/templates/'.$template.'.xml';
        $templatefilephp = VIEW_DIR.'/templates/'.$template.'.php';
        if (file_exists($templatefilexml)):
                    $this->_template = simplexml_load_file($templatefilexml);
        $this->_templatetype = 'xml'; elseif (file_exists($templatefilephp)):
                    $this->_template = file_get_contents($templatefilephp);
        $this->_template = explode('[content]', $this->_template);
        $this->_templatetype = 'html'; else:
                    throw new Exception('[Error '.__METHOD__.'] Template nao encontrado ('.$template.') ');
        endif;
        endif;
        $this->_viewsRegister = $this->_viewsRegister->addTemplate($template);

        return $this;
    }

    public function loadPage($page = null)
    {
        if (is_null($page)):
                throw new Exception('[Error '.__METHOD__.'] Pagina nao informada'); else:
                $pagefile = VIEW_DIR.'/pages/'.$page.'.php';
        $class = ucwords($page).'View';
        if (file_exists($pagefile)):
                    $templates = $this->_viewsRegister->getTemplates();
        if (empty($templates)):
                        include_once $pagefile; else:
                        if ($this->_templatetype == 'xml'):
                            $this->_pagefile = $pagefile;
        echo $this->renderXML(); else:
                            echo $this->_template[0];
        include_once $pagefile;
        echo $this->_template[1];

        return $this;
        endif;
        endif; else:
                    throw new Exception('[Error '.__METHOD__.'] Pagina nao encontrada ('.$pagefile.') ');
        endif;
        if (class_exists($class)):
                    new $class();
        endif;
        endif;

        return $this;
    }

    private function renderXML()
    {
        foreach ($this->_template as $item=>$value):
                if ($item == 'content'):
                    include_once $this->_pagefile; elseif ($item == 'text'):
                    echo $value; elseif ($item == 'element'):
                    $this->loadElement($value);
        endif;
        endforeach;
    }

    public static function loadElement($element = null)
    {
        if (is_null($element)):
                throw new Exception('[Error '.__METHOD__.'] Elemento nao informado'); else:
                $elementfile = VIEW_DIR.'/elements/'.$element.'.php';
        if (file_exists($elementfile)):
                    include_once $elementfile; else:
                    throw new Exception('[Error '.__METHOD__.'] Elemento nao encontrado ('.$elementfile.') ');
        endif;
        endif;
    }
}

    class ViewsRegister
    {
        protected $_templates = [];

        public function addTemplate($template)
        {
            $this->_templates[] = $template;

            return $this;
        }

        public function getTemplates()
        {
            return $this->_templates;
        }
    }
