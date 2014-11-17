<?php

namespace ReadmeGen\Vcs;

use ReadmeGen\Vcs\Type\TypeInterface;

/**
 * VCS log parser.
 * 
 * Used to return the VCS log as an array.
 */
class Parser
{
    /**
     * @var TypeInterface
     */
    protected $vcs;

    public function __construct(TypeInterface $vcs)
    {
        $this->vcs = $vcs;
    }

    /**
     * Returns the parsed log.
     * 
     * @return array
     */
    public function parse()
    {
        return $this->vcs->parse();
    }

    /**
     * Returns the VCS parser.
     * 
     * @return TypeInterface
     */
    public function getVcsParser()
    {
        return $this->vcs;
    }
}
