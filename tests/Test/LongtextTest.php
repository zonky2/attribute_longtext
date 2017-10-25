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
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012-2017 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_longtext/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\Test\Attribute\Longtext;

use Doctrine\DBAL\Connection;
use MetaModels\Attribute\Longtext\Longtext;
use MetaModels\Helper\TableManipulator;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests to test class longtext.
 */
class LongtextTest extends TestCase
{
    /**
     * Mock a MetaModel.
     *
     * @param string $language         The language.
     * @param string $fallbackLanguage The fallback language.
     *
     * @return \MetaModels\IMetaModel
     */
    protected function mockMetaModel($language, $fallbackLanguage)
    {
        $metaModel = $this->getMockBuilder('MetaModels\IMetaModel')->getMock();

        $metaModel
            ->expects($this->any())
            ->method('getTableName')
            ->will($this->returnValue('mm_unittest'));

        $metaModel
            ->expects($this->any())
            ->method('getActiveLanguage')
            ->will($this->returnValue($language));

        $metaModel
            ->expects($this->any())
            ->method('getFallbackLanguage')
            ->will($this->returnValue($fallbackLanguage));

        return $metaModel;
    }

    /**
     * Mock the database connection.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Connection
     */
    private function mockConnection()
    {
        return $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Mock the table manipulator.
     *
     * @param Connection $connection The database connection mock.
     *
     * @return TableManipulator|\PHPUnit_Framework_MockObject_MockObject
     */
    private function mockTableManipulator(Connection $connection)
    {
        return $this->getMockBuilder(TableManipulator::class)
            ->setConstructorArgs([$connection, []])
            ->getMock();
    }

    /**
     * Test that the attribute can be instantiated.
     *
     * @return void
     */
    public function testInstantiation()
    {
        $connection  = $this->mockConnection();
        $manipulator = $this->mockTableManipulator($connection);

        $text = new Longtext($this->mockMetaModel('en', 'en'), [], $connection, $manipulator);
        $this->assertInstanceOf('MetaModels\Attribute\Longtext\Longtext', $text);
    }


    /**
     * Test that the attribute does not accept config keys not specified via getAttributeSettingNames().
     *
     * @return void
     */
    public function testGetFieldDefinition()
    {
        $attributes = array(
            'id'             => 1,
            'pid'            => 1,
            'tstamp'         => 0,
            'name'           => array(
                'en'         => 'name English',
                'de'         => 'name German',
            ),
            'description'    => array(
                'en'         => 'description English',
                'de'         => 'description German',
            ),
            'type'           => 'base',
            'colname'        => 'baseattribute',
            'isvariant'      => 1,
            // Settings originating from tl_metamodel_dcasetting.
            'tl_class'       => 'custom_class',
            'readonly'       => 1,
            'allowHtml'      => null,
            'mandatory'      => null,
            'preserveTags'   => null,
            'decodeEntities' => null,
            'rows'           => null,
            'cols'           => null,
        );

        $serialized = array();
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $serialized[$key] = serialize($value);
            } else {
                $serialized[$key] = $value;
            }
        }

        $connection      = $this->mockConnection();
        $manipulator     = $this->mockTableManipulator($connection);
        $attribute       = new Longtext($this->mockMetaModel('en', 'en'), $serialized, $connection, $manipulator);
        $fieldDefinition = $attribute->getFieldDefinition(
            array(
                'tl_class' => 'some_widget_class',
                'readonly' => true,
                'rte'      => 'tinyMCE',
            )
        );

        $this->assertFalse(array_key_exists('filter', $fieldDefinition));
        $this->assertFalse(array_key_exists('search', $fieldDefinition));
        $this->assertEquals('textarea', $fieldDefinition['inputType']);
        $this->assertEquals('some_widget_class', $fieldDefinition['eval']['tl_class']);
        $this->assertEquals(true, $fieldDefinition['eval']['readonly']);
        $this->assertEquals('tinyMCE', $fieldDefinition['eval']['rte']);
    }
}
