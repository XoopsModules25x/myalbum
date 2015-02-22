if [ -z "$1" ]; then
	echo 'usage: source mk_templates.sh modulesnumber'
else

cp -a blocks/myalbum_block_rphoto.html blocks/myalbum$1_block_rphoto.html
cp -a blocks/myalbum_block_tophits.html blocks/myalbum$1_block_tophits.html
cp -a blocks/myalbum_block_tophits_p.html blocks/myalbum$1_block_tophits_p.html
cp -a blocks/myalbum_block_topnews.html blocks/myalbum$1_block_topnews.html
cp -a blocks/myalbum_block_topnews_p.html blocks/myalbum$1_block_topnews_p.html
cp -a myalbum_categories.html myalbum$1_categories.html
cp -a myalbum_footer.html myalbum$1_footer.html
cp -a myalbum_header.html myalbum$1_header.html
cp -a myalbum_imagemanager.html myalbum$1_imagemanager.html
cp -a myalbum_photo_in_list.html myalbum$1_photo_in_list.html
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_index.html >myalbum$1_index.html
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_photo.html >myalbum$1_photo.html
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_ratephoto.html >myalbum$1_ratephoto.html
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_topten.html >myalbum$1_topten.html
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_viewcat_list.html >myalbum$1_viewcat_list.html
perl -pe "s/db\\:myalbum_/db\\:myalbum$1_/g" <myalbum_viewcat_table.html >myalbum$1_viewcat_table.html

fi
