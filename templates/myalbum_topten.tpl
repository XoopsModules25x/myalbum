<{strip}>
    <table width="100%" cellspacing="0" class="outer">
        <tr>
            <td class="even">
                <br>
                <{include file="db:myalbum_header.tpl"}>
                <br>

                <div align="center">

                    <{* Start Category Loop *}>
                    <{foreach item=ranking from=$rankings}>
                        <table class="outer">
                            <tr>
                                <th colspan="6" align="center"><{$ranking.title}> (<{$lang_sortby}>)</th>
                            </tr>
                            <tr>
                                <td width='7%' class="head"><{$lang_rank}></td>
                                <td width='28%' class="head"><{$lang_title}></td>
                                <td width='40%' class="head"><{$lang_category}></td>
                                <{if $canrateview }>
                                    <td width='8%' class="head" align='center'><{$lang_hits}></td>
                                    <td width='9%' class="head" align='center'><{$lang_rating}></td>
                                    <td width='8%' class="head" align='right'><{$lang_vote}></td>
                                <{else}>
                                    <td colspan='3' width='8%' class="head" align='center'><{$lang_hits}></td>
                                <{/if}>
                                <{if $xoConfig.tag}>
                                    <td colspan='3' width='8%' class="head" align='center'><{$lang_tags}></td>
                                <{/if}>
                            </tr>

                            <{* Start photo loop *}>
                            <{foreach item=photo from=$ranking.photo}>
                                <tr>
                                    <td class="even"><{$photo.rank}></td>
                                    <td class="odd" align="left"><a href='photo.php?cid=<{$photo.cid}>&amp;lid=<{$photo.lid}>'><{$photo.title}></a>
                                    </td>
                                    <td class="even" align="left"><{$photo.category}></td>
                                    <{if $canrateview }>
                                        <td class="odd" align='center'><{$photo.hits}></td>
                                        <td class="even" align='center'><{$photo.rating}></td>
                                        <td class="odd" align='right'><{$photo.votes}></td>
                                    <{else}>
                                        <td colspan='3' class="odd" align='center'><{$photo.hits}></td>
                                    <{/if}>
                                    <{if $xoConfig.tag}>
                                        <td class='even'><{include file="db:tag_bar.tpl" tagbar=$photo.tagbar}></td>
                                    <{/if}>
                                </tr>
                            <{/foreach}>
                            <{* End photo loop *}>

                        </table>
                        <br>
                    <{/foreach}>
                    <{* End Category Loop *}>

                </div>
            </td>
        </tr>
    </table>
    <br>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center">
                <{include file="db:myalbum_footer.tpl"}>
            </td>
        </tr>
    </table>
<{/strip}>
