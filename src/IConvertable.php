<?php

namespace clagiordano\weblibs\configmanager;

/**
 * Class FileConverter
 * @package clagiordano\weblibs\configmanager
 */
interface IConvertable
{
    /**
     * FileConverter constructor.
     *
     * @param IConfigurable $source
     * @param IConfigurable $target
     */
    public function __construct(IConfigurable $source, IConfigurable $target);

    /**
     * Converts source config to target config format
     * @return void
     */
    public function convert();
}