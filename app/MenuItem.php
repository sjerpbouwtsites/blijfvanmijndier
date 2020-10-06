<?php

namespace App;

class MenuItem {
  private $text;
  private $url;
  private $class;
  private $icon;

  public function  __construct($text, $url, $class, $icon) {
    $this->Text = $text;
    $this->Url = $url;
    $this->Class = $class;
    $this->Icon = $icon;
  }
}
