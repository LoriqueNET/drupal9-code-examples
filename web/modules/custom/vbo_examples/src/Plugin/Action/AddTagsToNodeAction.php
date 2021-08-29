<?php

namespace Drupal\vbo_examples\Plugin\Action;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\node\Entity\Node;
use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Action description.
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
        $entity->set('field_tags', array_merge($tags, $this->configuration['new_tags']));
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

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['new_tags'] = $form_state->getValue('new_tags');
  }

}
