<?php

namespace Komodo\Store;

/*******************************************************************************************
 Komodo Lib - Store
 ____________________________________________________________________________________________
 *
 * Desenvolvido por: Jhonnata Paixão (Líder de Projeto)
 * Iniciado em: 15/10/2022
 * Arquivo: Store.php
 * Data da Criação Sun May 14 2023
 * Copyright (c) 2023
 *
 *********************************************************************************************/

use Komodo\Logger\Logger;
use Komodo\Map\Map;

class Store
{
    private $path;
    private $file = 'store.json';
    private $log;
    private Logger $logger;
    public Map $store;
    function __construct($path = './store', $file = 'store.json', $logger = null)
    {
        $this->path = $path;
        $this->file = $file;
        $this->logger = $logger ?: new Logger;
        $this->init();
    }

    public function init()
    {
        $this->store = new Map;

        $this->log = $this->path . '/' . $this->file;
        $this->logger->register('\\Komodo\\Logger');
        $this->logger->debug($this->file, 'Starting store');
        $this->readerFileStore();
    }

    public function prepare()
    {
        if (!is_dir($this->path)) mkdir($this->path, 0777, true);

        if (!file_exists($this->log)) {
            fopen($this->log, 'w');
            return !!file_put_contents($this->log, '{}');
        }

        return true;
    }

    private function readerFileStore()
    {
        $this->prepare();
        $this->logger->debug($this->file, 'Reading from file');
        $fileData =   json_decode(file_get_contents($this->log, true), true);

        foreach ($fileData as $key => $value) {
            $this->store->set($key, $value);
        };
    }

    public function updateFileStore()
    {
        if (!$this->prepare()) return false;
        return !!file_put_contents($this->log, $this->store->toJson());
    }

    public function set($key, $data)
    {

        if (!$this->prepare()) return false;

        $this->store->set($key, $data);
        if (!$this->updateFileStore()) return false;
        return $data;
    }

    public function delete($key)
    {
        if (!$this->prepare()) return false;
        $this->store->delete($key);

        if (!$this->updateFileStore()) return false;
        return true;
    }

    public function get($key)
    {
        $this->prepare();
        return  $this->store->get($key);
    }

    public function list()
    {
        $this->prepare();
        return $this->store->map();
    }

    public function has($key)
    {
        $this->prepare();
        return $this->store->has($key);
    }

    public function clear()
    {
        if (!file_exists($this->log)) unlink($this->log);
        return $this->store->clear();
    }
}
