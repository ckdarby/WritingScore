<?php

use Ckdarby\WritingScore\Marker\OffensivePhrase;

/**
 * Class OffensivePhraseTest
 * @package Ckdarby\WritingScoreTest
 */
class OffensivePhraseTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @return array
     */
    private function templateHighOffensive()
    {
        return [
            'Voldemort',
            'Dark Lord',
            'mundane',
            'Pinocchio',
        ];
    }

    /**
     * @return array
     */
    private function templateLowOffensiveWords()
    {
        return [
            'eSolutions',
            'gangster',
            'ugliest',
            'destiny',
            'shooter',
            'plan',
        ];
    }

    /**
     *
     * @param $expected
     * @param $content
     * @param $lowOffensiveWords
     * @param $highOffensiveWords
     *
     * @dataProvider providerSetOffensivePhrases()
     * @return void
     */
    public function testSetOffensivePhrases($expected, $content, $lowOffensiveWords, $highOffensiveWords)
    {
        $marker = new OffensivePhrase($content);

        $this->assertEquals(
            $expected,
            $marker->setOffensivePhrases($lowOffensiveWords, $highOffensiveWords)->getAllOffensivePhrases()
        );
    }

    /**
     * @return array
     */
    public function providerSetOffensivePhrases()
    {
        return [
            [
                [
                    'eSolutions' =>  OffensivePhrase::LOW_OFFENSIVE,
                    'gangster' => OffensivePhrase::LOW_OFFENSIVE,
                    'ugliest' => OffensivePhrase::LOW_OFFENSIVE,
                    'destiny' => OffensivePhrase::LOW_OFFENSIVE,
                    'shooter' => OffensivePhrase::LOW_OFFENSIVE,
                    'plan' => OffensivePhrase::LOW_OFFENSIVE,
                    'Voldemort' => OffensivePhrase::HIGH_OFFENSIVE,
                    'Dark Lord' => OffensivePhrase::HIGH_OFFENSIVE,
                    'mundane' => OffensivePhrase::HIGH_OFFENSIVE,
                    'Pinocchio' => OffensivePhrase::HIGH_OFFENSIVE,
                ],
                "Testing testSetOffensivePhrases()",
                $this->templateLowOffensiveWords(),
                $this->templateHighOffensive(),
            ],
        ];
    }


    /**
     *
     * @dataProvider providerRun()
     *
     * @param $expected
     * @param $content
     */
    public function testRun($expected, $content)
    {
        $marker = new OffensivePhrase($content);
        $marker->setOffensivePhrases($this->templateLowOffensiveWords(), $this->templateHighOffensive());
        $marker->run();

        $this->assertEquals(
            $expected,
            $marker->getScore()
        );
    }

    /**
     * @return array
     */
    public function providerRun()
    {
        return [
            [
                3,
                "Let me sit by you and whisper sweet-nothings in your mundane ear, you gangster.",
            ],
            [
                0,
                "TEST",
            ],
            [
                5,
                "Our Dark Lord Voldemort has a plan.",
            ],
        ];
    }


    /**
     *
     * @dataProvider providerGetScore()
     *
     * @param $expected
     * @param $lowOffensiveCount
     * @param $highOffensiveCount
     */
    public function testGetScore($expected, $lowOffensiveCount, $highOffensiveCount)
    {
        $marker = new OffensivePhrase("");
        $marker->totalFoundHighOffensivePhrases = $lowOffensiveCount;
        $marker->totalFoundLowOffensivePhrases = $highOffensiveCount;

        $this->assertEquals(
            $expected,
            $marker->getScore()
        );
    }

    /**
     * @return array
     */
    public function providerGetScore()
    {
        return [
            [
                0,
                0,
                0,
            ],
            [
                6,
                2,
                2,
            ],
            [
                3,
                1,
                1,
            ],
        ];
    }

}
