<?php header ("Content-Type:text/xml"); ?>
<?xml version="1.0" encoding="utf-8"?>
<assets current="<?php if ($data['breadcrumb']): ?><?php echo $data['breadcrumb'] ?><?php endif ?>" previous="">
<?php foreach ($data['assets'] as $asset): ?>
	<?php $metadata = get_post_custom($asset->ID); ?>
	<asset>
	<Id><?php echo $asset->ID ?></Id>
	<Title><![CDATA[<?php echo $asset->post_title ?>]]></Title>
	<SDPosterUrl><![CDATA[<?php echo isset($metadata['sd_poster_url'][0]) ? $metadata['sd_poster_url'][0] : '' ?>]]></SDPosterUrl>
	<HDPosterUrl><![CDATA[<?php echo isset($metadata['hd_poster_url'][0]) ? $metadata['hd_poster_url'][0] : '' ?>]]></HDPosterUrl>
	<Description><![CDATA[<?php echo isset($metadata['description'][0]) ? $metadata['description'][0] : '' ?>]]></Description>
	<ShortDescriptionLine1><![CDATA[<?php echo $asset->post_title ?>]]></ShortDescriptionLine1>
	<ShortDescriptionLine2><![CDATA[<?php echo isset($metadata['short_description'][0]) ? $metadata['short_description'][0] : '' ?>]]></ShortDescriptionLine2>
	<ContentType><?php echo isset($metadata['content_type'][0]) ? $metadata['content_type'][0] : '' ?></ContentType>
	<StreamFormat><?php echo isset($metadata['stream_format'][0]) ? $metadata['stream_format'][0] : '' ?></StreamFormat>
	<?php if (isset($metadata['content_type'][0])): ?>
		<?php if ($metadata['content_type'][0] == 'video'): ?>
			<StreamUrl><![CDATA[<?php echo isset($metadata['media_url'][0]) ? $metadata['media_url'][0] : '' ?>]]></StreamUrl>
			<StreamQualities>SD</StreamQualities>
		<?php else: ?>
			<Url><![CDATA[<?php echo isset($metadata['media_url'][0]) ? $metadata['media_url'][0] : '' ?>]]></Url>
		<?php endif ?>
	<?php endif ?>
	</asset>
<?php endforeach ?>
</assets>