<table width="100%" border='0' cellspacing='5' cellpadding='0' align="center">
    <tr>
        <{foreach from=$subcategories item=cat key=count}>
        <td valign="top" align="right">
            <{if $cat.imgurl != ""}>
            <a href="<{$mod_url}>/viewcat.php?cid=<{$cat.cid}>">
                <img src="<{$cat.imgurl}>" width="120" border="0" alt=""/>
            </a>
            <{else}>
            <a href="<{$mod_url}>/viewcat.php?cid=<{$cat.cid}>">
                <img src="<{xoModuleIcons32 category.png}>" width="32" height="32" border="0" alt=""/>
            </a>
            <{/if}>
        </td>
        <td valign="top" align="left" width="33%">
            <a href="<{$mod_url}>/viewcat.php?cid=<{$cat.cid}>"><span style="font:bold 150%;"><{$cat.title}></span></a>&nbsp;<{$lang_total}><{$cat.photo_total_sum}>&nbsp;(<{$cat.photo_small_sum}>)
            <br/>
            <{foreach from=$cat.subcategories item=subcat}>
            <{if $subcat.number_of_subcat}>
            <a href="<{$mod_url}>/viewcat.php?cid=<{$subcat.cid}>"><img src="<{xoModuleIcons16 topic.png}>" width="16" height="16" alt=""/><{$subcat.title}><img
                    src='<{$mod_url}>/assets/images/subcat.gif' width='15' height='15'/></a> &nbsp;
            <{else}>
            <a href="<{$mod_url}>/viewcat.php?cid=<{$subcat.cid}>"><img src="<{xoModuleIcons16 topic.png}>" width="16" height="16" alt=""/><{$subcat.title}></a>&nbsp;(<{$subcat.photo_small_sum}>)
            &nbsp;
            <{/if}>
            <{/foreach}>
        </td>
        <{if ($count+1) is div by 3}>
    </tr>
    <tr>
        <{/if}>
        <{/foreach}>
    </tr>
</table>
