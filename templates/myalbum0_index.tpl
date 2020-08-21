<{strip}>
    <div style="float:right;"><a href="<{$rss}>"><img border='0'
                                                      src='<{$xoops_url}>/modules/<{$mydirname}>/assets/images/rss.gif'></a>
    </div>
    <table width="100%" cellspacing="0" class="outer">
        <tr>
            <td class="even">
                <br>
                <{include file="db:myalbum0_header.tpl"}>
                <br>
                <{include file="db:myalbum0_categories.tpl"}>
                <hr>
                <form name='cid_select' action='<{$mod_url}>/viewcat.php' method='GET'
                      style='margin:0;text-align:center;'>
                    <select name='cid' onchange='submit();'>
                        <option value=''><{$lang_directcatsel}></option>
                        <{$category_options}>
                    </select> &nbsp; &nbsp;
                    <{$photo_global_sum}> &nbsp; &nbsp;
                    <{if $lang_add_photo}><a href="submit.php"><{$lang_add_photo}><img
                        src="<{xoModuleIcons16 add.png}>" border="0" alt="<{$lang_add_photo}>"
                        title="<{$lang_add_photo}>">
                        </a><{/if}>
                </form>
            </td>
        </tr>
    </table>
    <br>
    <table width="100%" cellspacing="0" class="outer">
        <tr>
            <td class="even" align="left">
                <h4><{$lang_latest_list}></h4>
                <{if $photonavdisp }>
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
                    <hr>
                <{/if}>
                <table width='100%' cellspacing='0' cellpadding='10' border='0'>
                    <{foreach from=$photos item=photo}>
                        <{include file="db:myalbum0_photo_in_list.tpl"}>
                    <{/foreach}>
                </table>
                <{if $photonavdisp }>
                    <hr>
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
                <{/if}>
            </td>
        </tr>
    </table>
    <br>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center">
                <br>
                <{include file='db:system_notification_select.tpl'}>
                <br>
                <{include file="db:myalbum0_footer.tpl"}>
            </td>
        </tr>
    </table>
<{/strip}>
