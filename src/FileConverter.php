<?php

namespace clagiordano\weblibs\configmanager;

/**
 * Class FileConverter
 * @package clagiordano\weblibs\configmanager
 */
class FileConverter implements IConvertable
{
    /** @var IConfigurable $source */
    protected $source = null;
    /** @var IConfigurable $target */
    protected $target = null;

    /**
     * @inheritDoc
     */
    public function __construct(IConfigurable $source, IConfigurable $target)
    {
        $this->source = $source;
        $this->target = $target;
    }

    /**
     * @inheritDoc
     */
    public function convert()
    {
        $this->target->setConfig($this->source->getConfig());

        $this->target->saveConfigFile();
    }
}