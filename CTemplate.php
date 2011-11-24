<?php
/*
 *      Copyright 2010 Chavão <fale@chavao.net>
 * 		Chavão Template Engine - http://www.chavao.net
 *      
 * 		Redistribution and use in source and binary forms, with or without
 *      modification, are permitted provided that the following conditions are
 *      met:
 *      
 * 		1. 	Redistributions of source code must retain the above copyright
 *			notice, this list of conditions and the following disclaimer.
 * 		2. 	Redistributions in binary form must reproduce the above copyright
 *    		notice, this list of conditions and the following disclaimer in the
 *    		documentation and/or other materials provided with the distribution.
 * 		3. 	All advertising materials mentioning features or use of this software
 *    		must display the following acknowledgement:
 *      	This product includes software developed by the University of
 *      	California, Berkeley and its contributors.
 * 		4. 	Neither the name of the University nor the names of its contributors
 *    		may be used to endorse or promote products derived from this software
 *    		without specific prior written permission.
 *      
 *      THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 *      "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 *      LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 *      A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 *      OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *      SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 *      LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 *      DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 *      THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *      (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 *      OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

class CTemplate {
	var $sHtml;
	static $sPath;
	
	/**
	 * @name __construct
	 * @param string $psHtml Stores the contents of the template
	 * @param string $psPath Stores the templates path
	 * @return void
	 */
	function __construct($psHtml=null,$psPath=null) 
	{
		if(!is_null($psPath))
		{
			self::$sPath = (substr($psPath,-1)=="/") ? $psPath : $psPath."/";
		}		

		if(!is_null($psHtml))
		{
			ob_start();
			if(is_file(self::$sPath.$psHtml)) 
			{
				include self::$sPath.$psHtml;
			}
			$this->sHtml = ob_get_contents();
			ob_clean();
		}
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
			self::$sPath = (substr($psPath,-1)=="/") ? $psPath : $psPath."/";
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
		if(is_file(self::$sPath.$psHtml)) 
		{
			include self::$sPath.$psHtml;
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
	 * Displays the contents of the template manipulated
	 * @name display
	 * @return void
	 */
	function display() 
	{
		foreach($this->arrTarget as $sTarget => $sValue)
		{
			if(!is_array($sValue))
			{
				$this->sHtml = str_replace("{".$sTarget."}",$sValue,$this->sHtml);
				unset($this->arrTarget[$sTarget]);
			}
		}
		
		$arrHtml = explode("\n",$this->sHtml);
		
		// Wow, foreach, foreach, foreach, foreach... Developers, developers, developers...
		foreach($arrHtml as $iLine => $sHtml)
		{
			foreach($this->arrTarget as $sTarget => $arrValue)
			{
				if(is_array($arrValue))
				{
					foreach($arrValue as $sValue)
					{
						if(preg_match('/\{' . $sTarget . '\}/i',$sHtml))
						{
							$sReturn .= str_replace("{".$sTarget."}",$sValue,$sHtml);
							$arrHtml[$iLine] = $sReturn;
						}
					}
				}
			}
			
			unset($sReturn);
		}
		
		$this->sHtml = implode("",$arrHtml);
		
		echo $this->sHtml;
	}
}

?>
