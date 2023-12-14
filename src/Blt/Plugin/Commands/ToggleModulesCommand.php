<?php

namespace Acquia\ToggleModules\Blt\Plugin\Commands;

use Acquia\Blt\Robo\Blt;
use Acquia\Blt\Robo\BltTasks;
use Acquia\Blt\Robo\Common\YamlMunge;
use Acquia\Blt\Robo\Exceptions\BltException;
use Robo\ResultData;
use Zumba\Amplitude\Amplitude;

/**
 * Defines commands in the "drupal:toggle:modules" namespace.
 */
class ToggleModulesCommand extends BltTasks {

  /**
   * Enables and uninstalls specified modules.
   *
   * You may define the environment for which modules should be toggled by
   * passing the --environment=[value] option to this command, setting the
   * 'environnment' environment variable, or defining environment in one of your
   * BLT configuration files.
   *
   * @command drupal:toggle:modules
   *
   * @aliases dtm toggle setup:toggle-modules
   *
   * @validateDrushConfig
   */
  public function toggleModules() {
    if ($this->getConfig()->has('environment')) {
      $environment = $this->getConfigValue('environment');
    }

    if (isset($environment)) {
      // Enable modules.
      $enable_key = "modules.$environment.enable";
      $this->doToggleModules('pm-enable', $enable_key);

      // Uninstall modules.
      $disable_key = "modules.$environment.uninstall";
      $this->doToggleModules('pm-uninstall', $disable_key);
    }
    else {
      $this->say("Environment is unset. Skipping drupal:toggle:modules...");
    }
  }

  /**
   * Enables or uninstalls an array of modules.
   *
   * @param string $command
   *   The drush command to execute, e.g., pm-enable or pm-uninstall.
   * @param string $config_key
   *   The config key containing the array of modules.
   *
   * @throws \Acquia\Blt\Robo\Exceptions\BltException
   */
  protected function doToggleModules($command, $config_key) {
    if ($this->getConfig()->has($config_key)) {
      $this->say("Executing <comment>drush $command</comment> for modules defined in <comment>$config_key</comment>...");
      $modules = (array) $this->getConfigValue($config_key);
      $modules_list = implode(' ', $modules);
      $result = $this->taskDrush()
        ->drush("$command $modules_list")
        ->run();
      $exit_code = $result->getExitCode();
    }
    else {
      $exit_code = 0;
      $this->logger->info("$config_key is not set.");
    }

    if ($exit_code) {
      $this->say("There was a problem toggling modules listed in $config_key. You may want to check the outcome manually.");
      return new ResultData(0);
    }
  }

  /**
   * This will be called after the config import.
   *
   * @hook post-command drupal:config:import
   */
  public function importToggle() {
    $this->invokeCommand('drupal:toggle:modules');
  }

  /**
   * This will be called after the setup build.
   *
   * @hook post-command drupal:install
   */
  public function setupToggle() {
    $this->invokeCommand('drupal:toggle:modules');
  }

  /**
   * Initializes default template toggle modules for this project.
   *
   * @command recipes:config:init:toggle-modules
   *
   * @throws \Acquia\Blt\Robo\Exceptions\BltException
   */
  public function generateToggleModulesConfig() {
    $this->say("This command will automatically generate template toggle modules settings for this project.");
    // Sets default values for the project's blt.yml file.
    $project_yml = $this->getConfigValue('blt.config-files.project');
    $this->say("Updating {$project_yml}...");
    $project_config = YamlMunge::parseFile($project_yml);
    $project_config['modules']['local']['enable'] = [
      'dblog',
      'devel',
      'seckit',
      'views_ui',
    ];
    $project_config['modules']['local']['uninstall'] = [
      'acquia-connector',
      'shield',
    ];
    $project_config['modules']['ci']['enable'] = [];
    $project_config['modules']['ci']['uninstall'] = [
      'acquia-connector',
      'shield',
    ];
    $project_config['modules']['dev']['enable'] = [
      'acquia-connector',
      'shield',
    ];
    $project_config['modules']['dev']['uninstall'] = [];
    $project_config['modules']['test']['enable'] = [
      'acquia-connector',
      'shield',
    ];
    $project_config['modules']['test']['uninstall'] = [
      'devel',
      'views_ui',
    ];
    $project_config['modules']['prod']['enable'] = [
      'acquia-connector',
      'shield',
    ];
    $project_config['modules']['prod']['uninstall'] = [
      'devel',
      'views_ui',
    ];
    try {
      YamlMunge::writeFile($project_yml, $project_config);
      $this->say("Please edit your project blt.yml file with desired module settings.");
    }
    catch (\Exception $e) {
      throw new BltException("Unable to update $project_yml.");
    }
  }
}
