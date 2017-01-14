<?php

namespace Ckdarby\WritingScore\Marker;

interface MarkerInterface
{
    public function run();
    public function getScore();
    public function setContent($content);
    public function getContent();
}
