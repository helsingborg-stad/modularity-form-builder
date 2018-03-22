<?php

namespace ModularityFormBuilder\Helper;

class SanitizeData
{
	/**
	 * Convert urls to hyperlinks
	 * @param  string $text String to be modified
	 * @return string 		Modified string
	 */
	public static function convertLinks($text) : string
	{
		$pattern = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

		// Check if there is urls in the text
		if (preg_match($pattern, $text, $url)) {
			// Make the urls hyper links
		 	$text = preg_replace($pattern, "<a href='{$url[0]}'>{$url[0]}</a> ", $text);
		}

		return $text;
	}
}
