<?php

declare(strict_types=1);

namespace Framework; 

// make it abstract as we're not going to instantiate any object directly
abstract class Controller
{
    
    // So that the request property is available in any classes that extend this class, 
    // we need to change its visibility to protected.
    protected Request $request;

    // contains the property of an object that implements this interface
    protected TemplateViewerInterface $viewer;
    
    /**
     * setRequest
     *
     * @param  Request $request
     * @return void
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    
    public function setViewer(TemplateViewerInterface $viewer): void
    {
        $this->viewer = $viewer;
    }
    
}