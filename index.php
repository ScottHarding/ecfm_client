<?php
//elog client web app
//Woods Hole Oceanographic Institute
//Created by: Scott Harding 

require_once 'php/template.php';
require_once 'php/helper_functions.php';

// Initialize object
$tpl = new template('tpl/main.tpl');

// Set header as header.tpl
$tpl->set('header', $tpl->getFile('tpl/header.tpl'));

// Set javascript to include top level js
$tpl->set('javascript', $tpl->getFile('tpl/javascript.tpl'));

// Set object properties
$tpl->set('cruiseID', getCruiseID());
$tpl->set('authorList', getPeopleList());
$tpl->set('instrumentList', getInstrumentList());
$tpl->set('generateJSFunctions', generateJSActionFunction());

// Render the template
$tpl->render();
