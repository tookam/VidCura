<?php header ("Content-Type:text/xml"); ?>
<?xml version="1.0" encoding="utf-8"?>
<categories current="<?php if ($data['breadcrumb']): ?><?php echo $data['breadcrumb'] ?><?php endif ?>" previous="">
<?php foreach ($data['categories'] as $cat_ID => $category): ?>
	<category>
	<Id><?php echo $cat_ID ?></Id>
	<SDPosterUrl><![CDATA[<?php echo $category['sd_poster_url'] ?>]]></SDPosterUrl>
	<HDPosterUrl><![CDATA[<?php echo $category['hd_poster_url'] ?>]]></HDPosterUrl>
	<ShortDescriptionLine1><![CDATA[<?php echo $category['name'] ?>]]></ShortDescriptionLine1>
	<ShortDescriptionLine2><![CDATA[<?php echo $category['short_description_line1'] ?>]]></ShortDescriptionLine2>
	</category>
<?php endforeach ?>
</categories>