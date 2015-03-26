<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package    MetaModels
 * @subpackage Tests
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace MetaModels\Test\Attribute\Longtext;

use MetaModels\Attribute\Longtext\Longtext;

/**
 * Unit tests to test class longtext.
 */
class LongtextTest extends \PHPUnit_Framework_TestCase
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
        $metaModel = $this->getMock(
            'MetaModels\MetaModel',
            array(),
            array(array())
        );

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
     * Test that the attribute can be instantiated.
     *
     * @return void
     */
    public function testInstantiation()
    {
        $text = new Longtext($this->mockMetaModel('en', 'en'));
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

        $attribute       = new Longtext($this->mockMetaModel('en', 'en'), $serialized);
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
