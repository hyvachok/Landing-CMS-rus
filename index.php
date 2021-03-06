<?php

// Connecting the public controller
require_once('assets/controller.php');

// Connecting a module
require_once('modules/rand_num.php');

?>

<!DOCTYPE html>
<html>

	<head>

		<title>Landing CMS | Demo</title>
		<meta charset="utf-8">
		<meta name="HandheldFriendly" content='True'>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="copyright" content="Landing CMS">
		<meta name="description" content="Free CMS for Landing">
		<meta name="keywords" content="CMS, Landing, Free">
		<meta name="author" content="Ilia Chernykh">
		<meta name="generator" content="Landing CMS 0.0.6" />

		<style>

			html, body, #header, #main
			{
				padding: 0;
				margin: 0;
				height: 100%;
				font-family: Helvetica, Tahoma, Arial;
			}

			#header, #footer
			{
				text-align: center;
			}

			#header
			{
				background-color: #5bc0de;
				font-size: 40px;
				color: #fff;
			}

			#main
			{
				padding-left: 15%;
				padding-right: 15%;
			}

			#footer
			{
				background-color: #f5f5f5;
				height: 100px;
			}

			.center
			{
				position: relative;
				top: 50%;
				transform: translateY(-50%);
			}

			.link
			{
				font-size: 20px;
				color: #fff;
			}

			.unline
			{
				text-decoration: none;
			}

		</style>

	</head>

	<body>
		<div id="header">
			<div class="center">
				<?php if( isset($get['title']) ): ?>
					<?=$get['title'];?>
				<?php else: ?>
					Создайте <i>Строковое</i> Поле с помощью Псевдонима 'title'.
				<?php endif; ?>
				<p><a href="/cms" target="_blank" title="Go to the Admin area." class="link">Открыть CMS</a></p>
				<p><a href="https://github.com/Elias-Black/Landing-CMS" target="_blank" title="Go to the site about Landing CMS." class="link unline">Landing CMS v0.0.6</a></p>
			</div>
		</div>
		<div id="main">
			<div class="center">

				<?php if( isset($get['main']) ): ?>
					<?=$get['main'];?>
				<?php else: ?>
				Создайте <i>Многострочные/WYSIWYG</i> Поля с помощью Псевдонима 'main'.
				<?php endif; ?>

				<p>
					<?php if( isset($get['main_group']) && is_array($get['main_group']) ): ?>
						<p>
							<b>Элементы:</b>
						</p>
						<ul>
							<?php foreach($get['main_group'] as $name => $item): ?>
								<li><i><?=$name;?></i>: <?=$item;?></li>
							<?php endforeach; ?>
						</ul>
					<?php else: ?>
					Создайте <i>Группы Полей</i> с помощью Псевдонима 'main_group' и несколькими <i>Строковыми</i> элементами.
					<?php endif; ?>
				</p>

				<p>Модуль случайных чисел: <?=$rand_num;?></p>

			</div>
		</div>
		<div id="footer">
			<div class="center">
				<?php if( isset($get['footer']) ): ?>
					<?=$get['footer'];?>
				<?php else: ?>
				Создайте <i>Строковое</i> Поле с помощью Псевдонима 'footer'.
				<?php endif; ?>
			</div>
		</div>
	</body>

</html>
