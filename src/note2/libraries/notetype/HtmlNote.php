<?php
/**
 * SPT software - Note type 
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: an abtract for note object
 *
 */

namespace DTM\note2\libraries\notetype;
 
use SPT\Application\IApp; 

class HtmlNote implements INote
{
    public function __construct(IApp $app)
    {

    }

    public function detail($format)
    {

    }
    
    public function preview($format)
    {
        
    }
}