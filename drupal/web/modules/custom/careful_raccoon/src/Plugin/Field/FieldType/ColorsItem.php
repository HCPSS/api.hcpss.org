<?php

namespace Drupal\careful_raccoon\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'colors' field type.
 *
 * @FieldType(
 *   id = "colors",
 *   label = @Translation("Colors"),
 *   category = @Translation("General"),
 *   default_widget = "colors",
 *   default_formatter = "colors_default"
 * )
 */
class ColorsItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    if ($this->primary !== NULL) {
      return FALSE;
    }
    elseif ($this->secondary !== NULL) {
      return FALSE;
    }
    elseif ($this->highlight !== NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $properties['primary'] = DataDefinition::create('string')
      ->setLabel(t('Primary'));
    $properties['secondary'] = DataDefinition::create('string')
      ->setLabel(t('Secondary'));
    $properties['highlight'] = DataDefinition::create('string')
      ->setLabel(t('Highlight'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    // @todo Add more constrains here.
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $columns = [
      'primary' => [
        'type' => 'varchar',
        'length' => 7,
      ],
      'secondary' => [
        'type' => 'varchar',
        'length' => 7,
      ],
      'highlight' => [
        'type' => 'varchar',
        'length' => 7,
      ],
    ];

    $schema = [
      'columns' => $columns,
      // @DCG Add indexes here if necessary.
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {

    $values['primary'] = '#111AAA';

    $values['secondary'] = '#111AAA';

    $values['highlight'] = '#111AAA';

    return $values;
  }
}
