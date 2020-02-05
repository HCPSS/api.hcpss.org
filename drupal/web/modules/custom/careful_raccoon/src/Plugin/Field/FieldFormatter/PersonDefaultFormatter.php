<?php

namespace Drupal\careful_raccoon\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'careful_raccoon_person_default' formatter.
 *
 * @FieldFormatter(
 *   id = "careful_raccoon_person_default",
 *   label = @Translation("Default"),
 *   field_types = {"careful_raccoon_person"}
 * )
 */
class PersonDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {

      if ($item->email) {
        $element[$delta]['email'] = [
          '#type' => 'item',
          '#title' => $this->t('Email'),
          'content' => [
            '#type' => 'link',
            '#title' => $item->email,
            '#url' => Url::fromUri('mailto:' . $item->email),
          ],
        ];
      }

      if ($item->name) {
        $element[$delta]['name'] = [
          '#type' => 'item',
          '#title' => $this->t('Name'),
          '#markup' => $item->name,
        ];
      }

      if ($item->phone) {
        $element[$delta]['phone'] = [
          '#type' => 'item',
          '#title' => $this->t('Phone'),
          'content' => [
            '#type' => 'link',
            '#title' => $item->phone,
            '#url' => Url::fromUri('tel:' . rawurlencode(preg_replace('/\s+/', '', $item->phone))),
          ],
        ];
      }

    }

    return $element;
  }

}
