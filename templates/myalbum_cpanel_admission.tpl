<h3 style='text-align:left;'><{$admin_title}></h3>
<{if $smarty.get.mes|default:false}><p><span style='color:blue;'>(<{$smarty.get.mes}>)</span></p><{/if}>
<table width='95%' border='0' cellpadding='4' cellspacing='0'>
    <tr>
        <td>

            <form action='' method='GET' style='margin-bottom:0;text-align:right;'>
                <!--
          <input type='hidden' name='num' value='<{$num}>'>
          <input type='text' name='txt' value='<{$txt}>'>
          <input type='submit' value='<{$smarty.const._ALBM_AM_BUTTON_EXTRACT}>'> &nbsp;
              -->
                <{$nav_html}> &nbsp;
            </form>

            <form name='MainForm' action='' method='POST' style='margin-top:0;'>
                <input type='hidden' name='action' value=''>
                <table width='95%' class='outer' cellpadding='4' cellspacing='1'>
                    <tr valign='middle'>
                        <th width='5'><input type='checkbox' name='dummy'
                                             onclick="with(document.MainForm){for(var i=0;i<length;i++){if(elements[i].type=='checkbox'){elements[i].checked=this.checked;}}}">
                        </th>
                        <th><{$smarty.const._AM_TH_THUMBNAIL}></th>
                        <th><{$smarty.const._AM_TH_SUBMITTER}></th>
                        <th><{$smarty.const._AM_TH_TITLE}></th>
                        <th><{$smarty.const._AM_TH_DESCRIPTION}></th>
                        <th><{$smarty.const._AM_TH_CATEGORIES}></th>
                        <th><{$smarty.const._ALBM_ACTION}></th>
                    </tr>
                    <{foreach item=photo from=$photos|default:null key=lid}>
                        <tr class="<{cycle values='even,odd'}>">
                            <td><input type='checkbox' name='ids[]' value='<{$photo.photo.lid}>'></td>
                            <td><img src='<{$thumbs_url}>/<{$photo.photo.lid}>.<{$photo.photo.ext}>'></td>
                            <td><{$photo.user.uname}></td>
                            <td><a href='<{$photos_url}>/<{$photo.lid}>.<{$photo.ext}>'
                                   target='_blank'><{$photo.photo.title}></a></td>
                            <td width='100%'><{$photo.text.description}></td>
                            <td><{$photo.cat.title}></td>
                            <td align="center"><a
                                        href='<{$xoops_url}>/modules/<{$mydirname}>/editphoto.php?lid=$photo.photo.lid'
                                        target='_blank'><img
                                            src='<{xoModuleIcons16 edit.png}>' border='0'
                                            alt='<{$smarty.const._ALBM_EDITTHISPHOTO}>'
                                            title='<{$smarty.const._ALBM_EDITTHISPHOTO}>'></a></td>
                        </tr>
                    <{/foreach}>
                    <tr>
                        <!-- <td colspan='4' align='left'>"._ALBM_AM_LABEL_ADMIT."<input type='submit' name='admit' value='"._ALBM_AM_BUTTON_ADMIT."'></td> -->
                        <td colspan='9' align='left'><{$smarty.const._ALBM_AM_LABEL_ADMIT}><input type='button'
                                                                                                  value='<{$smarty.const._ALBM_AM_BUTTON_ADMIT}>'
                                                                                                  onclick='document.MainForm.action.value="admit"; submit();'>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='9' align='left'><{$smarty.const._ALBM_AM_LABEL_REMOVE}><input type='button'
                                                                                                   value='<{$smarty.const._ALBM_AM_BUTTON_REMOVE}>'
                                                                                                   onclick='if(confirm("<{$smarty.const._ALBM_AM_JS_REMOVECONFIRM}>")){document.MainForm.action.value="delete"; submit();}'>
                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>
