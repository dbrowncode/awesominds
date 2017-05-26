<?php
	//takes the uploaded doc file and converts it to tmp file
	//parameters: .doc file to be uploaded, path and name of tmpFile.
	function read_doc($file, $tmp) {	
        
        $fileHandle = fopen($file, "r");
		$line = @fread($fileHandle, filesize($file));   
        
        //create array of strings, each index is new line
		$lines = explode(chr(0x0D),$line);
        
        $outtext = "";
        
        foreach($lines as $thisline){
            //find the position of the null characters in the string
			$pos = strpos($thisline, chr(0x00));
			//if not null character or string is empty continue
			if (($pos !== FALSE)||(strlen($thisline)==0)){
			} 
			//otherwise add newline to string
			else{
				$outtext .= $thisline."\n";
            
           }
          /* this will find what encoding you need to use to convert the doc*/
          /*$tab = array("UTF-8", "ASCII", "Windows-1252", "ISO-8859-15", "ISO-8859-1", "ISO-8859-6", "CP1256"); 
                $chain = ""; 
                foreach ($tab as $i){ 
                  foreach ($tab as $j){ 
                      $chain = " $i$j ".iconv($i, $j, "$thisline");
                     echo $chain . '<br>' ; 
                  } 
               } 

         */  
        }
        
		fclose($fileHandle); 		
		//create name for new .txt file and write the newly created string to it.
		$tempFile = fopen($tmp,"r+");
		//matche all characters and add to new string 
		//$outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
		fputs($tempFile, $outtext);
		unlink($file);
        rewind($tempFile);
        echo 'file successfully converted <br>';
	}
?>
