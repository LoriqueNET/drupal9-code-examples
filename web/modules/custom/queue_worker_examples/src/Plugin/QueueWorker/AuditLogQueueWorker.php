<?php

namespace Drupal\queue_worker_examples\Plugin\QueueWorker;

use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @QueueWorker(
 *   id = "audit_log",
 *   title = @Translation("Audit log worker"),
 *   cron = {"time" = 60}
 * )
 */
class AuditLogQueueWorker extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * Logging channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * {@inheritDoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $logger;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory')->get('logger.channel.audit_log'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function processItem($data) {
    $this->logger->notice(
      '@user (uid: @uid) performed @op on @entity at @timestamp',
      [
        '@user' => $data['user'],
        '@uid' => $data['uid'],
        '@op' => $data['op'],
        '@entity' => $data['entity'],
        '@timestamp' => $data['timestamp']
      ],
    );
  }

}
