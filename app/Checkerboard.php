<?php

namespace App;

class Checkerboard {
   

    private static $checkerboard_sequence_1 = ['checkerboard-grey', 'checkerboard-white', 'checkerboard-grey', 'checkerboard-white'];
    private static $checkerboard_sequence_2 = ['checkerboard-white', 'checkerboard-grey', 'checkerboard-white', 'checkerboard-grey'];
  

    function __construct(){
        //
    }    

/**
 * making the design like a checkerboard, 4 broad. blackwhiteblackwhitewhiteblackwhiteblack
 * when modulus 4 and 8 are equal, first sequence.
 */
private static function checkersboard_index($index = 0){
    $modulus4 = $index % 4;
    $modulus8 = $index % 8;
  
    if ($modulus4 === $modulus8) {
        return Checkerboard::$checkerboard_sequence_1[$modulus4];
    } else {
        return Checkerboard::$checkerboard_sequence_2[$modulus4];
    }
  }
  
  /**
  * Heeft iemand al eens gezegd dat PHPs array map functie shit in elkaar zit?
  * bij deze
  */
  public static function set_checkerboard($models_array) {
    $new = [];
    for ($i = 0; $i < count($models_array); $i++) {
        $model = $models_array[$i];
        $model->checkerboard_css = Checkerboard::checkersboard_index($i);
        $new[] = $model;
    }        
    return $new;
  }
}