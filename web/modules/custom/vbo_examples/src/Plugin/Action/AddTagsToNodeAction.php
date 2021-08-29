<?php

namespace Drupal\vbo_examples\Plugin\Action;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\node\Entity\Node;
use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * VBO Action for adding tags to nodes.
 *
 * @Action(
 *   id = "vbo_examples_add_tags_to_node",
 *   label = @Translation("Add Tags to Node"),
 *   type = "node"
 * )
 */
class AddTagsToNodeAction extends ViewsBulkOperationsActionBase implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    if ($entity instanceof Node) {
      if ($entity->hasField('field_tags')) {
        $tags = $entity->get('field_tags')->getValue();

        $flat_tags = [];
        foreach ($tags as $tag) {
          $flat_tags[] = $tag['target_id'];
        }

        foreach ($this->configuration['new_tags'] as $new_tag) {
          $flat_tags[] = $new_tag['target_id'];
        }

        $flat_tags = array_unique($flat_tags);

        $new_tags = [];
        foreach ($flat_tags as $flat_tag) {
          $new_tags[] = ['target_id' => $flat_tag];
        }


        $entity->set('field_tags', $new_tags);
        $entity->save();

        return $this->t('Tags were added to the node.');
      }
      else {
        return $this->t('Node %type does not have tags.', ['%type' => $entity->bundle()]);
      }
    }

    return $this->t('Action can only be performed on nodes.');
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\Core\Access\AccessResultInterface $result */
    $result = $object->access('update', $account, TRUE);

    return $return_as_object ? $result : $result->isAllowed();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['new_tags'] = [
      '#title' => $this->t('New Tags'),
      '#type' => 'entity_autocomplete',
      '#target_type' => 'taxonomy_term',
      '#tags' => TRUE,
      '#selection_settings' => [
        'target_bundles' => ['tags'],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['new_tags'] = $form_state->getValue('new_tags');
  }

}
