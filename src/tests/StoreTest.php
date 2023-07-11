<?php

namespace Komodo\Store\Test;

use Komodo\Store\Store;
use PHPUnit\Framework\TestCase;

class StoreTest extends TestCase
{

    public function testNewStore()
    {
        $path = './store';
        $file = 'store.json';

        //INSTACE
        $store = new Store($path, $file);
        $this->assertInstanceOf(Store::class, $store);

        //SET
        $set = $store->set('key', "test");
        $this->assertSame("test", $set);

        //HAS
        $has = $store->has('key');
        $this->assertTrue($has);

        //GET
        $get = $store->get('key');
        $this->assertSame("test", $get);

        //LIST
        $list = $store->list();
        $this->assertArrayHasKey('key', $list);

        //HAS
        $del = $store->delete('key');
        $this->assertTrue($del);

        //CLEAN
        $store->clear();
        $cln = $store->list();
        $this->assertEmpty($cln);
    }
}
