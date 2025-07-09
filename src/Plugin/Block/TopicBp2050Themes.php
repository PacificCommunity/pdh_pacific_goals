<?php

namespace Drupal\pdh_pacific_goals\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
// use Drupal\Core\Link;
// use Drupal\Core\Url;

/**
 * Highlight and link to BP2050 Dashboard
 *
 * @Block(
 *   id = "topic_bp2050_themes",
 *   admin_label = @Translation("Topic Blue Pacific 2050 Themes")
 * )
 */
class TopicBp2050Themes extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    
    $config = $this->getConfiguration();

    $themes = [];
    if (!empty($config['topic_bp2050_themes'])) {
      $themes = $config['topic_bp2050_themes'];
    }
    
    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('bp2050_themes', 0, 1, true);
    
    $items = [];
    
    $target_on = '<a class="bpt-tile bpt-{{weight}}" href="{{uri}}" title="{{label}}" data-toggle="tooltip" target="_blank">{{label}}</a>';
    $target_off = '<span class="bpt-tile bpt-{{weight}}">{{label}}</span>';

    $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
      
    foreach ($terms as $term) {
      $w = $term->get('weight')->value;

      if ($term->hasTranslation($lang)) {
        $term = $term->getTranslation($lang);
      }

      $items[] = [
        '#type' => 'inline_template',
        '#template' => !empty($config['topic_bp2050_themes'][$w+1])?$target_on:$target_off,
        '#context' => [
          'weight' => $w+1,
          'label' => $term->get('name')->value,
          'uri' => $term->get('field_bp_dashboard_link')->getValue()[0][ 'uri']
        ]
      ];
    }
    
    // Display Logo
    if (array_key_exists('topic_bp2050_logo', $config) && !empty($config['topic_bp2050_logo'])) {
      $items[] = [
        '#type' => 'inline_template',
        '#template' => '<a href="https://blue-pacific-2050.pacificdata.org/" target="_blank" title="Visit the Blue Pacific 2050 Dashboard" data-toggle="tooltip" class="bpt-logo">{{label}}</a>',
        '#context' => [
          'label' => $this->t('Blue Pacific 2050 Dashboard'),
        ]
      ];
    }

    $classes = [ 'topic-bp-themes' ];
    if (array_key_exists('topic_bp2050_double', $config) && $config["topic_bp2050_double"]) {
      $classes[] = 'double-trouble';
    }

    $build = [
      '#type' => 'container',
      '#attributes' => ['class' => $classes],
      'items' => $items,
      '#attached' => [
        'library' => [
          'pdh_pacific_goals/bp2050',
        ],
      ],
    ];
    
    return $build;
  }
  
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['topic_bp2050_logo'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show BP2050 logo (link to Dashboard home page)'),
      '#return_value' => 1,
      '#default_value' => empty($config['topic_bp2050_logo']) ? 0 : 1
    );

    $form['topic_bp2050_double'] = array(
      '#type' => 'checkbox',
      '#title' => t('Double size icons'),
      '#return_value' => 1,
      '#default_value' => empty($config['topic_bp2050_double']) ? 0 : 1
    );
    
    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('bp2050_themes');
    
    $options = [];
      
    foreach ($terms as $term) {
      $options[ $term->weight + 1 ] = $term->name;
    }

    $form['topic_bp2050_themes'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Related BP2050 themes'),
      '#options' => $options,
      '#default_value' => isset($config['topic_bp2050_themes']) ? $config['topic_bp2050_themes'] : '',
    ];

    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['topic_bp2050_themes'] = $values['topic_bp2050_themes'];
    $this->configuration['topic_bp2050_logo'] = empty($values['topic_bp2050_logo']) ? 0 : 1;
    $this->configuration['topic_bp2050_double'] = empty($values['topic_bp2050_double']) ? 0 : 1;
  }

}
