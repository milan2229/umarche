<?php

namespace App\Constants;//フォルダの場所

class Common
{
  const PRODUCT_ADD = '1';
  const PRODUCT_REDUCE = '2';

  const PRODUCT_LIST = [
    'add' => self::PRODUCT_ADD,
    'reduce' => self::PRODUCT_REDUCE
  ];//連想配列で管理する。クラスの中でconstを選択するにはselfをつける。
}

