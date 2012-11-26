<?php
include "./zonerama.api.php";

$zonerama = new Zonerama;
// Dimensions of thumbnails, default is 140x140
// $zonerama->thumbWidth = 140;
// $zonerama->thumbHeight = 140;
// User name
$zonerama->userName = "[[YOUR ZONERAMA USERNAME]]";

$album = isset($_GET['album']) ? intval($_GET['album']) : null;

?><!DOCTYPE html>
<head>
	<meta charset="utf-8" />
	<title>Zonerama PHP API Example</title>
	<style>
	body {
		font-family: sans-serif;
	}
	#albums {
		width:300px;
		float:left;
	}
	#albums ul {
		list-style-type:none;
		margin:0;
		padding:0;
		border-right:1px solid #ccc;
		border-top:1px solid #ccc;
	}
	#albums ul li {
		margin:0;
		padding:0;
		border-bottom:1px solid #ccc;
		clear:both;
		display:block;
		height:80px;
	}
	#albums ul li a {
		display:block;
		color:#333;
		text-decoration: none;
		height:80px;
	}
	#albums ul li a:hover, #albums ul li.active a {
		background:#e5e5e5;
	}
	#albums ul li a span.info span {
		display:block;
	}
	#albums ul li a span.thumb {
		float:left;
	}
	#albums ul li a span.info {
		margin-left:6px;
	}
	#albums ul li a span.title {
		font-weight:bold;
	}
	#albums ul li a span.date, #albums ul li a span.count {
		color:#888;
		font-size:0.8em;
	}

	#photos {
		float:left;
		margin-left: 12px;
		width:600px;
	}
	#photos ul {
		list-style-type:none;
		margin:0;
		padding:0;
	}
	#photos ul li {
		float:left;
		margin:0 6px 6px 0;
	}
	</style>
</head>
<body>
	<h1>Zonerama PHP API Example</h1>


	<div id="albums">
		<ul>
		<?php
			$zonerama->thumbWidth = 80;
			$zonerama->thumbHeight = 80;
			$publicAlbums = $zonerama->publicAlbums();
			foreach($publicAlbums as $publicAlbumId=>$publicAlbum) {
				echo '
					<li>
						<a href="./example.php?album='.$publicAlbumId.'">
							<span class="thumb"><img src="'.$publicAlbum['thumb'].'" /></span>
							<span class="info">
								<span class="title">'.$publicAlbum['title'].'</span>
								<span class="date">Date: '.$publicAlbum['date'].'</span>
								<span class="count">Photos: '.$publicAlbum['count'].'</span>
							</span>
						</a>
					</li>
				';
			}
		?>
		</ul>
	</div>
	

	<?php if(!is_null($album)): ?>
	<div id="photos">
		<h2><?php echo $publicAlbums[$album]['title']; ?></h2>
		<p>
			Date: <?php echo $publicAlbums[$album]['date']; ?>
			<br />
			Photos: <?php echo $publicAlbums[$album]['count']; ?>
		</p>
		
		<ul>
			<?php
			$zonerama->thumbWidth = 140;
			$zonerama->thumbHeight = 140;
			$albumPhotos = $zonerama->albumPhotos($album);

			foreach($albumPhotos as $albumPhotoId=>$albumPhoto) {
				echo '
				<li>
					<a href="'.$albumPhoto['url'].'" title="'.(!is_null($albumPhoto['title']) ? $albumPhoto['title'] : $publicAlbums[$album]['title']).'"><img src="'.$albumPhoto['thumb'].'" /></a>
				</li>
				';
			}
			?>
		</ul>
	</div>
	<?php endif; ?>

</body>
</html>