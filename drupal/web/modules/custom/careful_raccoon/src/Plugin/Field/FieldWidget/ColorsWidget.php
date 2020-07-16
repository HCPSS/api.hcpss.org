<?php

namespace Drupal\careful_raccoon\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Defines the 'colors' field widget.
 *
 * @FieldWidget(
 *   id = "colors",
 *   label = @Translation("Colors"),
 *   field_types = {"colors"},
 * )
 */
class ColorsWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['primary'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Primary'),
      '#default_value' => isset($items[$delta]->primary) ? $items[$delta]->primary : NULL,
      '#size' => 20,
    ];

    $element['secondary'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Secondary'),
      '#default_value' => isset($items[$delta]->secondary) ? $items[$delta]->secondary : NULL,
      '#size' => 20,
    ];

    $element['highlight'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Highlight'),
      '#default_value' => isset($items[$delta]->highlight) ? $items[$delta]->highlight : NULL,
      '#size' => 20,
    ];

    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'container-inline';
    $element['#attributes']['class'][] = 'colors-elements';
    $element['#attached']['library'][] = 'careful_raccoon/colors';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $violation, array $form, FormStateInterface $form_state) {
    return isset($violation->arrayPropertyPath[0]) ? $element[$violation->arrayPropertyPath[0]] : $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      if ($value['primary'] === '') {
        $values[$delta]['primary'] = NULL;
      }
      if ($value['secondary'] === '') {
        $values[$delta]['secondary'] = NULL;
      }
      if ($value['highlight'] === '') {
        $values[$delta]['highlight'] = NULL;
      }
    }
    return $values;
  }

}
