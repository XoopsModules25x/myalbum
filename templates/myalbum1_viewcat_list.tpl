<{strip}>
    <div style="float:right;"><a href="<{$rss}>"><img border='0'
                                                      src='<{$xoops_url}>/modules/<{$mydirname}>/assets/images/rss.gif'></a>
    </div>
    <table width="100%" cellspacing="0" class="outer">
        <tr>
            <td class="even">
                <br>
                <{include file="db:myalbum1_header.tpl"}>
                <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                    <tr>
                        <td align='left'>
                            <span style="font-weight:bold;"><a
                                        href='index.php'><{$lang_album_main}></a>&nbsp;:&nbsp;<{$album_sub_title}></span>&nbsp;<{$lang_total}><{$photo_total_sum}>
                        </td>
                        <td align='right'>
                            <{if $lang_add_photo}><a href="submit.php?cid=<{$category_id}>"><{$lang_add_photo}><img
                                src="<{xoModuleIcons16 add.png}>"
                                border="0" alt="<{$lang_add_photo}>"
                                title="<{$lang_add_photo}>"></a><{/if}>
                        </td>
                    </tr>
                </table>
                <{if $subcategories|is_array && count($subcategories) > 0}>
                    <hr>
                    <{include file="db:myalbum1_categories.tpl"}>
                <{/if}>
            </td>
        </tr>
    </table>
    <br>
    <{if $photo_small_sum < 1 }>
        <div align="center">
            <{$lang_nomatch}>
        </div>
    <{else}>
        <table width="100%" cellspacing="0" class="outer">
            <tr>
                <td class="even" align="left">
                    <{if $photo_small_sum > 1 }>
                        <div align="center">
                            <{$lang_sortby}>&nbsp;&nbsp;
                            <{$lang_title}> (<a
                                    href="viewcat.php?cid=<{$category_id}>&amp;uid=<{$uid}>&amp;orderby=titleA"><img
                                        src="assets/images/up.gif" border="0"
                                        align="middle" alt=""></a><a
                                    href="viewcat.php?cid=<{$category_id}>&amp;uid=<{$uid}>&amp;orderby=titleD"><img
                                        src="assets/images/down.gif" border="0" align="middle"
                                        alt=""></a>)&nbsp;
                            <{$lang_date}> (<a
                                    href="viewcat.php?cid=<{$category_id}>&amp;uid=<{$uid}>&amp;orderby=dateA"><img
                                        src="assets/images/up.gif" border="0"
                                        align="middle" alt=""></a><a
                                    href="viewcat.php?cid=<{$category_id}>&amp;uid=<{$uid}>&amp;orderby=dateD"><img
                                        src="assets/images/down.gif" border="0" align="middle"
                                        alt=""></a>)&nbsp;
                            <{if $canrateview }>
                                <{$lang_rating}> (
                                <a href="viewcat.php?cid=<{$category_id}>&amp;uid=<{$uid}>&amp;orderby=ratingA"><img
                                            src="assets/images/up.gif" border="0"
                                            align="middle" alt=""></a>
                                <a
                                        href="viewcat.php?cid=<{$category_id}>&amp;uid=<{$uid}>&amp;orderby=ratingD"><img
                                            src="assets/images/down.gif" border="0" align="middle"
                                            alt=""></a>
                                )&nbsp;
                            <{/if}>
                            <{$lang_popularity}> (<a
                                    href="viewcat.php?cid=<{$category_id}>&amp;uid=<{$uid}>&amp;orderby=hitsA"><img
                                        src="assets/images/up.gif"
                                        border="0" align="middle"
                                        alt=""></a><a
                                    href="viewcat.php?cid=<{$category_id}>&amp;uid=<{$uid}>&amp;orderby=hitsD"><img
                                        src="assets/images/down.gif" border="0" align="middle"
                                        alt=""></a>)
                            <br>
                            <strong><{$lang_cursortedby}></strong>
                            <br>
                            <br>
                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                <tr>
                                    <td align='left'>
                                        <{$photonavinfo}>
                                    </td>
                                    <td align='right'>
                                        <{$photonav}>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <hr>
                    <{/if}>
                    <br>
                    <table width='100%' cellspacing='0' cellpadding='10' border='0'>
                        <{foreach from=$photos item=photo}>
                            <{include file="db:myalbum1_photo_in_list.tpl"}>
                        <{/foreach}>
                    </table>

                    <{if $photo_small_sum > 1 }>
                        <hr>
                        <div align="center">
                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                <tr>
                                    <td align='left'>
                                        <{$photonavinfo}>
                                    </td>
                                    <td align='right'>
                                        <{$photonav}>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <{/if}>

                </td>
            </tr>
        </table>
    <{/if}>
    <br>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center">
                <br>
                <{include file='db:system_notification_select.tpl'}>
                <br>
                <{include file="db:myalbum1_footer.tpl"}>
            </td>
        </tr>
    </table>
<{/strip}>
