<?php

  namespace Drupal\musicsearch\Plugin\Block;
  use Drupal\Core\Block\BlockBase;
  use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
  use Drupal\musicsearch\MusicSearchSalutation;
  use Symfony\Component\DependencyInjection\ContainerInterface;

  /**
   * Music Search Salutation block.
   *
   * @Block(
   *  id = "musicsearch_salutation_block",
   *  admin_label = @Translation("Music Search salutation"),
   * )
   */
  class MusicSearchSalutationBlock extends BlockBase implements ContainerFactoryPluginInterface{
    /*
     * @var \Drupal\musicsearch\MusicSearchSalutation
     * */
    protected $salutation;

    /**
     * Constructs a HelloWorldSalutationBlock.
     *
     * @param array $configuration
     *   A configuration array containing information about the plugin instance.
     * @param string $plugin_id
     *   The plugin_id for the plugin instance.
     * @param string $plugin_definition
     *   The plugin implementation definition.
     * @param \Drupal\musicsearch\MusicSearchSalutation $salutation
     *   The salutation service.
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, MusicSearchSalutation $salutation) {
      parent::__construct($configuration, $plugin_id, $plugin_definition);
      $this->salutation = $salutation;
    }

    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition){
      return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get("musicsearch.salutation")
      );
    }

    /**
     * {@inheritdoc}
     */
    public function build() {
      return [
        '#markup' => $this->salutation->getSalutation(),
      ];
    }

  }
