<?php
/**
 * SPT software - Note controller
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: note controller interface
 *
 */

namespace DTM\note\libraries;

interface INoteController
{
    // form to create new
    function newform();
    // save new 
    function add();
    // update existing 
    function update();
    // remove existing(s)
    function delete();
    // list record by filter(s)
    //function list();
}