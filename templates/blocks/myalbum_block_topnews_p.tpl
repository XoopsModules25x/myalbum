<table width='100%' cellspacing='0' cellpadding='0' border='0'>
    <tr>
        <{foreach item=photo key=count from=$block.photo}>
        <td align='center' style='margin:0;padding:5px 0;'>
            <a href="<{$block.mod_url}>/photo.php?lid=<{$photo.lid}>&amp;cid=<{$photo.cid}>"><{$photo.title}></a>
            (<{$photo.date}>)<br>
                <a href="<{$block.mod_url}>/photo.php?lid=<{$photo.lid}>&amp;cid=<{$photo.cid}>"><img
                            src="<{$photo.thumbs_url}>/<{$photo.lid}>.<{$photo.ext}>" <{$photo.width_spec}>
                            alt="<{$photo.title}>" title="<{$photo.title}>"/></a>
        </td>
        <{if $count is div by $block.cols }>
    </tr>
    <tr>
        <{/if}>
        <{/foreach}>
    </tr>
</table>
