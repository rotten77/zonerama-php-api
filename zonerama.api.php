<?php
class Zonerama {
	public $userName = "";

	public $thumbWidth = 140;

	public $thumbHeight = 140;

	/**
	 * List of public albums
	 */
	public function publicAlbums($json=false) {
		$publicAlbums = array();
		$profileUrl = "http://".$this->userName.".zonerama.com/";
		/**
		 * cURL
		 */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $profileUrl);
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
		$html = curl_exec($ch);
		curl_close($ch);
		/**
		 * DOM
		 */
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		/**
		 * Xpath
		 */
		$xpath = new DOMXPath($dom);
		$albums = $xpath->evaluate("//div[@id='account-profile-albums-public-container']//div[@class='album']");
		/**
		 * Data
		 */
		foreach($albums as $album) {
			$albumTitle = $xpath->evaluate('a', $album);
			$href = $albumTitle->item(0)->getAttribute('href');

			// Get album ID
			preg_match("/\/([0-9]*)$/", $href, $matches);

			// Album info
			$albumDescription = $xpath->evaluate('p', $album);
			$albumDescriptionArray = explode("\n", trim(strip_tags($albumDescription->item(0)->nodeValue)));
			$date = null;
			$count = null;
			foreach($albumDescriptionArray as $line) {
				$line = trim($line);
				if($line!="") {
					preg_match('/([0-9]{1,2}).([0-9]{1,2}).([0-9]{4})$/', $line, $dateMatch);
					if(is_array($dateMatch) && count($dateMatch)>0) $date = strtotime($dateMatch[0]);
					
					preg_match('/^([0-9]*)/', $line, $countMatch);
					if(is_array($countMatch) && count($countMatch)>0) $count = $countMatch[0];
				}
			}

			// Thumb
			$albumThumb = $xpath->evaluate('div[@class="preview"]/a/img', $album);
			preg_match('/photos\/([0-9]*)/', $albumThumb->item(0)->getAttribute('src'), $thumbMatch);
			$id = $matches[1];
			$publicAlbums[$id]['url'] = $href;
			$publicAlbums[$id]['title'] = $albumTitle->item(0)->nodeValue;
			$publicAlbums[$id]['date'] = is_null($date) ? null : date("Y-m-d", $date);
			$publicAlbums[$id]['count'] = $count;
			$publicAlbums[$id]['thumb'] = "http://".$this->userName.".zonerama.com/photos/".$thumbMatch[1]."_".$this->thumbWidth."x".$this->thumbHeight."_16.jpg";
		}

		return $json ? json_encode($publicAlbums) : $publicAlbums;
	}

	/**
	 * Photos in album
	 */
	public function albumPhotos($albumId=0,$json=false) {

		$albumId = intval($albumId);
		if($albumId==0) return false;

		/**
		 * Input
		 */
		$photos = array();
		$albumUrl = "http://".$this->userName.".zonerama.com/Album/$albumId";
		/**
		 * cURL
		 */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $albumUrl);
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
		$html = curl_exec($ch);
		curl_close($ch);
		/**
		 * DOM
		 */
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		/**
		 * Xpath
		 */
		$xpath = new DOMXPath($dom);
		$images = $xpath->evaluate("//div[@id='album-photos']/div[@class='images']/a");
		/**
		 * Data
		 */
		foreach($images as $image) {
			$href = $image->getAttribute('href');
			if(preg_match("/^http:\/\/".$this->userName.".zonerama.com\/.*$/", $href)) {

				preg_match("/#([0-9]*)$/", $href, $matches);
				$id = $matches[1];
				$photos[$id]['url'] = $href;
				$photos[$id]['title'] = $image->getAttribute('title')!="" ? $image->getAttribute('title') : null;
				$photos[$id]['thumb'] = "http://".$this->userName.".zonerama.com/photos/".$matches[1]."_".$this->thumbWidth."x".$this->thumbHeight."_16.jpg";
			}
		}

		return $json ? json_encode($photos) : $photos;
	}
	
}