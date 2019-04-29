<?php

namespace palente\luckyblock;

use pocketmine\plugin\PluginBase;

use pocketmine\Player;

use pocketmine\Server;

use pocketmine\utils\Config;

use palente\luckyblock\Events;

class Main extends PluginBase {

	/** @var $main and $config instances */
    public static $main, $config;

	/** @var $economyPlugin and $mode_eco economyAPI plugin variables */
	public $economyPlugin;
	public $mode_eco = false;

	/** @var $piggyPlugin and $mode_enc PiggyCustomEnchant plugin variables */
	public $piggyPlugin;
	public $mode_enc = false;

	/** @var $prefix the prefix */
	public $prefix = "§e[§bLuckyBlock§e]§r" . " ";

	/**
	 * When the plugin is started.
	 */
	public function onEnable(){
		# Register events class:
		$this->getServer()->getPluginManager()->registerEvents(new Events(), $this);

		# Creating the configuration if it is not done and updating it:
		if(file_exists($this->getDataFolder() . "config.yml")){
			$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

            if($config->get("version") !== $this->getDescription()->getVersion() or !$config->exists("version")){
				$this->getLogger()->warning("Critical changes have been made in the new version of the plugin and it seem that your config.yml is a older config.");
				$this->getLogger()->warning("Your config has been updated, be careful to check the content change !");
				$this->getLogger()->info("You can find your old config in OldConfig.yml file.");

				rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "oldConfig.yml");
				$this->saveResource("config.yml", true);
			}
		} else {
			$this->getLogger()->info("The LuckyBlock config as been created !");
			$this->saveResource("config.yml");
		}

		# Register statics:
		self::$main = $this;
		self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		
		# Enabling the use of the EconomyAPI plugin:
		if(self::getDefaultConfig()->get("usage_of_EconomyAPI") == "true"){
			if($this->getServer()->getPluginManager()->getPlugin("EconomyAPI")){
				$this->economyPlugin = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
				$this->mode_eco = true;
			} else {
				$this->getLogger()->error("You have enabled the usage of the plugin EconomyAPI but the plugin is not found.");
			}
		}

		# Enabling the use of the PiggyCustomEnchant plugin:
		if(self::getDefaultConfig()->get("usage_of_PiggyCustomEnchants") == "true"){
			if($this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants")){
				$this->piggyPlugin = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
				$this->mode_enc = true;
			} else {
				$this->getLogger()->error("You have enabled the usage of the plugin PiggyCustomEnchants but the plugin is not found.");
			}
		}
	}

	/**
	 * Return instance of Main class.
	 * @return Main
	 */
	public static function getInstance() : Main {
		return self::$main;
	}

	/**
     * Return instance of plugin config.
     * @return Config
     */
    public static function getDefaultConfig() : Config {
        return self::$config;
    }
}