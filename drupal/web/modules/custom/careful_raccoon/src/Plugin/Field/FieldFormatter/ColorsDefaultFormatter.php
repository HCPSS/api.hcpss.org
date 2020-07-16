<?php

namespace Drupal\careful_raccoon\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'colors_default' formatter.
 *
 * @FieldFormatter(
 *   id = "colors_default",
 *   label = @Translation("Default"),
 *   field_types = {"colors"}
 * )
 */
class ColorsDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {

      if ($item->primary) {
        $element[$delta]['primary'] = [
          '#type' => 'item',
          '#title' => $this->t('Primary'),
          '#markup' => $item->primary,
        ];
      }

      if ($item->secondary) {
        $element[$delta]['secondary'] = [
          '#type' => 'item',
          '#title' => $this->t('Secondary'),
          '#markup' => $item->secondary,
        ];
      }

      if ($item->highlight) {
        $element[$delta]['highlight'] = [
          '#type' => 'item',
          '#title' => $this->t('Highlight'),
          '#markup' => $item->highlight,
        ];
      }

    }

    return $element;
  }

}
