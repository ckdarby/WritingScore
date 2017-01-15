<?php

namespace Ckdarby\WritingScore\Marker;

/**
 * Class OffensivePhrase
 * @package Ckdarby\WritingScore\Marker
 */
/**
 * Class OffensivePhrase
 * @package Ckdarby\WritingScore\Marker
 */
class OffensivePhrase implements MarkerInterface
{
    const LOW_OFFENSIVE_MULTIPLIER = 1;
    const HIGH_OFFENSIVE_MULTIPLIER = 2;
    const LOW_OFFENSIVE = 'low';
    const HIGH_OFFENSIVE = 'high';


    public $totalFoundLowOffensivePhrases = 0;
    public $totalFoundHighOffensivePhrases = 0;

    private $lowOffensivePhrases = [];
    private $highOffensivePhrases = [];
    private $allOffensivePhrases = [];

    /**
     * The input text that is passed
     *
     * @var string
     */
    private $content;


    /**
     * OffensivePhrase constructor.
     * @param $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * @param $lowOffensivePhrases
     * @param $highOffensivePhrases
     * @return $this
     */
    public function setOffensivePhrases($lowOffensivePhrases, $highOffensivePhrases)
    {
        $this->lowOffensivePhrases = array_fill_keys($lowOffensivePhrases, self::LOW_OFFENSIVE);
        $this->highOffensivePhrases = array_fill_keys($highOffensivePhrases, self::HIGH_OFFENSIVE);

        $this->allOffensivePhrases = array_merge($this->lowOffensivePhrases, $this->highOffensivePhrases);

        return $this;
    }

    /**
     * Used to initiate the marker
     *
     * @return $this
     */
    public function run()
    {
        $content = $this->getContent();
        $offensivePhrases = $this->getAllOffensivePhrases();
        $regexPattern = $this->regexPattern($offensivePhrases);

        //No phrases are found at all. Quick return.
        $checkForMatches = preg_match_all($regexPattern, $content, $regexMatches);
        if ($checkForMatches === 0 || $checkForMatches === false) {
            return $this;
        }

        $regexMatches = $regexMatches[0];

        foreach ($regexMatches as $match) {
            //Preference, like to avoid using 'magical' 0 array index without being verbose with what it is.
            $matchedPhrase = $match;

            //Should exist always, defensive code
            if (!array_key_exists($matchedPhrase, $offensivePhrases)) {
                continue;
            }

            //Check low offensive
            if (strcmp($offensivePhrases[$matchedPhrase], self::LOW_OFFENSIVE) === 0) {
                $this->totalFoundLowOffensivePhrases++;
                continue;
            }

            //Must now be a high offesnive
            $this->totalFoundHighOffensivePhrases++;
        }

        return $this;
    }

    /**
     * Creates the regex pattern to look for the phrases
     *
     * @param $Phrases
     * @return string
     */
    private function regexPattern($Phrases)
    {
        return sprintf(
            '/(%s)/',
            implode("|", array_keys($Phrases))
        );
    }

    /**
     * Called after run() to retrieve score
     *
     * @return int
     */
    public function getScore()
    {
        return (
            $this->totalFoundLowOffensivePhrases * self::LOW_OFFENSIVE_MULTIPLIER
            ) + (
                $this->totalFoundHighOffensivePhrases * self::HIGH_OFFENSIVE_MULTIPLIER
            );
    }

    /**
     * Used to drop prior state
     *
     * @return $this
     */
    public function reset()
    {
        $this->content = '';
        $this->totalFoundLowOffensivePhrases = 0;
        $this->totalFoundHighOffensivePhrases = 0;

        return $this;
    }

    /**
     * @param $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getAllOffensivePhrases()
    {
        return $this->allOffensivePhrases;
    }
}
