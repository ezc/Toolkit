<?php

/**
 * @Author： YuBing @2014-5-23
 * 常见的直接翻译的类库都太暴力了，多音词很难识别，找了一圈，发现还是Google翻译靠谱，就用他了！
 *
 * @param $chinese_text
 * @param bool $use_four_tones 是否反馈带中文注音音调的拼音
 * @return string
 * @throws Exception
 */
function pinyin($chinese_text, $use_four_tones = false) {
	$target_url = 'http://translate.google.cn/translate_a/t?client=t&sl=zh-CN&tl=zh-CN&hl=en&sc=2&ie=UTF-8&oe=UTF-8&prev=btn&rom=1&srcrom=1&ssel=4&tsel=3&q=';

	$rt = file_get_contents($target_url . urlencode($chinese_text) . '&time=' . time());

	if (!mb_strpos($rt, $chinese_text)) {
		throw new Exception("Fail to get data from google");
	}

	/*
		Sample Response:
		$rt = '[[["四阿哥 长大需要长很长时间","四阿哥 长大需要长很长时间","Sì'ā gē zhǎng dà xūyào zhǎng hěn cháng shíjiān","Sì'ā gē zhǎng dà xūyào zhǎng hěn cháng shíjiān"]],,"zh-CN",,[["四阿哥 长大需要长很长时间",,false,false,0,0,0,0]],,,,[["zh-CN","zh-CN"]],4]';
	*/

	preg_match("/^\[\[\[([^\]]+)\]\]/", $rt, $matches);
	$pinyin = mb_strtolower(trim(explode(',', $matches[1])[2], '"'));

	if ($use_four_tones) {
		return $pinyin;
	} else {
		$replace = [
			'ā' => 'a', 'á' => 'a', 'ǎ' => 'a', 'à' => 'a',
			'ō' => 'o', 'ó' => 'o', 'ǒ' => 'o', 'ò' => 'o',
			'ē' => 'e', 'é' => 'e', 'ě' => 'e', 'è' => 'e',
			'ī' => 'i', 'í' => 'i', 'ǐ' => 'i', 'ì' => 'i',
			'ū' => 'u', 'ú' => 'u', 'ǔ' => 'u', 'ù' => 'u',
			'ǖ' => 'v', 'ǘ' => 'v', 'ǚ' => 'v', 'ǜ' => 'v',
		];

		return strtr($pinyin, $replace);
	}

}

// sample: echo pinyin('中国');
