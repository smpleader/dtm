<?php
/**
 * SPT software - Note type 
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: note interface
 *
 */

namespace DTM\note2\libraries\notetype;

interface INote
{
    // - content: show full data
    // - picture: show full image
    function detail($format);

    // - content: first 100 characters 
    // - picture: show thumbnail
    function preview($format);
}