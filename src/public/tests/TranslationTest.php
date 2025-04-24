<?php


use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../GT_Plugin_EN_CZ_Translation.php';
require_once __DIR__ . '/../GT_Plugin_Public.php';

class TranslationTest extends TestCase
{

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function test_fetch_word2vec_suggestions_returns_array()
    {
        $mock_plugin_public = $this->createMock(GT_Plugin_Public::class);
        $plugin = new GT_Plugin_EN_CZ_Translation($mock_plugin_public);

        $result = $plugin->fetch_word2vec_suggestions('dog');
        $this->assertIsArray($result);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function test_fetch_word2vec_suggestions_not_empty()
    {
        $mock_plugin_public = $this->createMock(GT_Plugin_Public::class);
        $plugin = new GT_Plugin_EN_CZ_Translation($mock_plugin_public);

        $result = $plugin->fetch_word2vec_suggestions('house');
        $this->assertNotEmpty($result);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function test_handle_translation_request_empty_input()
    {
        $_POST['word_en'] = '';

        $mock_plugin_public = $this->createMock(GT_Plugin_Public::class);

        $plugin = $this->getMockBuilder(GT_Plugin_EN_CZ_Translation::class)
            ->setConstructorArgs([$mock_plugin_public])
            ->addMethods(['send_json_success', 'check_ajax_referer'])
            ->getMock();

        $plugin->expects($this->once())
            ->method('check_ajax_referer');

        $plugin->expects($this->once())
            ->method('send_json_success')
            ->with(['translation' => 'No translation found.']);

        $plugin->handle_translation_request('en', 'cz', 'word_en');

        unset($_POST['word_en']);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function test_handle_translation_request_valid_word()
    {
        $_POST['word_en'] = 'dog';

        $mock_plugin_public = $this->createMock(GT_Plugin_Public::class);

        $plugin = $this->getMockBuilder(GT_Plugin_EN_CZ_Translation::class)
            ->setConstructorArgs([$mock_plugin_public])
            ->onlyMethods(['check_ajax_referer', 'send_json_success', 'find_translation', 'fetch_word2vec_suggestions'])
            ->getMock();

        $plugin->expects($this->once())->method('check_ajax_referer');

        $plugin->expects($this->once())
            ->method('find_translation')
            ->with('dog', 'en', 'cz')
            ->willReturn('pes');

        $plugin->expects($this->once())
            ->method('fetch_word2vec_suggestions')
            ->with('dog')
            ->willReturn(['puppy', 'hound']);

        $plugin->expects($this->once())
            ->method('send_json_success')
            ->with([
                'translation' => 'pes',
                'suggestions' => ['puppy', 'hound'],
            ]);

        $plugin->handle_translation_request('en', 'cz', 'word_en');

        unset($_POST['word_en']);
    }

    public function test_find_translation_returns_translation()
    {
        $mock_plugin_public = $this->createMock(GT_Plugin_Public::class);
        $plugin = new GT_Plugin_EN_CZ_Translation($mock_plugin_public);

        // Vstupní slovo, které víme, že je v CSV
        $result = $plugin->find_translation('dog', 'en', 'cz');

        $this->assertEquals('pes', $result);
    }

    public function test_find_translation_returns_not_found()
    {
        $mock_plugin_public = $this->createMock(GT_Plugin_Public::class);
        $plugin = new GT_Plugin_EN_CZ_Translation($mock_plugin_public);

        // Slovo, které pravděpodobně v CSV není
        $result = $plugin->find_translation('nonsenseword', 'en', 'cz');

        $this->assertEquals('No translation found.', $result);
    }
}



