<?php header ("Content-Type:text/xml"); ?>
<?xml version="1.0" encoding="utf-8"?>
<theme>
  <OverhangOffsetSD_X><?php echo $data['theme']['overhang_offset_sd_x'] ?></OverhangOffsetSD_X>
  <OverhangOffsetSD_Y><?php echo $data['theme']['overhang_offset_sd_y'] ?></OverhangOffsetSD_Y>
  <OverhangSliceSD><![CDATA[<?php echo $data['theme']['overhang_slice_sd'] ?>]]></OverhangSliceSD>
  <OverhangLogoSD><![CDATA[<?php echo $data['theme']['overhang_logo_sd'] ?>]]></OverhangLogoSD>
  <OverhangOffsetHD_X><?php echo $data['theme']['overhang_offset_hd_x'] ?></OverhangOffsetHD_X>
  <OverhangOffsetHD_Y><?php echo $data['theme']['overhang_offset_hd_y'] ?></OverhangOffsetHD_Y>
  <OverhangSliceHD><![CDATA[<?php echo $data['theme']['overhang_slice_hd'] ?>]]></OverhangSliceHD>
  <OverhangLogoHD><![CDATA[<?php echo $data['theme']['overhang_logo_hd'] ?>]]></OverhangLogoHD>
  <BreadcrumbTextRight><?php echo $data['theme']['breadcrumb_text_right'] ?></BreadcrumbTextRight>
  
  <BreadcrumbTextLeft><?php echo $data['theme']['breadcrumb_text_left'] ?></BreadcrumbTextLeft>
  <BackgroundColor><?php echo $data['theme']['background_color'] ?></BackgroundColor>
</theme>