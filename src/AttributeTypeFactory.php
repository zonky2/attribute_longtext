<?php

/**
 * This file is part of MetaModels/attribute_longtext.
 *
 * (c) 2012-2017 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage AttributeLongtext
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     David Molineus      <david.molineus@netzmacht.de>
 * @copyright  2012-2017 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_longtext/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\Attribute\Longtext;

use MetaModels\Attribute\AbstractAttributeTypeFactory;

/**
 * Attribute type factory for longtext attributes.
 */
class AttributeTypeFactory extends AbstractAttributeTypeFactory
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->typeName  = 'longtext';
        $this->typeIcon  = 'bundles/metamodelsattributelongtext/longtext.png';
        $this->typeClass = 'MetaModels\Attribute\Longtext\Longtext';
    }
}
