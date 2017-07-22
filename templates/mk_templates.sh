if [ -z "$1" ]; then
	echo 'usage: source mk_templates.sh modulesnumber'
else

cp -a blocks/myalbum_block_rphoto.tpl blocks/myalbum$1_block_rphoto.tpl
cp -a blocks/myalbum_block_tophits.tpl blocks/myalbum$1_block_tophits.tpl
cp -a blocks/myalbum_block_tophits_p.tpl blocks/myalbum$1_block_tophits_p.tpl
cp -a blocks/myalbum_block_topnews.tpl blocks/myalbum$1_block_topnews.tpl
cp -a blocks/myalbum_block_topnews_p.tpl blocks/myalbum$1_block_topnews_p.tpl
cp -a myalbum_categories.tpl myalbum$1_categories.tpl
cp -a myalbum_footer.tpl myalbum$1_footer.tpl
cp -a myalbum_header.tpl myalbum$1_header.tpl
cp -a myalbum_imagemanager.tpl myalbum$1_imagemanager.tpl
cp -a myalbum_photo_in_list.tpl myalbum$1_photo_in_list.tpl
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_index.tpl >myalbum$1_index.tpl
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_photo.tpl >myalbum$1_photo.tpl
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_ratephoto.tpl >myalbum$1_ratephoto.tpl
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_topten.tpl >myalbum$1_topten.tpl
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_viewcat_list.tpl >myalbum$1_viewcat_list.tpl
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_viewcat_table.tpl >myalbum$1_viewcat_table.tpl

fi
