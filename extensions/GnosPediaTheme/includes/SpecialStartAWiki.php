<?php

namespace GnosPediaTheme;

use SpecialPage;
use Html;

class SpecialStartAWiki extends SpecialPage {
    
    public function __construct() {
        parent::__construct('StartAWiki');
    }
    
    public function execute($subPage) {
        $this->setHeaders();
        
        $out = $this->getOutput();
        $out->setPageTitle('Start a Wiki - GnosPedia');
        
        $html = Html::openElement('div', ['class' => 'gnospedia-create-wiki-container']);
        
        // Simple header
        $html .= Html::element('h1', [], 'Start Your Own Wiki');
        $html .= Html::element('p', [], 'Create a new wiki in the GnosPedia farm');
        
        // Simple form
        $html .= Html::openElement('form', ['method' => 'post']);
        $html .= Html::element('label', ['for' => 'wiki-name'], 'Wiki Name:');
        $html .= Html::input('wikiname', '', 'text', ['id' => 'wiki-name', 'required' => true]);
        $html .= Html::element('br');
        $html .= Html::element('label', ['for' => 'wiki-subdomain'], 'Subdomain:');
        $html .= Html::input('subdomain', '', 'text', ['id' => 'wiki-subdomain', 'required' => true]);
        $html .= Html::element('br');
        $html .= Html::submitButton('Create Wiki');
        $html .= Html::closeElement('form');
        
        $html .= Html::closeElement('div');
        
        $out->addHTML($html);
    }
}