<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineQueryResultPhoto;
use Longman\TelegramBot\Entities\InlineQueryResultArticle;
use Longman\TelegramBot\Request;

/**
 * Inline query command
 */
class InlinequeryCommand extends SystemCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'inlinequery';
    protected $description = 'Reply to inline query';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $update = $this->getUpdate();
        $inline_query = $update->getInlineQuery();
        $query = $inline_query->getQuery();

        $data = ['inline_query_id' => $inline_query->getId()];
	$id = 001;

        $array_article = [];

	if($query == "") {
	        $array_article[] = new InlineQueryResultArticle(['id' => "$id", 'title' => "Info message", 'message_text' => "Info about this bot.", 'input_message_content' => [ 'message_text' => 'This bot will rainbowify all the text you send him. To use simply type

@lolcatzbot Text

in any chat :)
Written by Daniil Gentili (@danogentili, https://daniil.it). Check out my other bots: @video_dl_bot, @mklwp_bot, @caption_ai_bot, @cowsaysbot, @cowthinksbot, @figletsbot, @lolcatzbot, @filtersbot, @id3bot, @pwrtelegrambot, @audiokeychainbot!
Source code @ http://github.com/danog/lolcatzbot', 'parse_mode' => 'Markdown' ] ]);
	} else {
		$url = shell_exec('export LANG=it_IT.UTF-8;export LC_ALL=it_IT.UTF-8;txt='.escapeshellarg($query).'; echo "$txt" 2>&1 | /usr/games/lolcat -F 10 -f 2>&1 | ansi-to-html 2>&1 | sed \'s/^/<style>span { font-size: 400%; }<\/style>/g\' | wkhtmltoimage --width $(echo "$txt" | wc -L) --quality 100 -q -f jpg - - | curl -T - chunk.io | tr -d "\n"') . '.jpg';
		$data_array = array("id" => "$id",
'title' => "Lolcatified text",
'description' => $query,
"photo_url" => $url,
"thumb_url" => $url );
	        $array_article[] = new InlineQueryResultPhoto($data_array);
//	        $array_article[] = new InlineQueryResultArticle(['id' => "$id", 'title' => "Info message", 'message_text' => "Info about this bot.", 'input_message_content' => [ 'message_text' => var_export(escapeshellarg($query), true) ] ]);
	}
        $data['results'] = '[' . implode(',', $array_article) . ']';

        return Request::answerInlineQuery($data);
    }
}
