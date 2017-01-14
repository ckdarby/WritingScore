<?php
/**
 * Created by PhpStorm.
 * User: ckdarby
 * Date: 1/14/17
 * Time: 2:58 AM
 */

namespace Ckdarby\WritingScore\Marker;


interface MarkerInterface
{
    public function run();
    public function getScore();
    public function setContent($content);
    public function getContent();

}