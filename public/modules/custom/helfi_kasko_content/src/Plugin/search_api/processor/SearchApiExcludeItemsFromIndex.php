<?php

namespace Drupal\helfi_kasko_content\Plugin\search_api\processor;

use Drupal\search_api\Plugin\PluginFormTrait;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Excludes all but comprehensive school TPR units from being indexed.
 *
 * @SearchApiProcessor(
 *   id = "search_api_exclude_items_from_index",
 *   label = @Translation("Exclude items from Search API index - TPR Units"),
 *   description = @Translation("Excludes all but comprehensive school TPR units from being indexed."),
 *   stages = {
 *     "alter_items" = -50
 *   }
 * )
 */
class SearchApiExcludeItemsFromIndex extends ProcessorPluginBase {

  use PluginFormTrait;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /** @var static $processor */
    $processor = parent::create($container, $configuration, $plugin_id, $plugin_definition);

    return $processor;
  }

  /**
   * {@inheritdoc}
   */
  public function alterIndexedItems(array &$items) {
    /** @var \Drupal\search_api\Item\ItemInterface $item */
    foreach ($items as $item_id => $item) {
      $object = $item->getOriginalObject()->getValue();

      $categories = $object->get('field_categories')->getValue();

      $unit_categories = [];

      foreach ($categories as $category) {
        $unit_categories[] = $category['value'];
      }

      if (!in_array('comprehensive school', $unit_categories)) {
        unset($items[$item_id]);
        continue;
      }
    }
  }

}
