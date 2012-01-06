<?php

class CTemplate
{
    private $sHtml = '';
    private $sPath = '';
    private $arrTarget = array();

    /**
     * @name __construct
     * @param string $psHtml Stores the contents of the template
     * @param string $psPath Stores the templates path
     * @return void
     */
    function __construct($psHtml=null, $psPath=null)
    {
        $this->setPath($psPath);
        $this->setTemplate($psHtml);
    }

    /**
     * Stores the templates path in a static variable
     * @name setPath
     * @param string $psPath Path where the templates are stored
     * @return void
     */
    function setPath($psPath)
    {
        if(!is_null($psPath))
        {
            $sDS = DIRECTORY_SEPARATOR;
            $this->sPath = (substr($psPath,-1) == $sDS) ? $psPath : $psPath.$sDS;
        }
    }

    /**
     * Store the HTML file for manipulation
     * @name setTemplate
     * @param string $psHtml Name of the HTML file
     * @return void
     */
    function setTemplate($psHtml)
    {
        ob_start();
        if(is_file($this->sPath.$psHtml))
        {
            include $this->sPath.$psHtml;
        }
        $this->sHtml = ob_get_contents();
        ob_clean();
    }

    /**
     * Stores an array of variables for manipulation of the template
     * @name assing
     * @param string $pTarget Location to be modified in the template
     * @param string $psValue Value to be placed in the template
     * @return void
     */
    function assign($pTarget,$psValue)
    {
        $this->arrTarget[$pTarget] = $psValue;
    }
    
    /**
     * Parse and mount HTML
     * @name getHtml
     * @return string sHtml
     */
    function getHtml()
    {
        foreach($this->arrTarget as $sTarget => $sValue)
        {
            if(!is_array($sValue))
            {
                $this->sHtml = str_replace('{'.$sTarget.'}', $sValue, $this->sHtml);
                unset($this->arrTarget[$sTarget]);
            }
        }

        $arrHtml = explode("\n", $this->sHtml);

        // Wow, foreach, foreach, foreach, foreach... Developers, developers, developers...
        foreach($arrHtml as $iLine => $sHtml)
        {
            $sReturn = '';
            foreach($this->arrTarget as $sTarget => $arrValue)
            {
                if(is_array($arrValue))
                {
                    foreach($arrValue as $sValue)
                    {
                        if(preg_match('/\{' . $sTarget . '\}/i', $sHtml))
                        {
                            $sReturn .= str_replace('{'.$sTarget.'}', $sValue, $sHtml);
                            $arrHtml[$iLine] = $sReturn;
                        }
                    }
                }
            }
        }

        return $this->sHtml = implode('',$arrHtml);
    }

    /**
     * Displays the contents of the template manipulated
     * @name display
     * @return void
     */
    function display()
    {
        echo $this->getHtml();
    }
}

?>
