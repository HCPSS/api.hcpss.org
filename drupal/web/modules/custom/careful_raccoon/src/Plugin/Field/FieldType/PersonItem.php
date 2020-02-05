<?php

namespace Drupal\careful_raccoon\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Render\Element\Email;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'careful_raccoon_person' field type.
 *
 * @FieldType(
 *   id = "careful_raccoon_person",
 *   label = @Translation("Person"),
 *   category = @Translation("General"),
 *   default_widget = "careful_raccoon_person",
 *   default_formatter = "careful_raccoon_person_default"
 * )
 */
class PersonItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    if ($this->email !== NULL) {
      return FALSE;
    }
    elseif ($this->name !== NULL) {
      return FALSE;
    }
    elseif ($this->phone !== NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $properties['email'] = DataDefinition::create('email')
      ->setLabel(t('Email'));
    $properties['name'] = DataDefinition::create('string')
      ->setLabel(t('Name'));
    $properties['phone'] = DataDefinition::create('string')
      ->setLabel(t('Phone'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    $options['email']['Length']['max'] = Email::EMAIL_MAX_LENGTH;

    $constraint_manager = \Drupal::typedDataManager()->getValidationConstraintManager();
    $constraints[] = $constraint_manager->create('ComplexData', $options);
    // @todo Add more constrains here.
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $columns = [
      'email' => [
        'type' => 'varchar',
        'length' => Email::EMAIL_MAX_LENGTH,
      ],
      'name' => [
        'type' => 'varchar',
        'length' => 255,
      ],
      'phone' => [
        'type' => 'varchar',
        'length' => 255,
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

    $random = new Random();

    $values['email'] = strtolower($random->name()) . '@example.com';

    $values['name'] = $random->word(mt_rand(1, 255));

    $values['phone'] = mt_rand(pow(10, 8), pow(10, 9) - 1);

    return $values;
  }

}
